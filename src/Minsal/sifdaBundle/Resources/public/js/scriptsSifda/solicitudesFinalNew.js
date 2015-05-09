
$(document).ready(function (){
    
    document.getElementById("pdf").checked=false;
    document.getElementById("excel").checked=false;
    document.getElementById("grafico").checked=false;

    document.getElementById("cmb1").value=0;
    document.getElementById('txt_fechaInicio').value="";
    document.getElementById('txt_fechaFin').value="";
    
     $("#FinTabla").hide("slow");
     $('#Finalizado').hide("slow");
     $('#msgFinalizado').hide("slow");
     $('#rangoFinTabla').show("slow");
    
    calendarEnlazado('txt_fechaInicio','txt_fechaFin');
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


function exportfile(){
    
    var pdf=document.getElementById('pdf').checked;
    var excel=document.getElementById('excel').checked;
    var graf=document.getElementById('grafico').checked;
    
    
    if(pdf !==false)
    {
      alert('llamada pdf');  
      window.open('/reports/phpexcel/solicitudesFinalizadas.php?fi='+mostrarfi()+'&ff='+mostrarff()+'&tp='+'mcontreras'+'&user='+mostrarusername());  
    }
       
    
    if(excel !== false)
        window.open('/reports/phpexcel/solicitudesFinalizadasExcel.php?fi='+mostrarfi()+'&ff='+mostrarff()+'&tp='+'mcontreras'+'&user='+mostrarusername());
//       alert('excel:'+excel);
    
    if(graf!== false)   
       alert('graf:'+graf);
}

//Funcion que busca las solicitudes de servicio

function buscarSolicitudesFinal(ruta){
    
    var dependencia=$("#cmb2").val();
    var fechaInicio=$("#txt_fechaInicio").val();
    var fechaFin=$("#txt_fechaFin").val();
    
    alert(dependencia);
    
    if(fechaFin !=="" && fechaInicio!=="" && dependencia!=="0"){
               
               $.post(
                    ruta,    
                    
                    { 
                      fechaInicio: fechaInicio,
                      fechaFin   : fechaFin,
                      dependencia: dependencia
                    }
                        , function( data ) {
                           console.log( data );
                           
                            res=data.val;
                           
                           if(res=="0"){
                               
                                $('#msgFinalizado').show("slow");
                                $("#rangoFinTabla").hide("slow");
                            }
                           else
                           {
                               $("#rangoFinTabla").html(data);
                           }
                         
                
                    }, "json"); 
    }
}


 //Funciones para reporteria
     
      function mostrarfi(){ 
            return $("#fechaInicio").val();
      }
    
      function mostrarff(){ 
            return $("#fechaFin").val();
      }
      
      function mostrarDependencia(){ 
            return $("#cmb1").val();
      } 
      
      function mostraruserid(){ 
            return "{{usuario.id}}";
      }
      function mostrarusername(){ 
            return "{{usuario.username}}";
      }
      

