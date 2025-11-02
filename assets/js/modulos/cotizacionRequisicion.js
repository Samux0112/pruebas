// JS para la vista cotización de requisiciones
// Calcula el subtotal en la tabla de productos

document.addEventListener('DOMContentLoaded', function() {
    function calcularSubtotal(row) {
        const cantidad = parseFloat(row.querySelector('.input-cantidad').value) || 0;
        const precio = parseFloat(row.querySelector('.input-precio').value) || 0;
        const descuento = parseFloat(row.querySelector('.input-descuento').value) || 0;
        let subtotal = (cantidad * precio) - descuento;
        if (subtotal < 0) subtotal = 0;
        row.querySelector('.input-subtotal').value = subtotal.toFixed(2);
    }

    document.querySelectorAll('.input-precio, .input-descuento').forEach(function(input) {
        input.addEventListener('input', function() {
            const row = input.closest('tr');
            calcularSubtotal(row);
        });
    });
});
