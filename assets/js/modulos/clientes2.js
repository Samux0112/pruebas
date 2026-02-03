let tblClientes2, editorDireccion;

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
const departamentoCliente = document.querySelector('#departamentoCliente');
const municipioCliente = document.querySelector('#municipioCliente');
const actividad = document.querySelector('#actividad');
const id = document.querySelector('#id');

const errorIdentidad = document.querySelector('#errorIdentidad');
const errorNum_identidad = document.querySelector('#errorNum_identidad');
const errorDUI = document.querySelector('#errorDUI');
const errorNombre = document.querySelector('#errorNombre');
const errorTelefono = document.querySelector('#errorTelefono');
const errorDireccion = document.querySelector('#errorDireccion');
const errorActividad = document.querySelector('#errorActividad');
const chekExento = document.querySelector('#chekExento');
const contribuyente = document.querySelector('#contribuyente');

const cuentaContable = document.querySelector('#cuentaContable');

document.addEventListener('DOMContentLoaded', function () {
    //cargar datos con el plugin datatables
    tblClientes2 = $('#tblClientes2').DataTable({
        ajax: {
            url: base_url + 'clientes2/listar',
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
    //registrar clientes2
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();
        limpiarCampos();
        if (identidad.value == '') {
            errorIdentidad.textContent = 'LA IDENTIDAD ES REQUERIDO';
        } else if (num_identidad.value == '') {
            errorNum_identidad.textContent = 'LA N° DE IDENTIDAD ES REQUERIDO';
        } else if (nombre.value == '') {
            errorNombre.textContent = 'EL NOMBRE ES REQUERIDO';
        } else if (telefono.value == '') {
            errorTelefono.textContent = 'EL TELEFONO ES REQUERIDO';
        } else if (direccion.value == '') {
            errorDireccion.textContent = 'LA DIRECCIÓN ES REQUERIDO';
        } else {
            const url = base_url + 'clientes2/registrar';
            insertarRegistros(url, this, tblClientes2, btnAccion, false);
            editorDireccion.setData('');
        }

    })
})


function eliminarCliente2(idCliente2) {
    const url = base_url + 'clientes2/eliminar/' + idCliente2;
    eliminarRegistros(url, tblClientes2);
}

function editarCliente2(idCliente2) {
    limpiarCampos();
    const url = base_url + 'clientes2/editar/' + idCliente2;
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
            nombre.value = res.nombre;
            telefono.value = res.telefono;
            DUI.value = res.DUI;
            correo.value = res.correo;
			departamentoCliente.value = res.departamento;
			municipioCliente.value = res.municipio;
			actividad.value = res.actividad;
			res.exento == 'on' ? chekExento.checked = true : chekExento.checked = false;
			contribuyente.value = res.contribuyente;
            editorDireccion.setData(res.direccion);
			if(res.codigo != undefined ){ 
				cuentaContable.value = res.codigo+' | '+res.nombre_cuenta
			}else{
			cuentaContable.value = "";	
			}
            btnAccion.textContent = 'Actualizar';
            firstTab.show()
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
    errorActividad.textContent = '';
}