// JS para la vista detalle de requisición
// Redirecciona a la lista de requisiciones y a cotización
// JS para la vista detalle de requisición
// Redirecciona a la lista de requisiciones y a cotización
document.addEventListener('DOMContentLoaded', function() {
    const btnVolver = document.getElementById('btnVolverRequisiciones');
    if (btnVolver) {
        btnVolver.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = 'index.php';
        });
    }
    const btnCotizar = document.getElementById('btnCotizarRequisicion');
        btnCotizar.addEventListener('click', function(e) {
            e.preventDefault();
            const id = btnCotizar.getAttribute('data-id');
            window.location.href = base_url+'requisiciones/cotizacion/' + encodeURIComponent(id);
        });

});
