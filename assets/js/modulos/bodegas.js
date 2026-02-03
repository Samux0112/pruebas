let tblRoles;
const btnAccion = document.querySelector('#btnAccion');
const btnNuevo = document.querySelector('#btnNuevo');
const formulario = document.querySelector('#formulario');

const nombre = document.querySelector('#nombre');
const telefono = document.querySelector('#telefono');
const nombreContacto = document.querySelector('#nombreContacto');
const direccion = document.querySelector('#direccion');
const id = document.querySelector('#id');


const errorNombre = document.querySelector('#errorNombre');

let listaCheck = document.querySelectorAll('.listaPermisos');


document.addEventListener('DOMContentLoaded', function () {
    //cargar datos con el plugin datatables
    tblRoles = $('#tblBodegas').DataTable({
        ajax: {
            url: base_url + 'bodegas/listar',
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { data: 'nombre' },  
			{ data: 'telefono' }, 
			{ data: 'nombreContacto' },
			{ data: 'direccion' },			
            { data: 'acciones' }
        ],
        language: {
            url: base_url + 'assets/js/espanol.json'
        },
        dom,
        buttons,
        responsive: true,
        order: [[0, 'desc']],
    });
    btnNuevo.addEventListener('click', function () {
        id.value = '';
        errorNombre.textContent = '';
        btnAccion.textContent = 'Registrar';
        formulario.reset();
        for (let i = 0; i < listaCheck.length; i++) {
            listaCheck[i].removeAttribute('Check');
            
        }
        formulario.reset();
        nombre.focus();
    })
    //enviar datos
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();
        errorNombre.textContent = '';
        if (nombre.value == '') {
            errorNombre.textContent = 'EL NOMBRE ES REQUERIDO';
        } else {
            const url = base_url + 'bodegas/registrar';
            insertarRegistros(url, this, tblRoles, btnAccion, false);
        }
    });
})

function eliminarRol(idRol) {
    const url = base_url + 'bodegas/eliminar/' + idRol;
    eliminarRegistros(url, tblRoles);
}

function editarRol(idRol) {
    errorNombre.textContent = '';
    const url = base_url + 'bodegas/editar/' + idRol;
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
            let arreglo = res.permisos;
            for (let i = 0; i < listaCheck.length; i++) {
                listaCheck[i].removeAttribute('Checked');
                if (arreglo.includes(listaCheck[i].value)) {
                    listaCheck[i].setAttribute('Checked','Checked');  
                }  
            }
            id.value = res.rol.id;
            nombre.value = res.rol.nombre;
			telefono.value = res.rol.telefono;
			nombreContacto.value = res.rol.nombreContacto;
			direccion.value = res.rol.direccion;
            btnAccion.textContent = 'Actualizar';
            firstTab.show()
        }
    }
}

function productos(id){
	
	window.location.href = base_url + 'bodegas/verstock/' + id;
	
}