// JS para la vista cotización de requisiciones
// Calcula el subtotal en la tabla de productos

document.addEventListener('DOMContentLoaded', function() {
    function calcularSubtotal(row) {
        const cantidad = parseFloat(row.querySelector('.input-cantidad').value) || 0;
        const precio = parseFloat(row.querySelector('.input-precio').value) || 0;
        const descuento = parseFloat(row.querySelector('.input-descuento').value) || 0;
        let subtotal = (cantidad * precio) - descuento;
        if (subtotal < 0) subtotal = 0;
        row.querySelector('.input-subtotal').value = subtotal.toFixed(2);
    }

    function calcularTotalCotizacion() {
        let total = 0;
        document.querySelectorAll('.input-subtotal').forEach(function(input) {
            total += parseFloat(input.value) || 0;
        });
        // Sumar IVA al total
        var ivaInput = document.getElementById('iva');
        let iva = ivaInput ? parseFloat(ivaInput.value) || 0 : 0;
        let totalConIva = total + iva;
        document.getElementById('monto').value = totalConIva.toFixed(2);
    }

    function calcularIVA() {
        let suma = 0;
        document.querySelectorAll('.input-subtotal').forEach(function(input) {
            suma += parseFloat(input.value) || 0;
        });
        var ivaInput = document.getElementById('iva');
        if (ivaInput) {
            ivaInput.value = (suma * 0.13).toFixed(2);
        }
    }

    function actualizarTotales() {
        document.querySelectorAll('tr').forEach(function(row) {
            if (row.querySelector('.input-subtotal')) {
                calcularSubtotal(row);
            }
        });
        calcularTotalCotizacion();
        calcularIVA();
    }

    document.querySelectorAll('.input-precio, .input-descuento').forEach(function(input) {
        input.addEventListener('input', function() {
            const row = input.closest('tr');
            calcularSubtotal(row);
            calcularTotalCotizacion();
            calcularIVA();
        });
    });

    // Inicializar totales e IVA al cargar
    actualizarTotales();
    calcularIVA();

    // Guardar cotización por AJAX
    const btnGuardar = document.getElementById('btnGuardarCotizacion');
        btnGuardar.addEventListener('click', function () {
            let valid = true;
            let msg = '';
            // Validar proveedor y capturar id usando datalist
            const proveedorInput = document.getElementById('proveedor');
            const proveedor = proveedorInput.value.trim();
            let proveedor_id = '';
            if (window.proveedoresData && proveedor) {
                const found = window.proveedoresData.find(function(p) {
                    return p.nombre.toLowerCase() === proveedor.toLowerCase();
                });
                if (found) {
                    proveedor_id = found.id;
                } else {
                    proveedor_id = '';
                }
            }
            if (!proveedor) {
                valid = false;
                msg += 'El proveedor es obligatorio.\n';
            } else if (!proveedor_id) {
                valid = false;
                msg += 'Debe seleccionar un proveedor válido de la lista.\n';
            }
            // Validar productos
            const productos = [];
            document.querySelectorAll('tbody tr').forEach(function(row, idx) {
                if (row.querySelector('.input-precio')) {
                    // Captura el id del producto desde la celda correspondiente (asumiendo que la primera celda es el id)
                    let id = null;
                    if (row.cells[0]) {
                        id = row.cells[0].textContent.trim();
                    }
                    const nombre = row.cells[1].textContent.trim();
                    const cantidad = parseFloat(row.querySelector('.input-cantidad').value) || 0;
                    const descripcion = row.cells[3].textContent.trim();
                    const precio = parseFloat(row.querySelector('.input-precio').value) || 0;
                    const descuento = parseFloat(row.querySelector('.input-descuento').value) || 0;
                    const subtotal = parseFloat(row.querySelector('.input-subtotal').value) || 0;
                    if (precio <= 0) {
                        valid = false;
                        msg += `El precio unitario del producto ${idx+1} debe ser mayor a 0.\n`;
                    }
                    productos.push({id, nombre, cantidad, descripcion, precio, descuento, subtotal});
                }
            });
            // Validar monto
            const monto = parseFloat(document.getElementById('monto').value) || 0;
            if (monto <= 0) {
                valid = false;
                msg += 'El monto de cotización debe ser mayor a 0.\n';
            }
            if (!valid) {
                alertaPersonalizada('warning', msg);
                return;
            }
            btnGuardar.disabled = true;
            // Preparar datos
            const data = {
                requisicion_id: document.getElementById('requisicion_id').value,
                proveedor,
                proveedor_id,
                monto,
                detalle: document.getElementById('detalle').value,
                productos
            };
            // Enviar por AJAX
            const url = base_url + 'requisiciones/guardarCotizacion';
            const http = new XMLHttpRequest();
            http.open('POST', url, true);
            http.setRequestHeader('Content-Type', 'application/json');
            http.onreadystatechange = function () {
                if (this.readyState == 4) {
                    btnGuardar.disabled = false;
                    if (this.status == 200) {
                        try {
                            const res = JSON.parse(this.responseText);
                            alertaPersonalizada(res.success ? 'success' : 'error', res.msg);
                            if (res.success) {
                                setTimeout(function() {
                                    window.location.href = base_url + 'requisiciones/detalle/' + data.requisicion_id;
                                }, 1500);
                            }
                        } catch (e) {
                            alertaPersonalizada('error', 'Error al procesar respuesta.');
                        }
                    } else {
                        alertaPersonalizada('error', 'Error al guardar cotización.');
                    }
                }
            };
            http.send(JSON.stringify(data));
        });
    
});
