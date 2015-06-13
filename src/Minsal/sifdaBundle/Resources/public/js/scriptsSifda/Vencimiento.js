$(document).ready(function (){
    $("#tablahead").hide("slow");
    $("#msjnodata").hide("slow");
    calendarEnlazado('txt_fechaInicio','txt_fechaFin');

    $("#buscar").click(function(){
            $.blockUI({ message: "Espere un instante" });
            $("#tablahead").hide("slow");
            $("#msjnodata").hide("slow");
            var establecimiento = $("#cmb1").val();
            var dependencia = $("#cmb2").val();
            var fechaInicio = $("#txt_fechaInicio").val();
            var fechaFin = $("#txt_fechaFin").val();            
            $.post(
                    '../../consultas/buscarSolicitudesVencer', 
                  { 
                    establecimiento:establecimiento,
                    dependencia:dependencia,
                    fechaInicio:fechaInicio,
                    fechaFin:fechaFin,
                  }
                  , function( data ) {
                    console.log( data );
                    var sol = new Array();
                    sol = data.query;
                    if(sol.length !== 0){
                        $("#tablahead").show("slow");
                        drawTable(data.query);
                    }
                    else{
                        $("#msjnodata").show("slow");
                    }   
                   
                }, "json");
        });
}); //Fin de Jquery


function buscarDependencia(ruta){
    
    var IdEstablecimiento = $("#cmb1").val();
    
    if(IdEstablecimiento !==""){
        
             $.post(
                ruta, 
                  { 
                    idEstablecimiento:IdEstablecimiento
                  }
                  , function( data ) {
                    console.log( data );
                        $("#cmb2").html(data);

                 }, "json");
        }      
}//Fin de buscarDependencia


function drawTable(data) {
    var rows = '';
    var row = '';
    
    row+= '<tr>';
    row+='<th><center>N°</center></th>';
    row+='<th><center>Unidad solicitante</center></th>';
//    row+='<th><center>Establecimiento</center></th>';
    row+='<th><center>Descripci&oacute;n</center></th>';
    row+='<th><center>Fecha que requiere</center></th>';
    row+='<th><center>Dias </center></th>';
    row+='</tr>';
    
    for (var i = 0; i < data.length; i++) {
        rows+=drawRow(data[i],i+1);
    }
    
    document.getElementById('head').innerHTML = row;
    document.getElementById('tabla').innerHTML = rows;    
}

function drawSubTable(data) {
var rows2 = '';
    for (var i = 0; i < data.length; i++) {
        rows2=drawRowA(data[i]);
    }
    return rows2;
}


function drawRow(rowData,id) {
    
    var row = '';
    
    switch(rowData.dias_vencer) {
    case rowData.dias_vencer <= 5:
        row+='<tr class="error">';
        break;
    case rowData.dias_vencer <= 10:
        row+='<tr class="warning">';
        break;
    default:
        row+='<tr class="success">';

    }
    
    row+='<td><center>' + id + '</center></td>';
    row+='<td><center>' + rowData.dependencia +', '+rowData.establecimiento+'</center></td>';
//    row+='<td><center>' + rowData.establecimiento + '</center></td>';
    row+='<td><center>' + rowData.descripcion + '</center></td>';
    row+='<td><center>' + rowData.fecha_requiere + '</center></td>';
    row+='<td><center>' + rowData.dias_vencer + '</center></td>';
    row+='</tr>';
    
    return row;
        
}

$(document).ajaxStop(function(){
    $.unblockUI();
});
