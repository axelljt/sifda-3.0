
$(document).ready(function (){
    $("#equipoTrabajo").hide("slow");
});

function buscarEquipoTrabajo() {
    
    var responsable = $("#minsal_sifdabundle_sifdaequipotrabajo_idEmpleado").val();

    if(responsable !="")
    {
// $(function(){ 
        $("#minsal_sifdabundle_sifdaequipotrabajo_equipoTrabajo input[type=checkbox]").each(function(){
            $(this).removeAttr('disabled');
            $(this).prop('checked', false);
        });
//  });

        var update_empleado = function () {
            if ($("#minsal_sifdabundle_sifdaequipotrabajo_idEmpleado_"+responsable).is(":checked")) {
                $('#minsal_sifdabundle_sifdaequipotrabajo_equipoTrabajo_'+responsable).prop('disabled', false);
            }
            else {
                $('#minsal_sifdabundle_sifdaequipotrabajo_equipoTrabajo_'+responsable).prop('disabled', 'disabled');
            }
        };
        
        $(update_empleado);
        $("#minsal_sifdabundle_sifdaequipotrabajo_idEmpleado_"+responsable).change(update_empleado);        
        $("#equipoTrabajo").show("slow");
    }
    else{
        $("#equipoTrabajo").hide("slow");
    }
}