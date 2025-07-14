

<?php $__env->startSection('template_title'); ?>
    <?php echo e(__('Crear Nuevo Registro de Stock')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient-primary text-black">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 font-weight-light">
                                <i class="fas fa-boxes mr-2"></i> <?php echo e(__('Nuevo Registro de Stock')); ?>

                            </h4>
                            <a href="<?php echo e(route('stocks.index')); ?>" class="btn btn-sm btn-light text-primary">
                                <i class="fas fa-arrow-left mr-1"></i> <?php echo e(__('Volver')); ?>

                            </a>
                        </div>
                    </div>

                    <div class="card-body px-5 py-4">
                        <form method="POST" action="<?php echo e(route('stocks.store')); ?>" role="form" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <?php echo csrf_field(); ?>

                            <div class="form-group row mb-4">
                                <label for="cantidad" class="col-md-3 col-form-label text-md-right">
                                    <?php echo e(__('Cantidad')); ?> <span class="text-danger">*</span>
                                </label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-hashtag text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="number"
                                               name="cantidad"
                                               id="cantidad"
                                               class="form-control rounded-right"
                                               required
                                               min="1"
                                               placeholder="Ingrese la cantidad">
                                        <div class="invalid-feedback">
                                            Por favor ingrese una cantidad válida.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="material_id" class="col-md-3 col-form-label text-md-right">
                                    <?php echo e(__('Material')); ?> <span class="text-danger">*</span>
                                </label>
                                <div class="col-md-7">
                                    <select name="material_id"
                                            id="material_id"
                                            class="form-control selectpicker"
                                            data-live-search="true"
                                            title="Seleccione un material"
                                            required>
                                        <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($material->id); ?>" data-tokens="<?php echo e($material->nombre); ?>">
                                                <?php echo e($material->nombre); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione un material.
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="almacen_id" class="col-md-3 col-form-label text-md-right">
                                    <?php echo e(__('Almacén')); ?> <span class="text-danger">*</span>
                                </label>
                                <div class="col-md-7">
                                    <select name="almacen_id"
                                            id="almacen_id"
                                            class="form-control selectpicker"
                                            data-live-search="true"
                                            title="Seleccione un almacén"
                                            required>
                                        <?php $__currentLoopData = $almacens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $almacen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($almacen->id); ?>" data-tokens="<?php echo e($almacen->nombre); ?>">
                                                <?php echo e($almacen->nombre); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione un almacén.
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-7 offset-md-3">
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary btn-lg px-4">
                                            <i class="fas fa-save mr-2"></i> <?php echo e(__('Guardar Registro')); ?>

                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary">
                                            <i class="fas fa-eraser mr-2"></i> <?php echo e(__('Limpiar')); ?>

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        // Ejemplo de validación de formulario con Bootstrap
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cfe\resources\views/stock/create.blade.php ENDPATH**/ ?>