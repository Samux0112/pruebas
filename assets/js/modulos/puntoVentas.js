let tblUsuarios;
const formulario = document.querySelector('#formulario');
const descripcion = document.querySelector('#descripcion');
const codPuntoVenta = document.querySelector('#codPuntoVenta');
const id = document.querySelector('#id');

//elementos para mostor errore
const errordescripcion = document.querySelector('#errordescripcion');
const errorcodPuntoVenta = document.querySelector('#errorcodPuntoVenta');

const btnAccion = document.querySelector('#btnAccion');
const btnNuevo = document.querySelector('#btnNuevo');

document.addEventListener('DOMContentLoaded', function () {
	debugger;
    //cargar datos con el plugin datatables
    tblUsuarios = $('#tblUsuarios').DataTable({
        ajax: {
            url: base_url + 'puntoVentas/listar',
            dataSrc: ''
        },
        columns: [
            { data: 'descripcion' },
            { data: 'codPuntoVentaMH' },
            { data: 'acciones' }
        ],
        language: {
            url: base_url + 'assets/js/espanol.json'
        },
        dom,
        buttons,
        responsive: true,
        order: [[0, 'asc']],
    });
    //Limpiar Campos
    btnNuevo.addEventListener('click', function () {
        id.value = '';
        btnAccion.textContent = 'Registrar';
        clave.removeAttribute('readonly');
        formulario.reset();
        descripcion.focus();
        limpiarCampos();
    })
    //registrar usuarios
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();
        limpiarCampos();
        if (errordescripcion.value == '') {
            errordescripcion.textContent = 'LA DESCRIPCION ES REQUERIDA';
        } else if (errorcodPuntoVenta.value == '') {
            errorcodPuntoVenta.textContent = 'LA DESCRIPCION ES REQUERIDA';
        } else {
            const url = base_url + 'puntoVentas/registrar';
			insertarRegistros(url, this, tblUsuarios, btnAccion, false);
        }
    })

})
//function para elimnar usuario
function eliminarPuntoVenta(idPuntoVenta) {
    const url = base_url + 'puntoVentas/eliminar/' + idPuntoVenta;
    eliminarRegistros(url, tblUsuarios);
}
// function para recuperar los datos
function editarPuntoVenta(idPuntoVenta) {
    limpiarCampos();
    const url = base_url + 'puntoVentas/editar/' + idPuntoVenta;
    //hacer una instancia del objeto XMLHttpRequest 
    const http = new XMLHttpRequest();
    //Abrir una Conexion - POST - GET
    http.open('GET', url, true);
    //Enviar Datos
    http.send();
    //verificar estados
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            const res = JSON.parse(this.responseText);
            id.value = res.id_puntoVenta;
            descripcion.value = res.descripcion;
            codPuntoVenta.value = res.codPuntoVentaMH;
			btnAccion.textContent = 'Actualizar';
            firstTab.show()
        }
    }
}
function limpiarCampos() {
    errordescripcion.textContent = '';
    errorcodPuntoVenta.textContent = '';

}