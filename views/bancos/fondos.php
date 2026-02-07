<?php include_once 'views/templates/header.php'; ?>

<style>
.saldo-actual { font-size: 1.2rem; font-weight: bold; }
.saldo-positivo { color: #198754; }
.saldo-negativo { color: #dc3545; }
</style>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0"><i class="fa-solid fa-wallet"></i> Fondos de Chqueras</h5>
            <div class="ms-auto">
                <a href="<?php echo BASE_URL; ?>bancos" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-building-columns"></i> Bancos
                </a>
            </div>
        </div>
        <hr>
        
        <!-- Saldos de Cuentas Bancarias -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fa-solid fa-wallet"></i> Saldos de Cuentas Bancarias</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="tblSaldos">
                                <thead>
                                    <tr>
                                        <th>Banco</th>
                                        <th>No. Cuenta</th>
                                        <th>Cuenta Contable</th>
                                        <th>Saldo Actual</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodySaldos">
                                    <tr>
                                        <td colspan="5" class="text-center">Cargando saldos...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fa-solid fa-money-bill-wave"></i> Registrar Fondo/Chquera</h6>
                    </div>
                    <div class="card-body">
                        <form id="formFondo">
                            <div class="mb-3">
                                <label for="id_banco_fondo">Banco <span class="text-danger">*</span></label>
                                <select id="id_banco_fondo" name="id_banco" class="form-control select2" required>
                                    <option value="">Seleccionar banco...</option>
                                    <?php if(!empty($data['bancos'])): ?>
                                    <?php foreach($data['bancos'] as $banco): ?>
                                    <option value="<?php echo $banco['id']; ?>">
                                        <?php echo $banco['nombre']; ?> - <?php echo $banco['numero_cuenta']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_fondo">Fecha <span class="text-danger">*</span></label>
                                <input type="date" id="fecha_fondo" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="concepto_fondo">Concepto <span class="text-danger">*</span></label>
                                <input type="text" id="concepto_fondo" name="concepto" class="form-control" placeholder="Ej: Depósito inicial chquera" required>
                            </div>
                            <div class="mb-3">
                                <label for="monto_fondo">Monto US$ <span class="text-danger">*</span></label>
                                <input type="number" id="monto_fondo" name="monto" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Registrar Fondo</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fa-solid fa-history"></i> Últimos Movimientos</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-bordered table-sm" id="tblMovimientos">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Banco</th>
                                        <th>Concepto</th>
                                        <th>Monto</th>
                                        <th>Usuario</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyMovimientos">
                                    <tr>
                                        <td colspan="5" class="text-center">Seleccione un banco para ver movimientos</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Inicializar Select2
    $('#id_banco_fondo').select2({ placeholder: 'Seleccionar banco...', allowClear: true });
    
    // Cargar saldos iniciales
    cargarSaldosBancos();
    
    // Evento change del banco
    $('#id_banco_fondo').change(function(){
        let id_banco = $(this).val();
        if (id_banco) {
            cargarMovimientos(id_banco);
        } else {
            $('#tbodyMovimientos').html('<tr><td colspan="5" class="text-center">Seleccione un banco para ver movimientos</td></tr>');
        }
    });
    
    // Submit del formulario fondo
    $('#formFondo').submit(function(e){
        e.preventDefault();
        registrarFondo();
    });
});

function cargarSaldosBancos() {
    fetch(base_url + 'cheques/getSaldosBancos')
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data && data.length > 0) {
                data.forEach(banco => {
                    let saldo = parseFloat(banco.saldo_actual) || 0;
                    let saldoClass = saldo > 0 ? 'saldo-positivo' : (saldo < 0 ? 'saldo-negativo' : '');
                    let saldoText = saldo >= 0 ? '$' + saldo.toFixed(2) : '-$' + Math.abs(saldo).toFixed(2);
                    html += `
                        <tr>
                            <td>${banco.nombre}</td>
                            <td>${banco.numero_cuenta}</td>
                            <td>${banco.cuenta_contable || 'N/A'}</td>
                            <td class="${saldoClass}">US$ ${saldoText}</td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="verMovimientos(${banco.id})">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                html = '<tr><td colspan="5" class="text-center">No hay bancos registrados</td></tr>';
            }
            $('#tbodySaldos').html(html);
        })
        .catch(error => console.error('Error:', error));
}

function verMovimientos(id_banco) {
    $('#id_banco_fondo').val(id_banco).trigger('change');
    $('#tbodyMovimientos').html('<tr><td colspan="5" class="text-center">Cargando...</td></tr>');
    cargarMovimientos(id_banco);
}

function cargarMovimientos(id_banco) {
    fetch(base_url + 'cheques/listarFondos?id_banco=' + id_banco)
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data && data.length > 0) {
                // Ordenar por fecha y id descendente
                data.sort((a, b) => {
                    if (a.fecha === b.fecha) {
                        return b.id - a.id;
                    }
                    return new Date(b.fecha) - new Date(a.fecha);
                });
                
                data.forEach(fondo => {
                    let monto = parseFloat(fondo.monto) || 0;
                    let tipo = fondo.tipo;
                    let montoClass = '';
                    let montoSigno = '';
                    
                    if (tipo === 'cheque') {
                        // Cheque: mostrar como gasto (rojo, negativo)
                        montoClass = 'text-danger';
                        montoSigno = '-';
                    } else {
                        // Depósito: mostrar como ingreso (verde, positivo)
                        montoClass = 'text-success';
                        montoSigno = '+';
                    }
                    
                    let referencia = fondo.referencia ? ` (Chq: ${fondo.referencia})` : '';
                    
                    html += `
                        <tr>
                            <td>${fondo.fecha}</td>
                            <td>${fondo.banco_nombre}</td>
                            <td>${fondo.concepto}${referencia}</td>
                            <td class="${montoClass}">US$ ${montoSigno}${monto.toFixed(2)}</td>
                            <td>${fondo.usuario_nombre}</td>
                        </tr>
                    `;
                });
            } else {
                html = '<tr><td colspan="5" class="text-center">No hay movimientos registrados</td></tr>';
            }
            $('#tbodyMovimientos').html(html);
        })
        .catch(error => console.error('Error:', error));
}

function registrarFondo() {
    let datos = new FormData();
    datos.append('id_banco', $('#id_banco_fondo').val());
    datos.append('fecha', $('#fecha_fondo').val());
    datos.append('concepto', $('#concepto_fondo').val());
    datos.append('monto', $('#monto_fondo').val());
    
    fetch(base_url + 'cheques/registrarFondo', {
        method: 'POST',
        body: datos
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire({ icon: data.type, title: 'Mensaje', text: data.msg, timer: 3000, showConfirmButton: false });
        if (data.type == 'success') {
            $('#formFondo')[0].reset();
            $('#id_banco_fondo').val('').trigger('change');
            cargarSaldosBancos();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<?php include_once 'views/templates/footer.php'; ?>
