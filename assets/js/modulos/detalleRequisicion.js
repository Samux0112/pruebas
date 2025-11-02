// JS para la vista detalle de requisición
// Redirecciona a la lista de requisiciones
const btnVolver = document.querySelector('#btnVolverRequisiciones');
document.addEventListener('DOMContentLoaded', function() {

        btnVolver.addEventListener('click', function(e) {
            e.preventDefault();
            const url = base_url + 'requisiciones/index';
            window.location.href = url;
        });
    
});
