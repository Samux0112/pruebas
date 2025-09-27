let tblClientes2;
document.addEventListener('DOMContentLoaded', function(){
    //cargar datos con el plugin datatables
    tblClientes2 = $('#tblClientes2').DataTable({
        ajax: {
            url: base_url + 'clientes2/listarInactivos',
            dataSrc: ''
        },
        columns: [
            { data: 'identidad' },
            { data: 'num_identidad' },
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
})

function restaurarCliente2(idCliente2) {
    const url = base_url + 'clientes2/restaurar/' + idCliente2;
    restaurarRegistros(url, tblClientes2);
}