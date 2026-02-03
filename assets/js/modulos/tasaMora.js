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
            url: base_url + 'tasaMora/listar',
            dataSrc: ''
        },
        columns: [
            { data: 'tasa' }, 
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
    //registrar usuarios
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();
        limpiarCampos();
        if (errordescripcion.value == '') {
            errordescripcion.textContent = 'LA DESCRIPCION ES REQUERIDA';
        } else {
            const url = base_url + 'tasaMora/registrar';
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
    const url = base_url + 'tasaMora/editar/' + idPuntoVenta;
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
            id.value = res.id;
            descripcion.value = res.tasa;
			btnAccion.textContent = 'Actualizar';
            firstTab.show()
        }
    }
}
function limpiarCampos() {
    errordescripcion.textContent = '';

}