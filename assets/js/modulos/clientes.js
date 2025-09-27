let tblClientes, editorDireccion;

const formulario = document.querySelector('#formulario');
const btnAccion = document.querySelector('#btnAccion');
const btnNuevo = document.querySelector('#btnNuevo');

const identidad = document.querySelector('#identidad');
const num_identidad = document.querySelector('#num_identidad');
const DUI = document.querySelector('#DUI');
const nombre = document.querySelector('#nombre');
const telefono = document.querySelector('#telefono');
const correo = document.querySelector('#correo');
const direccion = document.querySelector('#direccion');
const id = document.querySelector('#id');
const departamento = document.querySelector('#departamentoCliente');
const municipio = document.querySelector('#municipioCliente');
const actividad = document.querySelector('#actividad');

const errorIdentidad = document.querySelector('#errorIdentidad');
const errorNum_identidad = document.querySelector('#errorNum_identidad');
const errorDUI = document.querySelector('#errorDUI');
const errorNombre = document.querySelector('#errorNombre');
const errorTelefono = document.querySelector('#errorTelefono');
const errorDireccion = document.querySelector('#errorDireccion');
const cuentaContable = document.querySelector('#cuentaContable');
const chekExento = document.querySelector('#chekExento');
const contribuyente = document.querySelector('#contribuyente');


const pais = document.querySelector('#pais');
const actividadExportacion = document.querySelector('#actividadExportacion');

document.addEventListener('DOMContentLoaded', function () {
    //cargar datos con el plugin datatables
    tblClientes = $('#tblClientes').DataTable({
        ajax: {
            url: base_url + 'clientes/listar',
            dataSrc: ''
        },
        columns: [
            { data: 'identidad' },
            { data: 'num_identidad' },
            { data: 'DUI' },
            { data: 'nombre' },
            { data: 'telefono' },
            { data: 'correo' },
            { data: 'direccion' },
            { data: 'acciones' },
        ],
        language: {
            url: base_url + 'assets/js/espanol.json'
        },
        dom,
        buttons,
        responsive: true,
        order: [[0, 'asc']],
    });
    //Inicializar un Editor
    ClassicEditor
        .create(document.querySelector('#direccion'), {
            toolbar: {
                items: [
                    'selectAll', '|',
                    'heading', '|',
                    'bold', 'italic',
                    'outdent', 'indent', '|',
                    'undo', 'redo',
                    'alignment', '|',
                    'link', 'blockQuote', 'insertTable', 'mediaEmbed'
                ],
                shouldNotGroupWhenFull: true
            },
        })
        .then(editor => {
            editorDireccion = editor
        })
        .catch(error => {
            console.error(error);
        });

    //limpiar campos
    btnNuevo.addEventListener('click', function () {
        id.value = '';
        btnAccion.textContent = 'Registrar';
        editorDireccion.setData('');
        formulario.reset();
        limpiarCampos();
    })
    //registrar clientes
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();
        limpiarCampos();
		if (DUI.value == '') {
            errorDUI.textContent = 'EL DUI ES REQUERIDO';
        } else if (nombre.value == '') {
            errorNombre.textContent = 'EL NOMBRE ES REQUERIDO';
        } else if (telefono.value == '') {
            errorTelefono.textContent = 'EL TELEFONO ES REQUERIDO';
        } else if (direccion.value == '') {
            errorDireccion.textContent = 'LA DIRECCIÓN ES REQUERIDO';
        } else {
            const url = base_url + 'clientes/registrar';
            insertarRegistros(url, this, tblClientes, btnAccion, false);
            editorDireccion.setData('');
        }

    })
})


function eliminarCliente(idCliente) {
    const url = base_url + 'clientes/eliminar/' + idCliente;
    eliminarRegistros(url, tblClientes);
}

function editarCliente(idCliente) {
    limpiarCampos();
    const url = base_url + 'clientes/editar/' + idCliente;
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
            identidad.value = res.identidad;
            num_identidad.value = res.num_identidad;
            DUI.value = res.DUI;
            nombre.value = res.nombre;
            telefono.value = res.telefono;
            correo.value = res.correo;
			departamento.value = res.departamento;
			municipio.value = res.municipio;
			actividad.value = res.actividad;
			pais.value = res.pais;
			actividadExportacion.value = res.actividadExportacion;
		    res.exento == 'on' ? chekExento.checked = true : chekExento.checked = false;
			contribuyente.value = res.contribuyente;
            editorDireccion.setData(res.direccion);
			if(res.codigo != undefined ){ 
				cuentaContable.value = res.codigo+' | '+res.nombre_cuenta
			}else{
			cuentaContable.value = "";	
			}
			
            btnAccion.textContent = 'Actualizar';
            firstTab.show();
        }
    }
}

function limpiarCampos() {
    errorIdentidad.textContent = '';
    errorNum_identidad.textContent = '';
    errorDUI.textContent = '';
    errorNombre.textContent = '';
    errorTelefono.textContent = '';
    errorCorreo.textContent = '';
    errorDireccion.textContent = '';
}