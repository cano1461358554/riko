<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MovimientoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $movimientos = Movimiento::paginate();

        return view('movimiento.index', compact('movimientos'))
            ->with('i', ($request->input('page', 1) - 1) * $movimientos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $movimiento = new Movimiento();

        return view('movimiento.create', compact('movimiento'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MovimientoRequest $request): RedirectResponse
    {
        Movimiento::create($request->validated());

        return Redirect::route('movimientos.index')
            ->with('success', 'Movimiento created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $movimiento = Movimiento::find($id);

        return view('movimiento.show', compact('movimiento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $movimiento = Movimiento::find($id);

        return view('movimiento.edit', compact('movimiento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MovimientoRequest $request, Movimiento $movimiento): RedirectResponse
    {
        $movimiento->update($request->validated());

        return Redirect::route('movimientos.index')
            ->with('success', 'Movimiento updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Movimiento::find($id)->delete();

        return Redirect::route('movimientos.index')
            ->with('success', 'Movimiento deleted successfully');
    }
}
