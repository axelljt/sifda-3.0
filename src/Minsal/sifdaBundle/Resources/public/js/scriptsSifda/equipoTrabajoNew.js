/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function (){
    $("#equipoTrabajo").hide("slow");
});

function buscarEquipoTrabajo() {
    
    var responsable = $("#minsal_sifdabundle_sifdaequipotrabajo_idEmpleado").val();
    var ruta = "../buscarequipoTrabajo";  
    alert(responsable);
    if(responsable !="")
    {
        $.post(
        ruta,
          { 
            responsable:responsable,
          }
          , function( data ) {
            console.log( data );
            $("#minsal_sifdabundle_sifdaequipotrabajo_equipoTrabajo").html(data.query);
            $("#equipoTrabajo").show("slow");

         }, "json");
        
        
    }
    else{
        $("#equipoTrabajo").hide("slow");
    }
}