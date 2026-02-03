const tblUsuarios = "";
const formulario = document.querySelector('#formulario');
const descripcion = document.querySelector('#descripcion');
const ctaMayor = document.querySelector('#ctaMayor');
const naturaleza = document.querySelector('#naturaleza');
const idCuenta = document.querySelector('#idCuenta');
const codCuenta = document.querySelector('#codCuenta');
const nivel = document.querySelector('#nivel');
const idNaturaleza = document.querySelector('#idNaturaleza');






//elementos para mostor errore
const errordescripcion = document.querySelector('#errordescripcion');
const errorcodPuntoVenta = document.querySelector('#errorcodPuntoVenta');

const btnAccion = document.querySelector('#btnAccion');
const btnNuevo = document.querySelector('#btnNuevo');

document.addEventListener('DOMContentLoaded', function () {
	if(idCuenta.value!=""){
		codCuenta.disabled=true;
		nivel.disabled=true;
		naturaleza.disabled=true;
		btnAccion.textContent = 'Actualizar';
	}

    //Limpiar Campos
    btnNuevo.addEventListener('click', function () {
        location.href =base_url+"/catalogoCuentas/verDetalle/"+naturaleza.value;
    })
    //registrar usuarios
    formulario.addEventListener('submit', function (e) {
		debugger;
        e.preventDefault();
        limpiarCampos();
        if (descripcion.value == '') {
            errordescripcion.textContent = 'LA DESCRIPCION ES REQUERIDA';
        } else {
            const url = base_url + 'catalogoCuentas/registrar';
			insertarRegistros(url, this, tblUsuarios, btnAccion, false);
			setTimeout(() => {
				location.href =base_url+"/catalogoCuentas/verDetalle/"+idNaturaleza.value;
				}, 2000);
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

function limpiarCampos() {
    errordescripcion.textContent = '';
    errorcodPuntoVenta.textContent = '';

}
