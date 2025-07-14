@extends('layouts.app')

@section('template_title')
    {{ __('Actualización de Ingreso') }} #{{ $ingreso->id }}
@endsection

@section('content')
    <div class="container-fluid px-4">
        <div class="card border-0 shadow-lg">
            <!-- Encabezado con gradiente profesional -->
            <div class="card-header bg-gradient-primary text-black py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-edit mr-2"></i> Actualizar Ingreso
                    </h3>
                    <a href="{{ route('ingresos.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Volver al listado
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h5 class="alert-heading">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Error de validación
                        </h5>
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form method="POST" action="{{ route('ingresos.update', $ingreso->id) }}" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Columna Principal -->
                        <div class="col-lg-8 mx-auto">
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5 class="mb-0 text-primary">
                                        <i class="fas fa-info-circle mr-2"></i> Detalles del Ingreso
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!-- Fecha -->
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-calendar-day mr-1 text-primary"></i> Fecha <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="fecha" id="fecha"
                                               class="form-control @error('fecha') is-invalid @enderror"
                                               value="{{ old('fecha', $ingreso->fecha->format('Y-m-d')) }}" required>
                                        @error('fecha')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Material -->
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-box-open mr-1 text-primary"></i> Material <span class="text-danger">*</span>
                                        </label>
                                        <select name="material_id" id="material_id"
                                                class="form-control @error('material_id') is-invalid @enderror" required>
                                            <option value="">Seleccione un material...</option>
                                            @foreach($materials as $material)
                                                <option value="{{ $material->id }}"
                                                    {{ old('material_id', $ingreso->material_id) == $material->id ? 'selected' : '' }}>
                                                    {{ $material->nombre }} ({{ $material->codigo }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('material_id')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Cantidad -->
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-boxes mr-1 text-primary"></i> Cantidad Ingresada <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="cantidad_ingresada" id="cantidad_ingresada"
                                               class="form-control @error('cantidad_ingresada') is-invalid @enderror"
                                               value="{{ old('cantidad_ingresada', $ingreso->cantidad_ingresada) }}"
                                               min="1" required>
                                        @error('cantidad_ingresada')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>

                                    <!-- Responsable -->
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">
                                            <i class="fas fa-user-tie mr-1 text-primary"></i> Responsable <span class="text-danger">*</span>
                                        </label>
                                        <select name="user_id" id="user_id"
                                                class="form-control @error('user_id') is-invalid @enderror" required>
                                            <option value="">Seleccione un responsable...</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id', $ingreso->user_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->nombre }} {{ $user->apellido }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="form-group mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between">
                            <button type="reset" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-undo mr-2"></i> Restablecer
                            </button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="fas fa-save mr-2"></i> Actualizar Ingreso
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #00723E 0%, #00A86B 100%);
        }

        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .form-control, .form-select {
            border-radius: 0.375rem;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #00723E;
            box-shadow: 0 0 0 0.2rem rgba(0, 114, 62, 0.25);
        }

        .btn-primary {
            background-color: #00723E;
            border-color: #00723E;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #005a32;
            transform: translateY(-2px);
        }

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
        }

        .text-dark {
            color: #343a40 !important;
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Validación del formulario
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
@endsection
