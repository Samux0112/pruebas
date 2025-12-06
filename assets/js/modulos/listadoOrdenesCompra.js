// JS para la vista listado de órdenes de compra
// Puedes agregar aquí funciones para filtros, búsqueda, acciones, etc.

document.addEventListener('DOMContentLoaded', function() {
    // Código JS futuro para la vista listado
    function compra(idOrden) {
        localStorage.removeItem('posVenta2');
        let listaCarrito = [];
        const url = base_url + 'ordenesCompra/editar/' + idOrden;
        const http = new XMLHttpRequest();
        http.open('GET', url, true);
        http.send();
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                const res = JSON.parse(this.responseText);
                const productos = JSON.parse(res.productos);
                const id_proveedor = res.id_proveedor;
                // Aquí puedes setear datos de proveedor, etc. si lo necesitas
                for (let i = 0; i < productos.length; i++) {
                    listaCarrito.push({
                        id: productos[i].id,
                        cantidad: productos[i].cantidad,
                        precio: productos[i].precio,
                        descripcion: productos[i].nombre || productos[i].descripcion || '',
                        catalogo: "Normal"
                    });
                }
                localStorage.setItem('posVenta2', JSON.stringify(listaCarrito));
                if (listaCarrito.length > 0) {
                    window.location.href = base_url + 'ventas2/comprasCotizacion/'+idOrden+'/'+id_proveedor;
                }
            }
        }
    }
    
    // Si quieres asociar el botón aquí, puedes hacerlo así:
    document.querySelectorAll('.btnCompletarCompra').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const idOrden = btn.getAttribute('data-id');
            compra(idOrden);
        });
    });
});
