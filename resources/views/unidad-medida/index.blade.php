@extends('layouts.app')

@section('template_title')
    {{ __('Unidades de Medida') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-balance-scale"></i> {{ __('Gestión de Unidades de Medida') }}</h3>
            </div>

            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success text-center">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <form method="GET" action="{{ route('unidad-medidas.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="descripcion_unidad" class="form-control" placeholder="Buscar unidad de medida..." value="{{ request('descripcion_unidad') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('unidad-medidas.index') }}" class="btn btn-warning">
                                <i class="fa fa-sync-alt"></i> Limpiar
                            </a>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('unidad-medidas.create') }}" class="btn text-white" style="background-color: #A4D65E;">
                                <i class="fa fa-plus-circle"></i> Crear Nueva
                            </a>
                        </div>
                    </div>

                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="mostrar_coincidencias" name="mostrar_coincidencias"
                               {{ request('mostrar_coincidencias') ? 'checked' : '' }}
                               onchange="this.form.submit()">
                        <label class="form-check-label" for="mostrar_coincidencias">Mostrar solo coincidencias</label>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-white text-center" style="background-color: #00723E;">
                        <tr>
                            <th>No</th>
                            <th>Descripción de la Unidad</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $descripcionBuscada = request('descripcion_unidad');
                            $i = ($unidadMedidas->currentPage() - 1) * $unidadMedidas->perPage() + 1; // Inicializar $i
                        @endphp

                        @forelse ($unidadMedidas as $unidadMedida)
                            @php
                                $coincide = $descripcionBuscada && stripos($unidadMedida->descripcion_unidad, $descripcionBuscada) !== false;
                            @endphp
                            <tr class="{{ $coincide ? 'table-success' : '' }}">
                                <td class="text-center">{{ $i++ }}</td>
                                <td><strong>{{ $unidadMedida->descripcion_unidad }}</strong></td>
                                <td class="text-center">
{{--                                    <a href="{{ route('unidad-medidas.show', $unidadMedida->id) }}" class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fa fa-eye"></i> Ver--}}
{{--                                    </a>--}}
                                    <a href="{{ route('unidad-medidas.edit', $unidadMedida->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('unidad-medidas.destroy', $unidadMedida->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar unidad de medida?')">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-danger"><strong>No hay unidades de medida registradas.</strong></td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {!! $unidadMedidas->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
