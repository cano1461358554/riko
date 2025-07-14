<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\Ubicacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AlmacenRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $nombre = $request->input('nombre');
        $mostrarCoincidencias = $request->has('mostrar_coincidencias');

        $almacens = Almacen::with(['ubicacion' => function($query) {
            $query->select('id', 'ubicacion'); // Seleccionamos solo id y nombre de ubicación
        }])
            ->when($nombre, function ($query) use ($nombre, $mostrarCoincidencias) {
                if ($mostrarCoincidencias) {
                    return $query->where('nombre', 'like', "%{$nombre}%");
                } else {
                    return $query->orderByRaw("CASE WHEN nombre LIKE ? THEN 1 ELSE 2 END", ["%{$nombre}%"])
                        ->orderBy('nombre');
                }
            })
            ->paginate(10);

        return view('almacen.index', compact('almacens', 'nombre', 'mostrarCoincidencias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $almacen = new Almacen();
        $ubicacions = Ubicacion::select('id', 'ubicacion')->get(); // Obtenemos id y nombre de ubicación

        return view('almacen.create', compact('almacen', 'ubicacions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlmacenRequest $request): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion_id' => 'required|exists:ubicacions,id',
        ]);

        Almacen::create([
            'nombre' => $request->nombre,
            'ubicacion_id' => $request->ubicacion_id,
        ]);

        return Redirect::route('almacens.index')
            ->with('success', 'Almacén creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $almacen = Almacen::with(['ubicacion' => function($query) {
            $query->select('id', 'ubicacion'); // Seleccionamos id y nombre de ubicación
        }])->findOrFail($id);

        return view('almacen.show', compact('almacen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $almacen = Almacen::with('ubicacion')->findOrFail($id);
        $ubicacions = Ubicacion::all(['id', 'ubicacion as nombre']); // Selecciona id y renombra ubicacio a nombre

        return view('almacen.edit', [
            'almacen' => $almacen,
            'ubicacions' => $ubicacions,
            'ubicacionActual' => $almacen->ubicacion ? $almacen->ubicacion->ubicacion : 'No asignada'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlmacenRequest $request, Almacen $almacen): RedirectResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion_id' => 'required|exists:ubicacions,id',
        ]);

        $almacen->update($request->all());

        return Redirect::route('almacens.index')
            ->with('success', 'Almacén actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        Almacen::findOrFail($id)->delete();

        return Redirect::route('almacens.index')
            ->with('success', 'Almacén eliminado correctamente.');
    }
}
