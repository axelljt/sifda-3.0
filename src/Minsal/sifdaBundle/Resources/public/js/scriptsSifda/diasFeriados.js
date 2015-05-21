
$(document).ready(function(){
    
    document.getElementById('minsal_sifdabundle_ctlferiado_fechaInicio').value="";
    $('#fechaInicio_ctlferiado').hide("slow");
    $('#fechaFin_ctlferiado').hide("slow");
    
    calendarNormal('minsal_sifdabundle_ctlferiado_fechaInicio');
    calendarNormal('minsal_sifdabundle_ctlferiado_fechaFin');
    
    $('#minsal_sifdabundle_ctlferiado_tipoFecha_0').click(function(){
        $('#fechaFin_ctlferiado').hide("slow");
        $('#fechaInicio_ctlferiado').show("slow");
    });
    
    $('#minsal_sifdabundle_ctlferiado_tipoFecha_1').click(function(){
        $('#fechaInicio_ctlferiado').show("slow");
        $('#fechaFin_ctlferiado').show("slow");        
    });
    
});

function asignarFechasFeriadas(){
     
   var texto=$("#minsal_sifdabundle_ctlferiado_fechaInicio").val();
   var lista=document.getElementById('minsal_sifdabundle_ctlferiado_fechaFestiva');
   
   if(texto !=="")
        buscarFechaFeriada(texto,lista);
   else
       alert('Debe seleccionar una fecha');
//   if(texto !==""){
//       lista.options.add(new Option(texto));
//        $("#txt_fechaEspecifica").val("");
//   }
//   else
//       alert('Debe seleccionar una fecha');
//    
}

function eliminarFecha(){
    
    var lista=document.getElementById("minsal_sifdabundle_ctlferiado_fechaFestiva");
     if (lista.options.selectedIndex !== -1)
            lista.options[lista.selectedIndex]=null;
      else
          alert('No hay fechas para remover');
    
}

function buscarFechaFeriada(texto,lista){
    
    var num = lista.length;
   
    if(num === 0){
        lista.options.add(new Option(texto));
        $("#minsal_sifdabundle_ctlferiado_fechaInicio").val("");
     }
    else
    {
      for(var i=0;i<lista.length; i++){
          var itemLs= lista[i];
          
          if(texto===(itemLs.value))
            {
              alert('La Fecha ya fue elegida');
              break;
            }
          else
            {
                lista.options.add(new Option(texto));
                $("#minsal_sifdabundle_ctlferiado_fechaInicio").val("");
            }
      }
    }     
    
//    for(var i=0;i<sel.length; i++);
    
}