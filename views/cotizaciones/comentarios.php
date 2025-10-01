<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title text-center"><i class="fas fa-comments"></i> Comentarios de Cotización</h5>
        <form id="formComentario" autocomplete="off" class="mb-4">
            <input type="hidden" id="id_cotizacion" name="id_cotizacion" value="<?php echo isset($data['id_cotizacion']) ? $data['id_cotizacion'] : ''; ?>">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="comentario">Comentario</label>
                    <textarea id="comentario" name="comentario" class="form-control" rows="3" placeholder="Escribe tu comentario..."></textarea>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha">Fecha</label>
                    <input type="date" id="fecha" name="fecha" class="form-control">
                </div>
            </div>
            <div class="text-end">
                <button class="btn btn-primary" type="submit">Registrar Comentario</button>
            </div>
        </form>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tblComentarios">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Comentario</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se mostrarán los comentarios registrados -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>assets/js/modulos/comentariosCotizacion.js"></script>
<?php include_once 'views/templates/footer.php'; ?>
