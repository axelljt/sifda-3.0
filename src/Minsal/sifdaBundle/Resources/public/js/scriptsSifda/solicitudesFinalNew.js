
$(document).ready(function (){
    
    document.getElementById("pdf").checked=false;
    document.getElementById("excel").checked=false;
    document.getElementById("grafico").checked=false;
    document.getElementById('txt_fechaInicio').readOnly=true;
    document.getElementById('txt_fechaFin').readOnly=true;

    document.getElementById("cmb1").value=0;
    document.getElementById('txt_fechaInicio').value="";
    document.getElementById('txt_fechaFin').value="";
    
     $("#FinTabla").hide("slow");
     $('#Finalizado').hide("slow");
     $('#msgFinalizado').hide("slow");
     $('#rangoFinTabla').show("slow");
    
    $('#export').attr("disabled", true);  
    
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


function exportfile(user){
    
    var pdf=document.getElementById('pdf').checked;
    var excel=document.getElementById('excel').checked;
    var graf=document.getElementById('grafico').checked;
    var user=document.getElementById('txtuser').value;
    var iduser=document.getElementById('txtiduser').value;
       
    if(pdf !==false)
    {
      alert('llamada pdf');  
      
      window.open('/reports/solicitudes_Finalizadas2.php?fi='+mostrarfi()+'&ff='+mostrarff()+'&tp='+iduser+'&user='+user);  
    }
       
    
    if(excel !== false)
        window.open('/reports/phpexcel/solicitudesFinalizadasExcel.php?fi='+mostrarfi()+'&ff='+mostrarff()+'&tp='+iduser+'&user='+user);
//       alert('excel:'+excel);
    
    if(graf!== false)   
       alert('graf:'+graf);
}

//Funcion que busca las solicitudes de servicio

function buscarSolicitudesFinal(ruta){
    
    var dependencia=$("#cmb2").val();
    var fechaInicio=$("#txt_fechaInicio").val();
    var fechaFin=$("#txt_fechaFin").val();
    
       
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
                           
                           if(res==="0"){
                               
                                $('#msgFinalizado').show("slow");
                                $("#rangoFinTabla").hide("slow");
                                $('#export').attr("disabled", true);
                            }
                           else
                           {
                               $("#rangoFinTabla").html(data);
                                $('#export').attr("disabled", false);
                           }
                         
                
                    }, "json"); 
    }
}


 //Funciones para reporteria
     
      function mostrarfi(){ 
            return $("#txt_fechaInicio").val();
      }
    
      function mostrarff(){ 
            return $("#txt_fechaFin").val();
      }
      
      function mostrarDependencia(){ 
          
          var posicion=document.getElementById('cmb2').options.selectedIndex;
          var valor=document.getElementById('cmb2').options[posicion].text;
          alert(valor);  
      } 
      
      function mostraruserid(){ 
            return "{{usuario.id}}";
      }
      function mostrarusername(){ 
            return "{{usuario.username}}";
      }
      

