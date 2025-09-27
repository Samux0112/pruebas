const tblNuevaCotizacion = document.querySelector('#tblNuevaCotizacion tbody');

const idCliente = document.querySelector('#idCliente');
const telefonoCliente = document.querySelector('#telefonoCliente');
const direccionCliente = document.querySelector('#direccionCliente');

const descuento = document.querySelector('#descuento');
const metodo = document.querySelector('#metodo');
const validez = document.querySelector('#validez');
const comentario = document.querySelector('#comentario');

const errorCliente = document.querySelector('#errorCliente');
const idPedido = document.querySelector('#idPedido');
const bodeguero = document.querySelector('#bodeguero');


document.addEventListener('DOMContentLoaded', function () {
	
	    btnAccion.addEventListener('click', function () {
				            const url = base_url + 'pedidos/updatePedido';
            //hacer una instancia del objeto XMLHttpRequest 
            const http = new XMLHttpRequest();
            //Abrir una Conexion - POST - GET
            http.open('POST', url, true);
            //Enviar Datos
            http.send(JSON.stringify({
                id: idPedido.value,        
                metodo: metodo.value,
				comentarios : comentarios.value
            }));
            //verificar estados
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    const res = JSON.parse(this.responseText);
                    console.log(this.responseText);
                    alertaPersonalizada(res.type, res.msg);
                    if (res.type == 'success') {
                       
                        setTimeout(() => {
                                window.location.href = base_url + 'pedidos/despacho';
                        }, 2000);
                    }
                }
            }
				
			

        

    })

})

