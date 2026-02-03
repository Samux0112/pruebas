const finicio = document.querySelector('#finicio');
const ffin = document.querySelector('#ffin');
const documento = document.querySelector('#tipoDocumento');
const btnpdf = document.querySelector('#btnpdf');


if(documento.value == "Sujeto Excluido" || documento.value == "Anulaciones"){
	document.getElementById('btnpdf').disabled = true;
}





function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
   downloadLink.style.display = "none";

    // Add the link to DOM
   document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}

function exportTableToCSV() {

	const finicio = document.querySelector('#finicio');
    const ffin = document.querySelector('#ffin');
	documento.value;
	var filename ='Libro_'+documento.value+"_"+finicio.value+"_"+ffin.value+".csv";
    var csv = [];
    var rows = document.querySelectorAll("table tr");
    
    for (var i = 1; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText);
        
        csv.push(row.join(";"));        
    }

    // Download CSV file
    downloadCSV(csv.join("\r\n"), filename);
}


 btnAccion.addEventListener('click', function () {
   exportTableToCSV();
})

function generarPdf(mes,anio,documento,finicio,ffin) {
	var mesSeleccionado = mes == 1 ? "Enero" : mes == 2 ? "Febrero" : mes == 3 ? "Marzo" : mes == 4 ? "Abril" : mes == 5 ? "Mayo" : mes == 6 ? "Junio" : mes == 7 ? "Julio" : mes ==8 ? "Agosto" : mes == 9 ? "Septiembre" : mes == 10 ? "Octubre" : mes == 11 ? "Noviembre" : "Diciembre";                                     
 const ruta = base_url + 'librosIva/reporte/impresion/' + mesSeleccionado + '/'+ mes + '/'+ anio +'/'+documento+'/'+finicio+'/'+ffin;
 window.open(ruta, '_blank');
           

}