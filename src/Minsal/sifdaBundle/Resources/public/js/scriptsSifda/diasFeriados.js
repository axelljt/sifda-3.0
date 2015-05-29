
$(document).ready(function(){
    
    document.getElementById('minsal_sifdabundle_ctlferiado_fechaInicio').value="";
    document.getElementById('minsal_sifdabundle_ctlferiado_tipoFecha_0').checked = true;
    $('#fechaFin_ctlferiado').hide("slow");    
    calendarAnioActual('minsal_sifdabundle_ctlferiado_fechaInicio');
        
    $('#minsal_sifdabundle_ctlferiado_tipoFecha_0').click(function(){
        $('#fechaFin_ctlferiado').hide("slow");
        $('#fechaInicio_ctlferiado').show("slow");
        calendarAnioActual('minsal_sifdabundle_ctlferiado_fechaInicio');
    });
    
    $('#minsal_sifdabundle_ctlferiado_tipoFecha_1').click(function(){
        $('#fechaInicio_ctlferiado').show("slow");
        $('#fechaFin_ctlferiado').show("slow"); 
        calendarEnlazado('minsal_sifdabundle_ctlferiado_fechaInicio', 'minsal_sifdabundle_ctlferiado_fechaFin');
    });    
});


function asignarFeriado(){
    
    var r1=document.getElementById('minsal_sifdabundle_ctlferiado_tipoFecha_0').checked;
    var r2=document.getElementById('minsal_sifdabundle_ctlferiado_tipoFecha_1').checked;
    
    if(r1===false && r2 ===false)
        alert('Debe seleccionar un tipo de Fecha');
        
    if(r1)    
        asignarFechasFeriadas();
    
    if(r2)
        asignarRangoFechasFeriadas();
    
    return false;
}

function asignarFechasFeriadas(){
     
   var texto=$("#minsal_sifdabundle_ctlferiado_fechaInicio").val();
   var lista=document.getElementById('minsal_sifdabundle_ctlferiado_fechaFestiva');
       
   if(texto !=="")
        buscarFechaFeriada(texto,lista);
   else
       alert('Debe seleccionar una fecha');
 
}


function asignarRangoFechasFeriadas(){
    
    var date1=$("#minsal_sifdabundle_ctlferiado_fechaInicio").val();
    var date2=$("#minsal_sifdabundle_ctlferiado_fechaFin").val();
    var lista=document.getElementById('minsal_sifdabundle_ctlferiado_fechaFestiva');
    
    
    if(date1 !=="" && date2 !=="")
        {
            var texto= date1+', '+date2;
            buscarRangoFechaFeriada(texto,lista);
        } 
    else
       alert('Debe seleccionar fecha de inicio y Fin');
}



function eliminarFecha(){
    
    var lista=document.getElementById("minsal_sifdabundle_ctlferiado_fechaFestiva");
     if (lista.options.selectedIndex !== -1)
            lista.options[lista.selectedIndex]=null;
      else
          alert('No hay fechas para remover');
    
}

function limpiarListbox(){
     var lista=document.getElementById("minsal_sifdabundle_ctlferiado_fechaFestiva");
     if (lista.options.length>0){
         
         for(var i=lista.options.length-1;i>=0;i--){
             lista.remove(i);
         }
     }
               
      else
          alert('No hay fechas para remover');    
}

function buscarFechaFeriada(texto,lista){
    
    var num = lista.length;
    var aux=0;
    if(num === 0){
        lista.options.add(new Option(texto));
        $("#minsal_sifdabundle_ctlferiado_fechaInicio").val("");            
     }
    else
    {
      for(var i=0;i<lista.length; i++){
          var itemLs= lista[i];
                
          if(texto === (itemLs.value))
            {
              alert('La Fecha ya fue elegida');
              aux=1;
              break;
            }

      }
      
        if(aux===0){
          
           lista.options.add(new Option(texto));
           OrdenarListBoox(lista);
           $("#minsal_sifdabundle_ctlferiado_fechaInicio").val("");
          
        }
    }         
}

function buscarRangoFechaFeriada(texto,lista){
    
    var num = lista.length;
    var aux=0;
    if(num === 0){
        lista.options.add(new Option(texto));
        $("#minsal_sifdabundle_ctlferiado_fechaInicio").val("");  
        $("#minsal_sifdabundle_ctlferiado_fechaFin").val("");
     }
    else
    {
      for(var i=0;i<lista.length; i++){
          var itemLs= lista[i];
         
          if(texto === (itemLs.value))
            {
              alert('La Fecha ya fue elegida');
              aux=1;
              break;
            }
      }
      
      if(aux===0){
          
          lista.options.add(new Option(texto));
          OrdenarListBoox(lista);
          $("#minsal_sifdabundle_ctlferiado_fechaInicio").val("");
          $("#minsal_sifdabundle_ctlferiado_fechaFin").val("");
          
      }
    }         
}

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

function recuperarFechasElegidas(){
    
    var lista=document.getElementById("minsal_sifdabundle_ctlferiado_fechaFestiva");
    var fechaFestiva = new Array();
    
   if(lista.length>0)
    {
        for(var i=0; i<lista.length; i++)  {
        fechaFestiva[i] = lista.options[i].text;
        }   
        
        for ( var i = 0, l = lista.options.length, o; i < l; i++ )
        {
            o = lista.options[i];
            if ( fechaFestiva.indexOf( o.text ) != -1 )
            {
                o.selected = true;                
            }
        } 
        return false; 
    } 
    else {
          return false;  
    }
}

/*****************************************************************************************/
