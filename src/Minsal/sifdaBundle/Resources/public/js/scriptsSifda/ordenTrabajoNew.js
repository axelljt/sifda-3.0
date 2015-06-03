
$(document).ready(function (){
    document.getElementById("minsal_sifdabundle_sifdaordentrabajo_idEtapa").value="";
    
        
});

function buscarFechasFeriado(ruta, anio, mes, dia){
    var fecha = new Date();
    var anio = fecha.getFullYear();
    
//    alert(anio);
//    alert(mes);
//    alert(dia);
    $.post(ruta,
        {
            anio: anio
            },function(data){
                console.log( data );
                calendarAsuetoOrden('minsal_sifdabundle_sifdaordentrabajo_fechaFinalizacion', anio, mes, dia, data.query)
            }, "json"
        );
                
}

function buscarEtapa(ordenActualId, ruta1, ruta2){
    
    var idEtapa = $("#minsal_sifdabundle_sifdaordentrabajo_idEtapa").val();

    if(idEtapa !="")
    {
        $.post(
        ruta1,
          { 
            idEtapa:idEtapa,
            ordenActualId:ordenActualId
          }
          , function( data ) {
            console.log( data );
            $("#minsal_sifdabundle_sifdaordentrabajo_idSubEtapa").html(data);

         }, "json");
    }
    else 
    {
        $.post(
        ruta2,
            function( data ) {
            console.log( data );
                $("#minsal_sifdabundle_sifdaordentrabajo_idSubEtapa").html(data);

         }, "json");
    }
}//Fin de buscarEtapa

      