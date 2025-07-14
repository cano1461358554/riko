

<?php $__env->startSection('template_title'); ?>
    <?php echo e(__('Resguardos')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header text-center text-white" style="background-color: #00723E;">
                <h3 class="mb-0"><i class="fa fa-shield-alt"></i> <?php echo e(__('Gestión de Resguardos')); ?></h3>
            </div>

            <div class="card-body">
                <?php if($message = Session::get('success')): ?>
                    <div class="alert alert-success text-center">
                        <p><?php echo e($message); ?></p>
                    </div>
                <?php endif; ?>

                <form method="GET" action="<?php echo e(route('resguardos.index')); ?>" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="date" name="fecha_resguardo" class="form-control" placeholder="Buscar por fecha de resguardo..." value="<?php echo e(request('fecha_resguardo')); ?>">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn text-white" style="background-color: #4CAF50;">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                            <a href="<?php echo e(route('resguardos.index')); ?>" class="btn btn-warning">
                                <i class="fa fa-sync-alt"></i> Limpiar
                            </a>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="<?php echo e(route('resguardos.create')); ?>" class="btn text-white" style="background-color: #A4D65E;">
                                <i class="fa fa-plus-circle"></i> Crear Nuevo
                            </a>
                        </div>
                    </div>

                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="mostrar_coincidencias" name="mostrar_coincidencias"
                               <?php echo e(request('mostrar_coincidencias') ? 'checked' : ''); ?>

                               onchange="this.form.submit()">
                        <label class="form-check-label" for="mostrar_coincidencias">Mostrar solo coincidencias</label>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-white text-center" style="background-color: #00723E;">
                        <tr>

                            <th>Fecha de Resguardo</th>
                            <th>Estado</th>
                            <th>Préstamo Relacionado</th>



                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $fechaBuscada = request('fecha_resguardo');
                            $i = ($resguardos->currentPage() - 1) * $resguardos->perPage() + 1; // Inicializar $i
                        ?>

                        <?php $__empty_1 = true; $__currentLoopData = $resguardos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resguardo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $coincide = $fechaBuscada && $resguardo->fecha_resguardo == $fechaBuscada;
                            ?>
                            <tr class="<?php echo e($coincide ? 'table-success' : ''); ?>">

                                <td><?php echo e($resguardo->fecha_resguardo); ?></td>
                                <td><?php echo e(ucfirst($resguardo->estado)); ?></td>
                                <td>
                                    <?php if($resguardo->prestamo): ?>
                                        Préstamo #<?php echo e($resguardo->prestamo->id); ?> (<?php echo e($resguardo->prestamo->desc_uso); ?>)
                                    <?php else: ?>
                                        Sin préstamo asociado
                                    <?php endif; ?>






                                </td>
                                <td class="text-center">
                                    <a href="<?php echo e(route('resguardos.edit', $resguardo->id)); ?>" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Editar
                                    </a>
                                    <form action="<?php echo e(route('resguardos.destroy', $resguardo->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar resguardo?')">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-danger"><strong>No hay resguardos registrados.</strong></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    <?php echo $resguardos->withQueryString()->links(); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\cfe\resources\views/resguardo/index.blade.php ENDPATH**/ ?>