// Adaptación de la lógica de ventas.js para tipoProducto

const tblTipoProducto = document.querySelector('#tblTipoProducto tbody');
const descripcion = document.querySelector('#descripcion');
const codTipoProducto = document.querySelector('#codTipoProducto');
const btnAccion = document.querySelector('#btnAccion');
const errorDescripcion = document.querySelector('#errordescripcion');
const errorCodTipoProducto = document.querySelector('#errorcodTipoProducto');

// Cargar datos con el plugin datatables
$(document).ready(function () {
	$('#tblTipoProducto').DataTable({
		ajax: {
			url: base_url + 'tipoProducto/listar',
			dataSrc: ''
		},
		columns: [
			{ data: 'descripcion' },
			{ data: 'codTipoProductoMH' },
			{ data: 'acciones' }
		],
		language: {
			url: base_url + 'assets/js/espanol.json'
		},
		responsive: true,
		order: [[0, 'asc']],
	});
});

// Registrar o modificar tipo de producto
if (btnAccion) {
	btnAccion.addEventListener('click', function () {
		if (descripcion.value === '') {
			errorDescripcion.textContent = 'La descripción es requerida';
			return;
		} else {
			errorDescripcion.textContent = '';
		}
		if (codTipoProducto.value === '') {
			errorCodTipoProducto.textContent = 'El código es requerido';
			return;
		} else {
			errorCodTipoProducto.textContent = '';
		}
		// Enviar datos al backend
		$.ajax({
			url: base_url + 'tipoProducto/registrar',
			type: 'POST',
			data: {
				descripcion: descripcion.value,
				codTipoProducto: codTipoProducto.value,
				id: document.querySelector('#id').value
			},
			success: function (response) {
				const res = JSON.parse(response);
				Swal.fire(res.msg, '', res.type);
				if (res.type === 'success') {
					$('#tblTipoProducto').DataTable().ajax.reload();
					document.getElementById('formulario').reset();
				}
			}
		});
	});
}
