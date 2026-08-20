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

        $pricePerUnit = $request->quantity > 0 ? $request->purchase_price / $request->quantity : 0;

        Component::create([
            'name' => $request->name,
            'purchase_price' => $request->purchase_price,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'price_per_unit' => $pricePerUnit,
            'category' => $request->category
        ]);

        return redirect()->route('admin.components')
            ->with('success', 'Komponen berhasil ditambahkan');
    }

    /**
     * Display the specified component
     */
    public function show(Component $component)
    {
        return view('admin.components.show', compact('component'));
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

        $pricePerUnit = $request->quantity > 0 ? $request->purchase_price / $request->quantity : 0;

        $component->update([
            'name' => $request->name,
            'purchase_price' => $request->purchase_price,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'price_per_unit' => $pricePerUnit,
            'category' => $request->category
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