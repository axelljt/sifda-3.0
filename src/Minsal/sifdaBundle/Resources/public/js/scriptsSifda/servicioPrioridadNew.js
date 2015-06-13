
$(document).ready(function (){
    document.getElementById("minsal_sifdabundle_sifdaservicioprioridad_idTipoServicio").value="";
    document.getElementById("minsal_sifdabundle_sifdaservicioprioridad_establecimiento").value="";
    document.getElementById("minsal_sifdabundle_sifdaservicioprioridad_dependencia").value="";
    document.getElementById("minsal_sifdabundle_sifdaservicioprioridad_idPrioridad").value="";
    
        
});

function buscarEstablecimientoDependencia(ruta1, ruta2){
    
    var IdEstablecimiento = $("#minsal_sifdabundle_sifdaservicioprioridad_establecimiento").val();

            if(IdEstablecimiento !="")
            {
                $.post(
                    ruta1,
                  { 
                    idEstablecimiento:IdEstablecimiento
                  }
                  , function( data ) {
                    console.log( data );
                        $("#minsal_sifdabundle_sifdaservicioprioridad_dependencia").html(data);

                 }, "json");
            }
            else 
            {
                $.post(
                    ruta2,
                    function( data ) {
                    console.log( data );
                        $("#minsal_sifdabundle_sifdaservicioprioridad_dependencia").html(data);

                 }, "json");
                 
            }
}// Fin de buscarEstablecimientoDependencia

function asignarPrioridad(){
    
    var idEstablecimiento = document.getElementById('minsal_sifdabundle_sifdaservicioprioridad_establecimiento').options.selectedIndex; 
    var establecimiento = document.getElementById('minsal_sifdabundle_sifdaservicioprioridad_establecimiento').options[idEstablecimiento].text;

    var idDependencia = document.getElementById('minsal_sifdabundle_sifdaservicioprioridad_dependencia').options.selectedIndex; 
    var dependencia = document.getElementById('minsal_sifdabundle_sifdaservicioprioridad_dependencia').options[idDependencia].text;
    
    var idPrioridad = document.getElementById('minsal_sifdabundle_sifdaservicioprioridad_idPrioridad').options.selectedIndex; 
    var prioridad = document.getElementById('minsal_sifdabundle_sifdaservicioprioridad_idPrioridad').options[idPrioridad].text;
    
    var lista = document.getElementById('minsal_sifdabundle_sifdaservicioprioridad_servicioPrioridad');
    
    if($("#minsal_sifdabundle_sifdaservicioprioridad_establecimiento").val() !=="" 
            && $("#minsal_sifdabundle_sifdaservicioprioridad_dependencia").val() !=="" 
            && $("#minsal_sifdabundle_sifdaservicioprioridad_idPrioridad").val() !=="")
        {
            var texto = dependencia+' - '+establecimiento;
            setPrioridadServicio(texto, lista, prioridad);
        } 
    else
       alert('Debe seleccionar establecimiento, dependencia y prioridad');
   
}// Fin de asignarPrioridad()

function setPrioridadServicio(texto, lista, prioridad){
    
    var num = lista.length;
    var aux=0;
    if(num === 0){
        texto+= ' - '+prioridad;
        lista.options.add(new Option(texto));
        $("#minsal_sifdabundle_sifdaservicioprioridad_establecimiento").val("");
        $("#minsal_sifdabundle_sifdaservicioprioridad_dependencia").val("");
        $("#minsal_sifdabundle_sifdaservicioprioridad_idPrioridad").val("");
     }
    else
    {
      for(var i=0;i<lista.length; i++){
          var depest = lista[i].text.split(' - ');
          var itemLs = depest[0]+' - '+depest[1];
          
          if(texto === (itemLs))
            {
              alert('La dependencia ya fue elegida');
              aux=1;
              break;
            }
      }
      
      if(aux===0){
          texto+= ' - '+prioridad;
          lista.options.add(new Option(texto));
          $("#minsal_sifdabundle_sifdaservicioprioridad_establecimiento").val("");
          $("#minsal_sifdabundle_sifdaservicioprioridad_dependencia").val("");
          $("#minsal_sifdabundle_sifdaservicioprioridad_idPrioridad").val("");
          
      }
    }         
}//  Fin de setPrioridadServicio

function eliminarPrioridadServicio(){
    
    var lista=document.getElementById("minsal_sifdabundle_sifdaservicioprioridad_servicioPrioridad");
    if (lista.options.selectedIndex !== -1)
        lista.options[lista.selectedIndex]=null;
    else
        alert('No hay elemento seleccionado para remover');
    
}//  Fin de eliminarPrioridadServicio

function limpiarListbox(){
     var lista=document.getElementById("minsal_sifdabundle_sifdaservicioprioridad_servicioPrioridad");
     if (lista.options.length>0){
         
         for(var i=lista.options.length-1;i>=0;i--){
             lista.remove(i);
         }
     }
               
      else
          alert('No hay elementos para remover');    
}// Fin de limpiarListbox

function getPrioridadServicioElegidos(){
    
    $.blockUI({ message: "Espere un instante" }); 
    var lista=document.getElementById("minsal_sifdabundle_sifdaservicioprioridad_servicioPrioridad");
    var servicioPrioridad = new Array();
    
   if(lista.length>0)
    {
        for(var i=0; i<lista.length; i++)  {
        servicioPrioridad[i] = lista.options[i].text;
        }   
        
        for ( var i = 0, l = lista.options.length, o; i < l; i++ )
        {
            o = lista.options[i];
            if ( servicioPrioridad.indexOf( o.text ) != -1 )
            {
                o.selected = true;                
            }
        } 
        return false; 
    } 
    else {
          return false;  
    }
}//Fin de getPrioridadServicioElegidos

//Funcion Ordenar Elementos de Listbox
function OrdenarListBoox(lista){
    
    var arrTexts = new Array();
    var arrTextsNew = new Array();
    
    for(var i=0; i<lista.length; i++)  {
        arrTexts[i] = lista.options[i].text;
    }
    
    arrTextsNew=arrTexts.sort();
    
    for(i=0; i<lista.length; i++)  {
        lista.options[i].text = arrTextsNew[i];
        lista.options[i].value = arrTextsNew[i];
   }   
}
     