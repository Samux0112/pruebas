
let tblTipoProducto;
const formulario = document.querySelector('#formulario');
const descripcion = document.querySelector('#descripcion');
const codTipoProducto = document.querySelector('#codTipoProducto');
const id = document.querySelector('#id');
const errordescripcion = document.querySelector('#errordescripcion');
const errorcodTipoProducto = document.querySelector('#errorcodTipoProducto');
const btnAccion = document.querySelector('#btnAccion');
const btnNuevo = document.querySelector('#btnNuevo');

document.addEventListener('DOMContentLoaded', function () {
	tblTipoProducto = $('#tblTipoProducto').DataTable({
		ajax: {
			url: base_url + 'tipoProducto/listar',
			dataSrc: ''
		},
		columns: [
			{ data: 'descripcion' },
			{ data: 'acciones' }
		],
		language: {
			url: base_url + 'assets/js/espanol.json'
		},
		responsive: true,
		order: [[0, 'asc']],
	});

	// Limpiar campos

    btnNuevo.addEventListener('click', function () {
        id.value = '';
        btnAccion.textContent = 'Registrar';
        clave.removeAttribute('readonly');
        formulario.reset();
        descripcion.focus();
        limpiarCampos();
    })

	// Registrar o actualizar tipo de producto
	formulario.addEventListener('submit', function (e) {
		e.preventDefault();
		limpiarCampos();
		if (descripcion.value === '') {
			errordescripcion.textContent = 'La descripción es requerida';
			return;
		}
		const url = base_url + 'tipoProducto/registrar';
		insertarRegistros(url, this, tblTipoProducto, btnAccion, false);
	});
});

function eliminarTipoProducto(idTipoProducto) {
	const url = base_url + 'tipoProducto/eliminar/' + idTipoProducto;
	eliminarRegistros(url, tblTipoProducto);
}

function editarTipoProducto(idTipoProducto) {
	limpiarCampos();
	const url = base_url + 'tipoProducto/editar/' + idTipoProducto;
	const http = new XMLHttpRequest();
	http.open('GET', url, true);
	http.send();
	http.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200) {
			const res = JSON.parse(this.responseText);
			id.value = res.id_tipoProducto;
			descripcion.value = res.descripcion;
			codTipoProducto.value = res.codTipoProductoMH;
			btnAccion.textContent = 'Actualizar';
			firstTab.show()
		}
	}
}

function limpiarCampos() {
	errordescripcion.textContent = '';
	errorcodTipoProducto.textContent = '';
}
