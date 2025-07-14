<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stock;
use App\Models\Material;
use App\Models\Ingreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class IngresoController extends Controller
{
    public function index(Request $request): View
    {
        $ingresos = Ingreso::with(['material', 'user'])->paginate();
        return view('ingreso.index', compact('ingresos'))
            ->with('i', ($request->input('page', 1) - 1) * $ingresos->perPage());
    }

    public function create(): View
    {
        $materials = Material::all();
        $users = User::all();
        return view('ingreso.create', compact('materials', 'users'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'cantidad_ingresada' => 'required|numeric|min:1',
                'fecha' => 'required|date',
                'material_id' => 'required|exists:materials,id',
                'user_id' => 'required|exists:users,id',
            ]);

            $material = Material::findOrFail($request->material_id);

            // Buscar el stock actual
            $stock = Stock::where('material_id', $request->material_id)->first();

            if ($stock) {
                $stock->cantidad += $request->cantidad_ingresada;
                $stock->save();
            } else {
                Stock::create([
                    'material_id' => $request->material_id,
                    'cantidad' => $request->cantidad_ingresada,
                    'almacen_id' => $material->almacen_id, // <- aquí tomamos el almacén desde el material
                ]);
            }

            Ingreso::create([
                'cantidad_ingresada' => $request->cantidad_ingresada,
                'fecha' => $request->fecha,
                'material_id' => $request->material_id,
                'user_id' => $request->user_id,
            ]);

            return redirect()->route('ingresos.index')->with('success', 'Ingreso registrado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al registrar el ingreso: ' . $e->getMessage());
        }
    }

    public function edit($id): View
    {
        $ingreso = Ingreso::findOrFail($id);
        $materials = Material::all();
        $users = User::all();
        return view('ingreso.edit', compact('ingreso', 'materials', 'users'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'material_id' => 'required|exists:materials,id',
                'cantidad_ingresada' => 'required|numeric|min:1',
                'fecha' => 'required|date',
                'user_id' => 'required|exists:users,id',
            ]);

            $ingreso = Ingreso::findOrFail($id);

            // Obtener la diferencia de cantidad
            $diferenciaCantidad = $validatedData['cantidad_ingresada'] - $ingreso->cantidad_ingresada;

            // Actualizar el stock si el material no ha cambiado
            if ($ingreso->material_id == $validatedData['material_id']) {
                $stock = Stock::where('material_id', $ingreso->material_id)->first();

                if ($stock) {
                    $stock->cantidad += $diferenciaCantidad;
                    $stock->save();
                } else {
                    // Si no hay stock pero el material es el mismo, crear nuevo registro
                    $material = Material::findOrFail($validatedData['material_id']);
                    Stock::create([
                        'material_id' => $validatedData['material_id'],
                        'cantidad' => $validatedData['cantidad_ingresada'],
                        'almacen_id' => $material->almacen_id,
                    ]);
                }
            } else {
                // Si cambió el material, revertir el stock anterior y actualizar el nuevo

                // Revertir stock del material anterior
                $stockAnterior = Stock::where('material_id', $ingreso->material_id)->first();
                if ($stockAnterior) {
                    $stockAnterior->cantidad -= $ingreso->cantidad_ingresada;
                    $stockAnterior->save();
                }

                // Actualizar stock del nuevo material
                $stockNuevo = Stock::where('material_id', $validatedData['material_id'])->first();
                if ($stockNuevo) {
                    $stockNuevo->cantidad += $validatedData['cantidad_ingresada'];
                    $stockNuevo->save();
                } else {
                    $material = Material::findOrFail($validatedData['material_id']);
                    Stock::create([
                        'material_id' => $validatedData['material_id'],
                        'cantidad' => $validatedData['cantidad_ingresada'],
                        'almacen_id' => $material->almacen_id,
                    ]);
                }
            }

            // Actualizar el registro de ingreso
            $ingreso->update($validatedData);

            DB::commit();
            return redirect()->route('ingresos.index')
                ->with('success', 'Ingreso actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el ingreso: ' . $e->getMessage());
        }
    }

    public function destroy($id): RedirectResponse
    {
        $ingreso = Ingreso::findOrFail($id);
        $ingreso->delete();

        return redirect()->route('ingresos.index')
            ->with('success', 'Ingreso eliminado correctamente.');
    }

    public function show($id): View
    {
        $ingreso = Ingreso::with(['material', 'user'])->findOrFail($id);
        return view('ingreso.show', compact('ingreso'));
    }
}
