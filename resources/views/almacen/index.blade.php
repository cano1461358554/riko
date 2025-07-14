@extends('layouts.app')

@section('template_title')
    {{ __('Sistema de Gestión de Almacenes') }}
@endsection

@section('content')
    <div class="container-fluid px-4">
        <div class="card border-0 shadow-lg">
            <!-- Encabezado con gradiente profesional -->
            <div class="card-header bg-gradient-primary text-black py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-warehouse mr-2"></i> Gestión de Almacenes
                    </h3>
                    <div class="badge bg-white text-primary p-2">
                        <i class="fas fa-database mr-1"></i>
                        Total: {{ $almacens->total() }} registros
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i> {{ $message }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Barra de herramientas -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <form method="GET" action="{{ route('almacens.index') }}" class="flex-grow-1 mr-3">
                        <div class="input-group">
                            <input type="text" name="nombre" class="form-control"
                                   placeholder="Buscar por nombre de almacén..."
                                   value="{{ request('nombre') }}"
                                   aria-label="Buscar almacén">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <a href="{{ route('almacens.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-broom"></i> Limpiar
                                </a>
                            </div>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox"
                                   id="mostrar_coincidencias" name="mostrar_coincidencias"
                                   {{ request('mostrar_coincidencias') ? 'checked' : '' }}
                                   onchange="this.form.submit()">
                            <label class="form-check-label" for="mostrar_coincidencias">
                                Mostrar solo coincidencias exactas
                            </label>
                        </div>
                    </form>

                    <a href="{{ route('almacens.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle mr-2"></i> Nuevo Almacén
                    </a>
                </div>

                <!-- Tabla de almacenes -->
                <div class="table-responsive rounded-lg">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th class="pl-4">Nombre</th>
                            <th>Ubicación</th>
                            <th class="text-center" style="width: 180px;">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $nombreBuscado = request('nombre'); @endphp
                        @forelse ($almacens as $almacen)
                            @php
                                $coincide = $nombreBuscado && stripos($almacen->nombre, $nombreBuscado) !== false;
                            @endphp
                            <tr class="{{ $coincide ? 'highlight-row' : '' }}">
                                <td class="align-middle pl-4">
                                    <strong>{{ $almacen->nombre }}</strong>
                                </td>
                                <td class="align-middle">
                                    <span class="badge badge-secondary">
                                        {{ $almacen->ubicacion->ubicacion }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('almacens.edit', $almacen->id) }}"
                                           class="btn btn-outline-warning"
                                           data-toggle="tooltip"
                                           title="Editar almacén">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('almacens.destroy', $almacen->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger"
                                                    onclick="return confirm('¿Confirmas eliminar este almacén?')"
                                                    data-toggle="tooltip"
                                                    title="Eliminar almacén">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-warehouse fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No se encontraron almacenes</h4>
                                        <p class="text-muted">Puedes comenzar agregando un nuevo almacén</p>
                                        <a href="{{ route('almacens.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus"></i> Agregar Almacén
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($almacens->hasPages())
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p class="text-muted">
                                Mostrando {{ $almacens->firstItem() }} a {{ $almacens->lastItem() }}
                                de {{ $almacens->total() }} registros
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                {!! $almacens->withQueryString()->links() !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #00723E 0%, #00A86B 100%);
        }

        .highlight-row {
            background-color: rgba(0, 168, 107, 0.1) !important;
            border-left: 4px solid #00723E;
        }

        .empty-state {
            padding: 2rem 0;
            text-align: center;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-outline-warning {
            color: #ffc107;
            border-color: #ffc107;
        }

        .btn-outline-warning:hover {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }

        .rounded-lg {
            border-radius: 0.5rem;
            overflow: hidden;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
