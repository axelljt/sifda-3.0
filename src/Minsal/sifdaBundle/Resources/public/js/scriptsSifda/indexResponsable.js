

$(document).ready(function(){
             var estado= $("#txt_dependencia").val();
             var fecha= $("#txt_fecha").val();
             var ruta= $("#ruta").val();          
             
//             if(estado !=="0")
//                $('#dialog').attr('title','saved').text('Hay '+estado+' solicitudes Nuevas').dialog(); 
//                
                
             llamadaAjaxProxVencer(fecha,ruta,estado);
        });

 //Funcion que obtiene todas las solicitudes Proximas a vencer
 
 function llamadaAjaxProxVencer(fecha,ruta,estado){
     
     $.post(
            ruta,
            {
                fechaSistema: fecha
            }
    , function (data) {
        console.log(data);
        
        
        var res = data.query;
            
        if (res === "0") {

//            $('#msgFinalizado').show("slow");
//            $("#rangoFinTabla").hide("slow");
        }
        else
        {
           $('#dialog').attr('title','saved').text('Hay '+estado+' solicitudes Nuevas \n'+'Cantidad de Solicitudes a vencer'+data.query[0].cant_dias).dialog(); 
//           alert('Cantidad de Solicitudes a vencer'+data.query[0].cant_dias);
        }


    }, "json"); 
//     
//     alert('Trae solicitudes Proximas a vencer 15 dias despues de '+fecha);
     
 }
 
 
 
 function drawTable(data) {
    var rows = '';
    var row = '';
    
    row+= '<tr>';
    row+='<th><center>N°</center></th>';
    row+='<th><center>Dependencia solicitante</center></th>';
    row+='<th><center>Tipo de servicio</center></th>';
    row+='<th><center>Descripci&oacute;n</center></th>';
    row+='<th><center>Persona solicitante</center></th>';
    row+='<th><center>Fecha que requiere</center></th>';
    row+='<th><center>Acciones</center></th>';
    row+='</tr>';
    
    alert(data[0].cant_dias);
    
//    document.getElementById('head').innerHTML = row;
//    document.getElementById('tabla').innerHTML = rows;    
}