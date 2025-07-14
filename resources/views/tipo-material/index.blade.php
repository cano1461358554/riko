@extends('layouts.app')

@section('template_title')
    {{ __('Tipos de Material') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-cubes"></i> {{ __('Gestión de Tipos de Material') }}</h3>
            </div>

            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success text-center">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <!-- Filtros de búsqueda -->
                <form method="GET" action="{{ route('tipo-materials.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="descripcion" class="form-control" placeholder="Buscar tipo de material..." value="{{ request('descripcion') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('tipo-materials.index') }}" class="btn btn-warning">
                                <i class="fa fa-sync-alt"></i> Limpiar
                            </a>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('tipo-materials.create') }}" class="btn text-white" style="background-color: #A4D65E;">
                                <i class="fa fa-plus-circle"></i> Crear Nuevo
                            </a>
                        </div>
                    </div>

                    <!-- Opción para mostrar solo coincidencias -->
                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="mostrar_coincidencias" name="mostrar_coincidencias"
                               {{ request('mostrar_coincidencias') ? 'checked' : '' }}
                               onchange="this.form.submit()">
                        <label class="form-check-label" for="mostrar_coincidencias">Mostrar solo coincidencias</label>
                    </div>
                </form>

                <!-- Tabla de tipos de material -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-white text-center" style="background-color: #00723E;">
                        <tr>
                            <th>No</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $descripcionBuscada = request('descripcion');
                            $i = ($tipoMaterials->currentPage() - 1) * $tipoMaterials->perPage() + 1; // Inicializar $i
                        @endphp

                        @forelse ($tipoMaterials as $tipoMaterial)
                            @php
                                $coincide = $descripcionBuscada && stripos($tipoMaterial->descripcion, $descripcionBuscada) !== false;
                            @endphp
                            <tr class="{{ $coincide ? 'table-success' : '' }}">
                                <td class="text-center">{{ $i++ }}</td>
                                <td><strong>{{ $tipoMaterial->descripcion }}</strong></td>
                                <td class="text-center">
{{--                                    <a href="{{ route('tipo-materials.show', $tipoMaterial->id) }}" class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fa fa-eye"></i> Ver--}}
{{--                                    </a>--}}
                                    <a href="{{ route('tipo-materials.edit', $tipoMaterial->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('tipo-materials.destroy', $tipoMaterial->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar tipo de material?')">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-danger"><strong>No hay tipos de material registrados.</strong></td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-3">
                    {!! $tipoMaterials->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
