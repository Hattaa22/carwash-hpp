<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComponentController extends Controller
{
    /**
     * Display a listing of components
     */
    public function index()
    {
        $components = Component::orderBy('name')->paginate(15);
        return view('admin.components', compact('components'));
    }

    /**
     * Show the form for creating a new component
     */
    public function create()
    {
        return view('admin.components.create');
    }

    /**
     * Store a newly created component
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:components',
            'purchase_price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'category' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $harga = $request->harga ?? $request->purchase_price ?? 0;
        $qty = $request->qty ?? $request->quantity ?? 0;
        $satuan = $request->satuan ?? $request->unit ?? 'ml';
        $hargaPerMl = $qty > 0 ? $harga / $qty : 0;

        Component::create([
            'name' => $request->name,
            'harga' => $harga,
            'qty' => $qty,
            'satuan' => $satuan,
            'harga_per_ml' => $hargaPerMl,
            'harga_per_satuan' => $hargaPerMl,
            'kategori' => $request->kategori ?? $request->category
        ]);

        return redirect()->route('admin.components')
            ->with('success', 'Komponen berhasil ditambahkan');
    }

    /**
     * Display the specified component
     */
    public function show(Component $component)
    {
        return response()->json($component);
    }

    /**
     * Show the form for editing the specified component
     */
    public function edit(Component $component)
    {
        return view('admin.components.edit', compact('component'));
    }

    /**
     * Update the specified component
     */
    public function update(Request $request, Component $component)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:components,name,' . $component->id,
            'harga' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'qty' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|numeric|min:0',
            'satuan' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:50',
            'kategori' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $harga = $request->harga ?? $request->purchase_price ?? $component->harga;
        $qty = $request->qty ?? $request->quantity ?? $component->qty;
        $satuan = $request->satuan ?? $request->unit ?? $component->satuan;
        $hargaPerMl = $qty > 0 ? $harga / $qty : 0;

        $component->update([
            'name' => $request->name,
            'harga' => $harga,
            'qty' => $qty,
            'satuan' => $satuan,
            'harga_per_ml' => $hargaPerMl,
            'harga_per_satuan' => $hargaPerMl,
            'kategori' => $request->kategori ?? $request->category
        ]);

        return redirect()->route('admin.components')
            ->with('success', 'Komponen berhasil diperbarui');
    }

    /**
     * Remove the specified component
     */
    public function destroy(Component $component)
    {
        try {
            $component->delete();
            return redirect()->route('admin.components')
                ->with('success', 'Komponen berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.components')
                ->with('error', 'Komponen tidak bisa dihapus karena masih digunakan');
        }
    }

    /**
     * Bulk update components
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'components' => 'required|array',
            'components.*.id' => 'required|exists:components,id',
            'components.*.purchase_price' => 'required|numeric|min:0',
            'components.*.quantity' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach ($request->components as $componentData) {
            $component = Component::find($componentData['id']);
            $pricePerUnit = $componentData['quantity'] > 0 ? $componentData['purchase_price'] / $componentData['quantity'] : 0;
            
            $component->update([
                'purchase_price' => $componentData['purchase_price'],
                'quantity' => $componentData['quantity'],
                'price_per_unit' => $pricePerUnit
            ]);
        }

        return response()->json(['message' => 'Komponen berhasil diperbarui secara bulk']);
    }

    /**
     * Get components by category for AJAX
     */
    public function getByCategory(Request $request)
    {
        $category = $request->get('category');
        $components = Component::when($category, function ($query, $category) {
            return $query->where('category', $category);
        })->get(['id', 'name', 'price_per_unit', 'unit']);

        return response()->json($components);
    }

    /**
     * Import components from array (for seeding)
     */
    public function importComponents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'components' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imported = 0;
        $errors = [];

        foreach ($request->components as $componentData) {
            try {
                $pricePerUnit = $componentData['qty'] > 0 ? $componentData['harga'] / $componentData['qty'] : 0;
                
                Component::updateOrCreate(
                    ['name' => $componentData['name']],
                    [
                        'purchase_price' => $componentData['harga'],
                        'quantity' => $componentData['qty'],
                        'unit' => $componentData['satuan'],
                        'price_per_unit' => $pricePerUnit,
                        'category' => $componentData['category'] ?? null
                    ]
                );
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Error importing {$componentData['name']}: " . $e->getMessage();
            }
        }

        return response()->json([
            'message' => "Berhasil import {$imported} komponen",
            'errors' => $errors
        ]);
    }
}