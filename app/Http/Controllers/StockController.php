<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Material; // Importar el modelo Material
use App\Models\Almacen;  // Importar el modelo Almacen
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StockRequest;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $stocks = Stock::when($request->material, function ($query, $material) {
            return $query->whereHas('material', function ($q) use ($material) {
                $q->where('nombre', 'like', "%$material%");
            });
        })->paginate(10);

        return view('stock.index', compact('stocks'))
            ->with('i', ($request->input('page', 1) - 1) * $stocks->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $materials = Material::all();
        $almacens = Almacen::all();

        $stock = new Stock();

        return view('stock.create', compact('stock', 'materials', 'almacens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StockRequest $request): RedirectResponse
    {
        Stock::create($request->validated());

        return redirect()->route('stocks.index')
            ->with('success', 'Stock creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $stock = Stock::findOrFail($id);

        return view('stock.show', compact('stock'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $stock = Stock::findOrFail($id);

        $materials = Material::all();
        $almacens = Almacen::all();

        return view('stock.edit', compact('stock', 'materials', 'almacens'));
    }

    /**
     * Update the specified resource in storage.
     */
//    public function update(StockRequest $request, Stock $stock): RedirectResponse
//    {
//        $stock->update($request->validated());
//
//        return redirect()->route('stocks.index')
//            ->with('success', 'Stock actualizado correctamente.');
//    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $stock = Stock::findOrFail($id);

            $request->validate([
                'nombre' => 'required|string|max:255',
                'cantidad' => 'required|numeric|min:0'
            ]);

            // Actualizar el stock
            $stock->update([
                'cantidad' => $request->cantidad
            ]);

            // Actualizar el material asociado (si existe)
            if ($stock->material) {
                $stock->material->update([
                    'nombre' => $request->nombre,
                ]);
            } else {
                // Opcional: Crear nuevo material si no existe
                $material = Material::create([
                    'nombre' => $request->nombre,
                    // otros campos necesarios
                ]);

                $stock->material_id = $material->id;
                $stock->save();
            }

            DB::commit();

            return redirect()->route('stocks.index')
                ->with('success', 'Stock y material actualizados correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        Stock::findOrFail($id)->delete();

        return redirect()->route('stocks.index')
            ->with('success', 'Stock eliminado correctamente.');
    }

}
