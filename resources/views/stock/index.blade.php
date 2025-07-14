@extends('layouts.app')

@section('template_title')
    {{ __('Gestión de Inventario') }}
@endsection

@section('content')
    <div class="container-fluid px-4">
        <div class="card border-0 shadow-lg">
            <!-- Encabezado con gradiente profesional -->
            <div class="card-header bg-gradient-primary text-black py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-boxes mr-2"></i> Control de Inventario
                    </h3>
                    <div class="badge bg-white text-primary p-2">
                        <i class="fas fa-database mr-1"></i>
                        Total: {{ $stocks->total() }} registros
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
                    <form method="GET" action="{{ route('stocks.index') }}" class="flex-grow-1 mr-3">
                        <div class="input-group">
                            <input type="text" name="material" class="form-control"
                                   placeholder="Buscar por material..."
                                   value="{{ request('material') }}"
                                   aria-label="Buscar material">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary">
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

                    <a href="{{ route('stocks.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle mr-2"></i> Nuevo Registro
                    </a>
                </div>

                <!-- Tabla de stocks -->
                <div class="table-responsive rounded-lg">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-center">Cantidad</th>
                            <th class="redirectable" data-url="{{ route('materials.index') }}">Material</th>
                            <th class="redirectable" data-url="{{ route('almacens.index') }}">Almacén</th>
                            <th class="text-center" style="width: 180px;">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $materialBuscado = request('material'); @endphp
                        @forelse ($stocks as $stock)
                            @php
                                $coincide = $materialBuscado && stripos($stock->material->nombre, $materialBuscado) !== false;
                            @endphp
                            <tr class="{{ $coincide ? 'highlight-row' : '' }}">
                                <td class="text-center align-middle">
                                    <span class="badge badge-pill badge-primary">
                                        {{ $stock->cantidad }}
                                    </span>
                                </td>
{{--                                <td class="redirectable align-middle" data-url="{{ route('materials.index') }}">--}}
{{--                                    <strong>{{ $stock->material->nombre }}</strong>--}}
{{--                                    <small class="d-block text-muted">{{ $stock->material->codigo }}</small>--}}
{{--                                </td>--}}
                                <td class="redirectable align-middle" data-url="{{ route('materials.index') }}">
                                    @if($stock->material)
                                        <strong>{{ $stock->material->nombre }}</strong>
                                        <small class="d-block text-muted">{{ $stock->material->codigo }}</small>
                                    @else
                                        <strong class="text-danger">Material eliminado</strong>
                                        <small class="d-block text-muted">Código no disponible</small>
                                    @endif
                                </td>
                                <td class="redirectable align-middle" data-url="{{ route('almacens.index') }}">
                                    {{ $stock->almacen->nombre }}
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('stocks.edit', $stock->id) }}"
                                           class="btn btn-outline-warning"
                                           data-toggle="tooltip"
                                           title="Editar stock">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('stocks.destroy', $stock->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger"
                                                    onclick="return confirm('¿Confirmas eliminar este registro?')"
                                                    data-toggle="tooltip"
                                                    title="Eliminar stock">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No se encontraron registros</h4>
                                        <p class="text-muted">Puedes comenzar agregando un nuevo stock</p>
                                        <a href="{{ route('stocks.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus"></i> Agregar Stock
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($stocks->hasPages())
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p class="text-muted">
                                Mostrando {{ $stocks->firstItem() }} a {{ $stocks->lastItem() }}
                                de {{ $stocks->total() }} registros
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                {!! $stocks->withQueryString()->links() !!}
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
            vertical-align: middle;
        }

        .table td {
            vertical-align: middle;
        }

        .badge-primary {
            background-color: #00723E;
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

        .redirectable {
            cursor: context-menu;
            transition: background-color 0.2s;
        }

        .redirectable:hover {
            background-color: rgba(0, 114, 62, 0.05);
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
            // Inicializar tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Configurar celdas redireccionables
            $('.redirectable').each(function() {
                const url = $(this).data('url');
                const type = $(this).text().trim().toLowerCase().includes('material') ? 'Materiales' : 'Almacenes';

                $(this).attr('title', 'Clic derecho para ir a ' + type);

                $(this).on('contextmenu', function(e) {
                    e.preventDefault();
                    window.location.href = url;
                });
            });
        });
    </script>
@endsection
