<?php

namespace App\Http\Controllers;

use App\Models\Ubicacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UbicacionRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class UbicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $ubicacions = Ubicacion::paginate();

        return view('ubicacion.index', compact('ubicacions'))
            ->with('i', ($request->input('page', 1) - 1) * $ubicacions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $ubicacion = new Ubicacion();

        return view('ubicacion.create', compact('ubicacion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion_id' => 'required|exists:ubicacions,id',
        ]);

        Almacen::create([
            'nombre' => $request->nombre,
            'ubicacion_id' => $request->ubicacion_id,
        ]);

        return redirect()->route('almacens.index')->with('success', 'Almacén creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $ubicacion = Ubicacion::find($id);

        return view('ubicacion.show', compact('ubicacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $ubicacion = Ubicacion::find($id);

        return view('ubicacion.edit', compact('ubicacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UbicacionRequest $request, Ubicacion $ubicacion): RedirectResponse
    {
        $ubicacion->update($request->validated());

        return Redirect::route('ubicacions.index')
            ->with('success', 'Ubicacion updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Ubicacion::find($id)->delete();

        return Redirect::route('ubicacions.index')
            ->with('success', 'Ubicacion deleted successfully');
    }
}
