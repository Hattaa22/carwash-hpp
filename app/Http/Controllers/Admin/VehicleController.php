<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    /**
     * Display a listing of vehicles
     */
    public function index()
    {
        $vehicles = Vehicle::orderBy('jenis_kendaraan')->get();
        return view('admin.vehicles', compact('vehicles'));
    }

    /**
     * Show the form for creating a new vehicle
     */
    public function create()
    {
        return view('admin.vehicles.create');
    }

    /**
     * Store a newly created vehicle
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string|max:10|unique:vehicles',
            'jenis_kendaraan' => 'required|string|max:50',
            'volume_campuran' => 'required|numeric|min:0',
            'harga_member' => 'required|numeric|min:0',
            'harga_non_member' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Vehicle::create([
                'id' => $request->id,
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'volume_campuran' => $request->volume_campuran,
                'harga_member' => $request->harga_member,
                'harga_non_member' => $request->harga_non_member,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jenis kendaraan berhasil ditambahkan'
                ]);
            }

            return redirect()->route('admin.vehicles')
                ->with('success', 'Jenis kendaraan berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }

    /**
     * Display the specified vehicle
     */
    public function show($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        if (request()->expectsJson()) {
            return response()->json($vehicle);
        }
        
        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified vehicle
     */
    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified vehicle
     */
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'id' => 'required|string|max:10|unique:vehicles,id,' . $vehicle->id,
            'jenis_kendaraan' => 'required|string|max:50',
            'volume_campuran' => 'required|numeric|min:0',
            'harga_member' => 'required|numeric|min:0',
            'harga_non_member' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $vehicle->update([
                'id' => $request->id,
                'jenis_kendaraan' => $request->jenis_kendaraan,
                'volume_campuran' => $request->volume_campuran,
                'harga_member' => $request->harga_member,
                'harga_non_member' => $request->harga_non_member,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jenis kendaraan berhasil diperbarui'
                ]);
            }

            return redirect()->route('admin.vehicles')
                ->with('success', 'Jenis kendaraan berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data');
        }
    }

    /**
     * Remove the specified vehicle
     */
    public function destroy($id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);
            $vehicle->delete();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jenis kendaraan berhasil dihapus'
                ]);
            }
            
            return redirect()->route('admin.vehicles')
                ->with('success', 'Jenis kendaraan berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis kendaraan tidak bisa dihapus karena masih digunakan'
                ], 400);
            }
            
            return redirect()->route('admin.vehicles')
                ->with('error', 'Jenis kendaraan tidak bisa dihapus karena masih digunakan');
        }
    }

    /**
     * Get vehicle pricing for AJAX
     */
    public function getPricing(Request $request)
    {
        $vehicleType = $request->get('jenis_kendaraan');
        $vehicle = Vehicle::where('jenis_kendaraan', $vehicleType)->first();

        if (!$vehicle) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }

        return response()->json([
            'volume_campuran' => $vehicle->volume_campuran,
            'harga_member' => $vehicle->harga_member,
            'harga_non_member' => $vehicle->harga_non_member
        ]);
    }

    /**
     * Bulk update vehicle prices
     */
    public function bulkUpdatePrices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicles' => 'required|array',
            'vehicles.*.id' => 'required|exists:vehicles,id',
            'vehicles.*.harga_member' => 'required|numeric|min:0',
            'vehicles.*.harga_non_member' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            foreach ($request->vehicles as $vehicleData) {
                $vehicle = Vehicle::find($vehicleData['id']);
                $vehicle->update([
                    'harga_member' => $vehicleData['harga_member'],
                    'harga_non_member' => $vehicleData['harga_non_member']
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Harga kendaraan berhasil diperbarui secara bulk'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all vehicles for dropdown/select options
     */
    public function getVehicleOptions()
    {
        $vehicles = Vehicle::select('id', 'jenis_kendaraan', 'volume_campuran')
                          ->orderBy('jenis_kendaraan')
                          ->get();
        return response()->json($vehicles);
    }

    /**
     * Import default vehicles data
     */
    public function importDefaultData()
    {
        $defaultVehicles = [
            ['id' => 'S', 'jenis_kendaraan' => 'Small', 'volume_campuran' => 750, 'harga_member' => 55000, 'harga_non_member' => 75000],
            ['id' => 'M', 'jenis_kendaraan' => 'Medium', 'volume_campuran' => 1000, 'harga_member' => 60000, 'harga_non_member' => 80000],
            ['id' => 'L', 'jenis_kendaraan' => 'Large', 'volume_campuran' => 1250, 'harga_member' => 75000, 'harga_non_member' => 90000],
            ['id' => 'XL', 'jenis_kendaraan' => 'Extra Large', 'volume_campuran' => 1500, 'harga_member' => 95000, 'harga_non_member' => 120000],
            ['id' => 'SL', 'jenis_kendaraan' => 'Sport & Luxury', 'volume_campuran' => 1500, 'harga_member' => 120000, 'harga_non_member' => 150000]
        ];

        try {
            $imported = 0;
            foreach ($defaultVehicles as $vehicleData) {
                Vehicle::updateOrCreate(
                    ['id' => $vehicleData['id']],
                    $vehicleData
                );
                $imported++;
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil import {$imported} jenis kendaraan default"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat import data: ' . $e->getMessage()
            ], 500);
        }
    }
}