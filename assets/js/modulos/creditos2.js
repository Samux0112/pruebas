let tblCreditos2, tblAbonos2;
const idCredito2 = document.querySelector('#idCredito2');
const cliente2 = document.querySelector('#buscarCliente2');
const telefonoCliente2 = document.querySelector('#telefonoCliente2');
const direccionCliente2 = document.querySelector('#direccionCliente2');
const abonado2 = document.querySelector('#abonado2');
const restante = document.querySelector('#restante');
const fecha = document.querySelector('#fecha');
const monto_total = document.querySelector('#monto_total');
const monto_abonar2 = document.querySelector('#monto_abonar2');
const id_venta2 = document.querySelector('#id_venta2');
const btnAccion = document.querySelector('#btnAccion');

const nuevoAbono2 = document.querySelector('#nuevoAbono2');
const modalAbono2 = new bootstrap.Modal('#modalAbono2');
const errorCliente2 = document.querySelector('#errorCliente2');

//para filtro por rango de fechas
const desde = document.querySelector('#desde');
const hasta = document.querySelector('#hasta');


document.addEventListener('DOMContentLoaded', function(){
    //cargar datos con el plugin datatables
    tblCreditos2 = $('#tblCreditos2').DataTable({
        ajax: {
            url: base_url + 'creditos2/listar',
            dataSrc: ''
        },
        columns: [
            { data: 'fecha' },
            { data: 'monto' },            
            { data: 'nombre' },
            { data: 'restante' },
            { data: 'abonado2' },
            { data: 'venta2' },
            { data: 'estado' },
            { data: 'acciones' },
        ],
        language: {
            url: base_url + 'assets/js/espanol.json'
        },
        dom,
        buttons,
        responsive: true,
        order: [[5, 'desc']],
    });

    //autocomplete clientes2
    $("#buscarCliente2").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + 'creditos2/buscar',
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function (data) {
                    response(data);
                    if (data.length > 0) {
                        errorCliente2.textContent = '';
                    } else {
                        errorCliente2.textContent = 'NO HAY CLIENTE2 CON ESE NOMBRE';
                    }
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            telefonoCliente2.value = ui.item.telefono;
            direccionCliente2.innerHTML = ui.item.direccion;
            idCredito.value = ui.item.id;

            abonado2.value = ui.item.abonado2;
            restante.value = ui.item.restante;
            monto_total.value = ui.item.monto;
            fecha.value = ui.item.fecha;
            id_venta2.value = ui.item.id_venta2;

            monto_abonar2.focus();
        }
    });

    //levantar modal para agregar abono
    nuevoAbono2.addEventListener('click', function(){
        idCredito.value = '';
        telefonoCliente2.value = '';
        cliente2.value = '';
        direccionCliente2.innerHTML = '';
        abonado2.value = '';
        restante.value = '';
        monto_total.value = '';
        fecha.value = '';
        monto_abonar2.value = '';
        id_venta2.value = '';
        modalAbono2.show();
    })

    btnAccion.addEventListener('click', function(){
        if (monto_abonar2.value == '') {
            alertaPersonalizada('warning', 'INGRESE EL MONTO');
        }else if(idCredito.value == '' && cliente2.value == '' && telefonoCliente2.value == ''){
            alertaPersonalizada('warning', 'BUSCA Y SELECCIONA CLIENTE2');
        }else if(parseFloat(restante.value) < parseFloat(monto_abonar2.value)){
            alertaPersonalizada('warning', 'INGRESE MENOR A RESTANTE');
        }else{
            document.getElementById('btnAccion').disabled = true;
            const url = base_url + 'creditos2/registrarAbono2';
            //hacer una instancia del objeto XMLHttpRequest 
            const http = new XMLHttpRequest();
            //Abrir una Conexion - POST - GET
            http.open('POST', url, true);
            //Enviar Datos
            http.send(JSON.stringify({
                idCredito2 : idCredito.value,
                monto_abonar2 : monto_abonar2.value,
            }));
            //verificar estados
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    alertaPersonalizada(res.type, res.msg);
                    if (res.type == 'success') {
                        modalAbono2.hide();
                        tblCreditos2.ajax.reload();
                        setTimeout(() => {
                            const ruta = base_url + 'creditos2/reporte/' + idCredito.value;
                            window.open(ruta, '_blank');
                        }, 2000);
                    }
                }
            }
        }
    })

    //cargar datos con el plugin datatables
    tblAbonos2 = $('#tblAbonos2').DataTable({
        ajax: {
            url: base_url + 'creditos2/listarAbonos2',
            dataSrc: ''
        },
        columns: [
            { data: 'fecha' },
            { data: 'abono2' },
            { data: 'credito2' }
        ],
        language: {
            url: base_url + 'assets/js/espanol.json'
        },
        dom,
        buttons,
        responsive: true,
        order: [[0, 'asc']],
    });

    //filtro rango de fechas
    desde.addEventListener('change', function () {
        tblCreditos2.draw();
    })
    hasta.addEventListener('change', function () {
        tblCreditos2.draw();
    })

    $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            var FilterStart = desde.value;
            var FilterEnd = hasta.value;
            var DataTableStart = data[0].trim();
            var DataTableEnd = data[0].trim();
            if (FilterStart == '' || FilterEnd == '') {
                return true;
            }
            if (DataTableStart >= FilterStart && DataTableEnd <= FilterEnd) {
                return true;
            } else {
                return false;
            }

        });
})