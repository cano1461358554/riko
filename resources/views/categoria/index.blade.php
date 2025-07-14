@extends('layouts.app')

@section('template_title')
    {{ __('Categorías') }}
@endsection

@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-tags"></i> {{ __('Gestión de Categorías') }}</h3>
            </div>

            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success text-center">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <form method="GET" action="{{ route('categorias.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="nombre" class="form-control" placeholder="Buscar categoría..." value="{{ request('nombre') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('categorias.index') }}" class="btn btn-warning">
                                <i class="fa fa-sync-alt"></i> Limpiar
                            </a>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('categorias.create') }}" class="btn text-white" style="background-color: #A4D65E;">
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
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $nombreBuscado = request('nombre');
                            $i = ($categorias->currentPage() - 1) * $categorias->perPage() + 1; // Inicializar $i
                        @endphp

                        @forelse ($categorias as $categoria)
                            @php
                                $coincide = $nombreBuscado && stripos($categoria->nombre, $nombreBuscado) !== false;
                            @endphp
                            <tr class="{{ $coincide ? 'table-success' : '' }}">
                                <td class="text-center">{{ $i++ }}</td>
                                <td><strong>{{ $categoria->nombre }}</strong></td>
                                <td>{{ $categoria->descripcion }}</td>
                                <td class="text-center">
{{--                                    <a href="{{ route('categorias.show', $categoria->id) }}" class="btn btn-primary btn-sm">--}}
{{--                                        <i class="fa fa-eye"></i> Ver--}}
{{--                                    </a>--}}
                                    <a href="{{ route('categorias.edit', $categoria->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar categoría?')">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger"><strong>No hay categorías registradas.</strong></td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {!! $categorias->withQueryString()->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
