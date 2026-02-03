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
            url: base_url + 'catalogoCuentas/listarDetalle/'+id.value,
            dataSrc: ''
        },
        columns: [
            { data: 'codigo' },
            { data: 'nombre_cuenta' },
			{ data: 'cuenta_mayor' },
			{ data: 'nivel' },
			{ data: 'naturaleza' },
			{ data: 'mayor' }, 
            { data: 'acciones' }
        ],
        language: {
            url: base_url + 'assets/js/espanol.json'
        },
        dom,
        buttons,
        responsive: true,
        order: [[6, 'asc']],
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
function eliminarCuentaContable(id) {
    const url = base_url + 'catalogoCuentas/eliminar/' + id;
    eliminarRegistros(url, tblUsuarios);
}
// function para recuperar los datos
function verCuentas(id) {
   const ruta = base_url + 'catalogoCuentas/verDetalle/' + id;
  window.open(ruta, '_blank');	
}

function agregar(id) {
   const ruta = base_url + 'catalogoCuentas/agregarCuenta/' + id;
  window.open(ruta, '_self');	
}

function editarCuenta(id){
const ruta = base_url + 'catalogoCuentas/editarCuenta/' + id;
  window.open(ruta, '_self');

}

function limpiarCampos() {
    errordescripcion.textContent = '';
    errorcodPuntoVenta.textContent = '';

}