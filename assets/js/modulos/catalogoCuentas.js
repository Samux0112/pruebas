let tblUsuarios;
const formulario = document.querySelector('#formulario');
const descripcion = document.querySelector('#descripcion');
const codCuenta = document.querySelector('#codCuenta');
const id = document.querySelector('#id');

//elementos para mostor errore
const errorCodigoCuenta = document.querySelector('#errorCodigoCuenta');
const errorDescripcion = document.querySelector('#errorDescripcion');

const btnAccion = document.querySelector('#btnAccion');
const btnNuevo = document.querySelector('#btnNuevo');

document.addEventListener('DOMContentLoaded', function () {
	debugger;
    //cargar datos con el plugin datatables
    tblUsuarios = $('#tblUsuarios').DataTable({
        ajax: {
            url: base_url + 'catalogoCuentas/listar',
            dataSrc: ''
        },
        columns: [
            { data: 'codigo_cuenta' },
            { data: 'nombre' },
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
        formulario.reset();
        descripcion.focus();
        limpiarCampos();
    })
    //registrar usuarios
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();
        if (codCuenta.value == '') {
            errorCodigoCuenta.textContent = 'EL CODIGO DE CUENTA ES REQUERIDO';
        } else if (descripcion.value == '') {
            errorDescripcion.textContent = 'LA DESCRIPCION ES REQUERIDA';
        } else {
            const url = base_url + 'catalogoCuentas/registrarPadre';
			insertarRegistros(url, this, tblUsuarios, btnAccion, false);
        }
    })

})
//function para elimnar usuario
function eliminarCuentaContable(id) {
    const url = base_url + 'catalogoCuentas/eliminarPadre/' + id;
    eliminarRegistros(url, tblUsuarios);
}

function editarCuenta(idCuenta) {
    limpiarCampos();
    const url = base_url + 'catalogoCuentas/editar/' + idCuenta;
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
            descripcion.value = res.nombre;
            codCuenta.value = res.codigo_cuenta;
			btnAccion.textContent = 'Actualizar';
            firstTab.show()
        }
    }
}


// function para recuperar los datos
function verCuentas(id) {
   const ruta = base_url + 'catalogoCuentas/verDetalle/' + id;
  window.open(ruta, '_self');	
}

function limpiarCampos() {
    errorCodigoCuenta.textContent = '';
    errorDescripcion.textContent = '';
	descripcion.value ='';
	codCuenta.value='';

}