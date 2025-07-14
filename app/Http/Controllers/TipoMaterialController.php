<?php

namespace App\Http\Controllers;

use App\Models\TipoMaterial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TipoMaterialRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TipoMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tipoMaterials = TipoMaterial::paginate();

        return view('tipo-material.index', compact('tipoMaterials'))
            ->with('i', ($request->input('page', 1) - 1) * $tipoMaterials->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tipoMaterial = new TipoMaterial();

        return view('tipo-material.create', compact('tipoMaterial'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipoMaterialRequest $request): RedirectResponse
    {
        TipoMaterial::create($request->validated());

        return Redirect::route('tipo-materials.index')
            ->with('success', 'TipoMaterial created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $tipoMaterial = TipoMaterial::find($id);

        return view('tipo-material.show', compact('tipoMaterial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tipoMaterial = TipoMaterial::find($id);

        return view('tipo-material.edit', compact('tipoMaterial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TipoMaterialRequest $request, TipoMaterial $tipoMaterial): RedirectResponse
    {
        $tipoMaterial->update($request->validated());

        return Redirect::route('tipo-materials.index')
            ->with('success', 'TipoMaterial updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        TipoMaterial::find($id)->delete();

        return Redirect::route('tipo-materials.index')
            ->with('success', 'TipoMaterial deleted successfully');
    }
}
