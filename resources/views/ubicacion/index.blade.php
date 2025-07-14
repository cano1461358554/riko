@extends('layouts.app')

@section('template_title')
    {{ __('Ubicaciones') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-map-marker-alt"></i> {{ __('Gestión de Ubicaciones') }}</h3>
            </div>

            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success text-center">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <form method="GET" action="{{ route('ubicacions.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="ubicacion" class="form-control" placeholder="Buscar ubicación..." value="{{ request('ubicacion') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('ubicacions.index') }}" class="btn btn-warning">
                                <i class="fa fa-sync-alt"></i> Limpiar
                            </a>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('ubicacions.create') }}" class="btn text-white" style="background-color: #A4D65E;">
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
                            <th>Ubicación</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $ubicacionBuscada = request('ubicacion');
                            $i = ($ubicacions->currentPage() - 1) * $ubicacions->perPage() + 1; // Inicializar $i
                        @endphp

                        @forelse ($ubicacions as $ubicacion)
                            @php
                                $coincide = $ubicacionBuscada && stripos($ubicacion->ubicacion, $ubicacionBuscada) !== false;
                            @endphp
                            <tr class="{{ $coincide ? 'table-success' : '' }}">
                                <td class="text-center">{{ $i++ }}</td>
                                <td><strong>{{ $ubicacion->ubicacion }}</strong></td>
                                <td class="text-center">
{{--                                    <a href="{{ route('ubicacions.show', $ubicacion->id) }}" class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fa fa-eye"></i> Ver--}}
{{--                                    </a>--}}
                                    <a href="{{ route('ubicacions.edit', $ubicacion->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('ubicacions.destroy', $ubicacion->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar ubicación?')">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-danger"><strong>No hay ubicaciones registradas.</strong></td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {!! $ubicacions->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
