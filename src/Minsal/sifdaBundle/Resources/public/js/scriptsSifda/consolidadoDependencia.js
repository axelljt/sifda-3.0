    $(document).ready(function(){
//    Condiciones Iniciales de la Pantalla
    document.getElementById("cmb1").value="0";
    document.getElementById("txt_fechaInicio").value=null;
    document.getElementById("txt_fechaFin").value=null;
    $("#tablahead").hide("slow");
    $("#msjnodata").hide("slow");
    
    $("#buscar").click(function(){
        $("#tablahead").hide("slow");
        $("#msjnodata").hide("slow");
        var establecimiento = $("#cmb1").val();
        var dependencia = $("#cmb2").val();
        var fechaInicio = $("#txt_fechaInicio").val();
        var fechaFin = $("#txt_fechaFin").val();
        var pdf=document.getElementById('pdf').checked;
        var excel=document.getElementById('excel').checked;
        var graf=document.getElementById('grafico').checked;

        if(pdf !==false || excel !== false || graf!== false)
        {
            $.blockUI({ message: "Espere un instante" });
            $.post(
                    '../consolidadoSolicitudesDependencia', 
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

                }, "json"
            );
        }
        else {
            alert('Debe seleccionar modo de exportar');
        }
    });

}); // Fin de JQuery

$(document).ajaxStop(function(){
    $.unblockUI();
});

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
    row+='<th><center>Tipo de servicio</center></th>';
    row+='<th><center>Solicitudes sin atender</center></th>';
    row+='<th><center>Solicitudes en proceso</center></th>';
    row+='<th><center>Solicitudes rechazadas</center></th>';
    row+='<th><center>Solicitudes finalizadas</center></th>';
    row+='<th><center>Total de solicitudes</center></th>';
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
    
//    switch(rowData.prioridad) {
//    case 1:
//        row+='<tr class="error">';
//        break;
//    case 2:
//        row+='<tr class="warning">';
//        break;
//    case 3:
//        row+='<tr class="success">';
//        break;  
//    default:
//        row+='<tr class="info">';
//
//    }
    row+='<tr>';
    row+='<td><center>' + id + '</center></td>';
    row+='<td><center>' + rowData.tipo_servicio + '</center></td>';
    row+='<td><center>' + rowData.sin_atender + '</center></td>';
    row+='<td><center>' + rowData.en_proceso + '</center></td>';
    row+='<td><center>' + rowData.rechazadas + '</center></td>';
    row+='<td><center>' + rowData.finalizadas + '</center></td>';
    row+='<td bgcolor="#d9edf7"><center>' + rowData.total + '</center></td>';
    row+='</tr>';
        
    return row;
    
}

    $.datepicker.regional['es'] = {
			 closeText: 'Cerrar',
			 prevText: '<Ant',
			 nextText: 'Sig>',
			 currentText: 'Hoy',
			 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
			 dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi�rcoles', 'Jueves', 'Viernes', 'S�bado'],
			 dayNamesShort: ['Dom','Lun','Mar','Mi�','Juv','Vie','S�b'],
			 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S�'],
			 weekHeader: 'Sm',
			 showButtonPanel:false,
			 showAnim:'show',
                         changeMonth: true,
			 changeYear: true,
//                         minDate: new Date(2014, 1, 1), 
//                         maxDate: "1D",
                         dateFormat: 'yy-mm-dd',
			 firstDay: 1,
			 isRTL: false,
			 showMonthAfterYear: false,
			 			                         
                         onClose: function (selectedDate) {
                         
                          $("#txt_fechaInicio").datepicker("option", "maxDate", selectedDate);
                          $("#txt_fechaFin").datepicker("option", "minDate", selectedDate);
                          
                         },
                         
                         yearSuffix: ''
                         
			 };
			$.datepicker.setDefaults($.datepicker.regional['es']);
			$(function() {
				$( "#txt_fechaInicio" ).datepicker(
					
					);
                                
                                $( "#txt_fechaFin" ).datepicker(
					
					);
                                
                                
				}); 
                                       
       
    function confirmar(valor){         
        confirmar=confirm("Desea Rechazar la Solicitud?"); 
        if (confirmar)
            LlamarControlador('../solicitudservicio/sifda/rechazar',$("#txt"+valor).val());
        else 
            // si pulsamos en cancelar
            alert('Has dicho que no');         
}  
    
    function LlamarControlador(url,valor)
    {
        $.post(
            url,
            {
                id:valor
            }).done(function(){
                 window.location='../solicitudservicio/gestionSolicitudes';   
                ('#dialog').attr('title','Confirmacion').text('Solicitud Rechazada Correctamente').dialog();               
            });
    }
    
    function aceptarSolicitud(url,valor2)
    {
        $.post(
            url,
            {
                id:valor2    
            }).done(function(){
                 window.location='../solicitudservicio/gestionSolicitudes';   
                ('#dialog').attr('title','Confirmacion').text('La solicitud ha sido aceptada correctamente').dialog();               
                
            });
    }    
    
    /*Codigo para nueva ventana*/
  function ShowDialog(valor) {
    
        $('#spanMessage').html('¿Está seguro(a) de Rechazar la Solicitud?');
        $("#dialogConfirm").dialog({
            resizable: false,
            height: 160,
            width: 300,
            modal: true,
            title: 'Confirmacion',
            buttons: {
                'Aceptar': function () {
                    LlamarControlador('../solicitudservicio/sifda/rechazar',$("#txt"+valor).val());
                    $(this).dialog("close");
                },
                    'Cancelar': function () {
                    $(this).dialog("close");
                }
            }
        });
    }
    
    /*Codigo para aceptar la solicitud que ha sido rechazada*/
    function ShowDialog2(valor2) {
    
        $('#spanMessage').html('¿Está seguro(a) de aceptar la solicitud?');
        $("#dialogConfirm").dialog({
            resizable: false,
            height: 160,
            width: 300,
            modal: true,
            title: 'Confirmacion',
            buttons: {
                'Aceptar': function () {
                    aceptarSolicitud('../solicitudservicio/sifda/aceptar',$("#txt_"+valor2).val());
                    $(this).dialog("close");
                },
                    'Cancelar': function () {
                    $(this).dialog("close");
                }
            }
        });
    }
    /*Fin del codigo de ventana*/
   
   function mostrarfi(){ 
      return $("#fechaInicio").val();
        
    }
    
      function mostrarff(){ 
       return $("#fechaFin").val();
        
    }
   
   function LimpiarCamposFecha(){
            
        document.getElementById('fechaInicio').value="";
        document.getElementById('fechaFin').value="";
        
    }
