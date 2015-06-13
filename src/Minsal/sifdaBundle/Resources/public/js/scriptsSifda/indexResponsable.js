

$(document).ready(function(){
             var estado= $("#txt_dependencia").val();
             var fecha= $("#txt_fecha").val();
             
             if(estado !=="0")
                 $('#dialog').innerHtml('<table border="1"><tr><td>/bundles/minsalsifda/images/ventana/warning.png</td></tr></table>');
                $('#dialog').attr('title','saved').text('Hay '+estado+' solicitudes Nuevas').dialog(); 
                
                
             llamadaAjaxProxVencer(fecha);
        });

 //Funcion que obtiene todas las solicitudes Proximas a vencer
 
 function llamadaAjaxProxVencer(fecha){
     
     alert('Trae solicitudes Proximas a vencer 15 dias despues de '+fecha);
 }
 
 
 
 