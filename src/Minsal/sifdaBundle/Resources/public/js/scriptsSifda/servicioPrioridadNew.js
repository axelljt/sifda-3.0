
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
    
    var establecimiento=$("#minsal_sifdabundle_sifdaservicioprioridad_establecimiento").val();
    var dependencia=$("#minsal_sifdabundle_sifdaservicioprioridad_dependencia").val();
    var prioridad=$("#minsal_sifdabundle_sifdaservicioprioridad_idPrioridad").val();
    var lista=document.getElementById('minsal_sifdabundle_sifdaservicioprioridad_servicioPrioridad');
    
    
    if(establecimiento !=="" && dependencia !=="" && prioridad !=="")
        {
            var texto= establecimiento+' - '+dependencia+' - '+prioridad;
            //buscarRangoFechaFeriada(texto,lista);
        } 
    else
       alert('Debe seleccionar establecimiento, dependencia y prioridad');
   
}// Fin de asignarPrioridad()

      