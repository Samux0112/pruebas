// JS para la vista detalle de requisición
// Redirecciona a la lista de requisiciones

document.addEventListener('DOMContentLoaded', function() {
    const btnVolver = document.getElementById('btnVolverRequisiciones');
    if (btnVolver) {
        btnVolver.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = 'index.php';
        });
    }
});
