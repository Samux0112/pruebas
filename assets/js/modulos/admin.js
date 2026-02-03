const formulario = document.querySelector('#formulario');
const btnAccion = document.querySelector('#btnAccion');

const ruc = document.querySelector('#ruc');
const nombre = document.querySelector('#nombre');
const correo = document.querySelector('#correo');
const rango = document.querySelector('#rango');
const rangoc = document.querySelector('#rangoc');
const ticket = document.querySelector('#ticket');
const notasc = document.querySelector('#notasc');
const recibo = document.querySelector('#recibo');
const dui = document.querySelector('#dui');
const registro = document.querySelector('#registro');
const giro = document.querySelector('#giro');
const telefono = document.querySelector('#telefono');
const direccion = document.querySelector('#direccion');
const foto = document.querySelector('#foto');
const containerPreview = document.querySelector('#containerPreview');

const errorRuc = document.querySelector('#errorRuc');
const errorNombre = document.querySelector('#errorNombre');
const errorTelefono = document.querySelector('#errorTelefono');
const errorCorreo = document.querySelector('#errorCorreo');
const errorRango = document.querySelector('#errorRango');
const errorRangoc = document.querySelector('#errorRangoc');
const errorTicket = document.querySelector('#errorTicket');
const errorNotasc = document.querySelector('#errorNotasc');
const errorRecibo = document.querySelector('#errorRecibo');
const errorDui = document.querySelector('#errorDui');
const errorRegistro = document.querySelector('#errorRegistro');
const errorGiro = document.querySelector('#errorGiro');
const errorDireccion = document.querySelector('#errorDireccion');

document.addEventListener('DOMContentLoaded', function () {
    //Inicializar un Editor
    ClassicEditor
        .create(document.querySelector('#mensaje'), {
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
        .catch(error => {
            console.error(error);
        });

        //vista Previa
    foto.addEventListener('change', function(e){
        if (e.target.files[0].type == 'image/png') {
            const url = e.target.files[0];
            const tmpUrl = URL.createObjectURL(url);
            containerPreview.innerHTML = `<img class="img-thumbnail" src="${tmpUrl}" width="200">
            <button class="btn btn-danger" type="button" onclick="deleteImg()"><i class="fas fa-trash"></i></button>`;
        }else{
            foto.value = '';
            alertaPersonalizada('warning', 'SOLO SE PERMITEN IMG DE TIPO PNG');
        }
    })
    
    //actualizar datos
    formulario.addEventListener('submit', function (e) {
        e.preventDefault();
        errorRuc.textContent = '';
        errorNombre.textContent = '';
        errorTelefono.textContent = '';
        errorCorreo.textContent = '';
        errorRango.textContent = '';
        errorRangoc.textContent = '';
        errorTicket.textContent = '';
        errorNotasc.textContent = '';
        errorRecibo.textContent = '';
        errorDui.textContent = '';
        errorRegistro.textContent = '';
        errorGiro.textContent = '';
        errorDireccion.textContent = '';
        if (ruc.value == '') {
            errorRuc.textContent = 'EL RUC ES REQUERIDO';
        } else if (nombre.value == '') {
            errorNombre.textContent = 'EL NOMBRE ES REQUERIDO';
        } else if (correo.value == '') {
            errorCorreo.textContent = 'EL CORREO ES REQUERIDO';

        } else if (rango.value == '') {
            errorRango.textContent = 'EL RANGO ES REQUERIDO';

        } else if (rangoc.value == '') {
            errorRangoc.textContent = 'EL RANGOC ES REQUERIDO';

            
        } else if (dui.value == '') {
            errorDui.textContent = 'EL DUI ES REQUERIDO';

        } else if (registro.value == '') {
            errorRegistro.textContent = 'EL REGISTRO ES REQUERIDO';

        } else if (giro.value == '') {
            errorGiro.textContent = 'EL GIRO ES REQUERIDO';

        

        } else if (telefono.value == '') {
            errorTelefono.textContent = 'EL TELEFONO ES REQUERIDO';
        } else if (direccion.value == '') {
            errorDireccion.textContent = 'LA DIRECCIÓN ES REQUERIDO';
        } else {
            const url = base_url + 'admin/modificar';
            insertarRegistros(url, this, null, btnAccion, false);
        }

    })
})

function deleteImg(){
    foto.value = '';
    containerPreview.innerHTML = '';
}