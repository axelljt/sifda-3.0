
$(document).ready(function (){
    document.getElementById('minsal_sifdabundle_sifdarutaciclovida_descripcion').value="";
    document.getElementById('minsal_sifdabundle_sifdarutaciclovida_referencia').value="";
    document.getElementById('minsal_sifdabundle_sifdarutaciclovida_jerarquia').value="";
    document.getElementById('minsal_sifdabundle_sifdarutaciclovida_peso').value="";
    document.getElementById('minsal_sifdabundle_sifdarutaciclovida_numEtapas').value="";
    $("#minsal_sifdabundle_sifdarutaciclovida_submit").prop('disabled', true);
});



function setEtapaServicio(){
    var descripcion = $("#minsal_sifdabundle_sifdarutaciclovida_descripcion").val();
    var referencia = $("#minsal_sifdabundle_sifdarutaciclovida_referencia").val();
    var jerarquia = $("#minsal_sifdabundle_sifdarutaciclovida_jerarquia").val();
    var peso = $("#minsal_sifdabundle_sifdarutaciclovida_peso").val();
    var lista = document.getElementById('minsal_sifdabundle_sifdarutaciclovida_etapaServicio');

    if($("#minsal_sifdabundle_sifdarutaciclovida_numEtapas").val() !=="") {
        if($("#minsal_sifdabundle_sifdarutaciclovida_numEtapas").val() > 0){
            if($("#minsal_sifdabundle_sifdarutaciclovida_descripcion").val() !=="" 
                    && $("#minsal_sifdabundle_sifdarutaciclovida_jerarquia").val() !=="" 
                    && $("#minsal_sifdabundle_sifdarutaciclovida_peso").val() !=="")
                {
                    if($("#minsal_sifdabundle_sifdarutaciclovida_peso").val() > 0
                            && $("#minsal_sifdabundle_sifdarutaciclovida_peso").val() < 100
                            || parseInt($("#minsal_sifdabundle_sifdarutaciclovida_numEtapas").val()) === 1) {
                        var texto = jerarquia+' - '+descripcion+' - '+peso;
                        if($("#minsal_sifdabundle_sifdarutaciclovida_referencia").val() !==""){
                            texto+= ' - '+referencia;
                        } 
                        colocarEtapaServicio(texto, lista, descripcion, peso);
                    }
                    else{
                        alert('El porcentaje de la etapa debe ser mayor que 0 y menor a 100');
                    }
                } 
            else
               alert('Debe completar los campos descripcion, jerarquia y peso');
        }
        else
            alert('El numero de etapas debe ser mayor que 0');
    }
    else
        alert('Debe introducir el numero de etapas del tipo de servicio');
}// Fin de setEtapaServicio()

var etapa = 1;

function colocarEtapaServicio(texto, lista, descripcion, peso){
    var NumEtapas = $("#minsal_sifdabundle_sifdarutaciclovida_numEtapas").val();
    var num = lista.length;
    var aux=0;
    var porcentaje = 0;
    if(num === 0){
        if(etapa === NumEtapas -1){
            $("#minsal_sifdabundle_sifdarutaciclovida_peso").prop('disabled', true);
            $("#minsal_sifdabundle_sifdarutaciclovida_peso").val(100 - peso);
        }
        else if(num === NumEtapas - 1)
        { 
            $("#minsal_sifdabundle_sifdarutaciclovida_peso").val("");
            $("#minsal_sifdabundle_sifdarutaciclovida_descripcion").prop('disabled', true);
            $("#minsal_sifdabundle_sifdarutaciclovida_referencia").prop('disabled', true);
            $("#minsal_sifdabundle_sifdarutaciclovida_jerarquia").prop('disabled', true);
            $("#minsal_sifdabundle_sifdarutaciclovida_peso").prop('disabled', true);
            $("#minsal_sifdabundle_sifdarutaciclovida_submit").prop('disabled', false);
        }
        else{
            $("#minsal_sifdabundle_sifdarutaciclovida_peso").val("");          
        }
        lista.options.add(new Option(texto));
        $("#minsal_sifdabundle_sifdarutaciclovida_numEtapas").prop('disabled', true);
        $("#minsal_sifdabundle_sifdarutaciclovida_descripcion").val("");
        $("#minsal_sifdabundle_sifdarutaciclovida_referencia").val("");
        $("#minsal_sifdabundle_sifdarutaciclovida_jerarquia").val("");
        etapa = etapa + 1;
     }
    else
    {
        for(var i=0;i<lista.length; i++){
            var depest = lista[i].text.split(' - ');
            var itemLs = depest[0];
            
            porcentaje = porcentaje + parseInt(depest[2]);
            
            if(descripcion === (itemLs))
            {
                alert('La etapa ya fue introducida');
                aux=1;
                break;
            }
        }
        
        porcentaje = porcentaje + parseInt(peso);
        if(porcentaje <= 100){            
            if(aux===0){
                if(etapa === parseInt(NumEtapas) -1 && (100 - porcentaje) !== 0){
                    var uEtapa = 100 - porcentaje;
                    var por = document.getElementById('minsal_sifdabundle_sifdarutaciclovida_peso');
                    por.value = uEtapa;
                    lista.options.add(new Option(texto));
                    $("#minsal_sifdabundle_sifdarutaciclovida_descripcion").val("");
                    $("#minsal_sifdabundle_sifdarutaciclovida_referencia").val("");
                    $("#minsal_sifdabundle_sifdarutaciclovida_jerarquia").val("");
                    $("#minsal_sifdabundle_sifdarutaciclovida_peso").prop('disabled', true);
                    etapa = etapa + 1;
                } else if(num === NumEtapas - 1)
                {
                    lista.options.add(new Option(texto));
                    $("#minsal_sifdabundle_sifdarutaciclovida_peso").val("");   
                    $("#minsal_sifdabundle_sifdarutaciclovida_descripcion").val("");
                    $("#minsal_sifdabundle_sifdarutaciclovida_referencia").val("");
                    $("#minsal_sifdabundle_sifdarutaciclovida_jerarquia").val("");
                    $("#minsal_sifdabundle_sifdarutaciclovida_descripcion").prop('disabled', true);
                    $("#minsal_sifdabundle_sifdarutaciclovida_referencia").prop('disabled', true);
                    $("#minsal_sifdabundle_sifdarutaciclovida_jerarquia").prop('disabled', true);
                    $("#minsal_sifdabundle_sifdarutaciclovida_peso").prop('disabled', true);
                    $("#minsal_sifdabundle_sifdarutaciclovida_submit").prop('disabled', false);
                    etapa = etapa + 1;
                } 
                else if((100 - porcentaje) !== 0){
                    lista.options.add(new Option(texto));
                    $("#minsal_sifdabundle_sifdarutaciclovida_peso").val("");
                    $("#minsal_sifdabundle_sifdarutaciclovida_descripcion").val("");
                    $("#minsal_sifdabundle_sifdarutaciclovida_referencia").val("");
                    $("#minsal_sifdabundle_sifdarutaciclovida_jerarquia").val("");
                    etapa = etapa + 1;
                }
                else
                    alert('El porcentaje total del tipo de servicio debe ser igual a 100');                
            }
                
        } else{
                alert('El porcentaje total del tipo de servicio debe ser igual a 100');
            }        
    }         
}//  Fin de colocarEtapaServicio

function eliminarEtapaServicio(){
    
    var lista = document.getElementById("minsal_sifdabundle_sifdarutaciclovida_etapaServicio");
    if (lista.options.selectedIndex !== -1)
        lista.options[lista.selectedIndex]=null;
    else
        alert('No hay elemento seleccionado para remover');
    
}//  Fin de eliminarEtapaServicio

function limpiarListbox(){
     var lista = document.getElementById("minsal_sifdabundle_sifdarutaciclovida_etapaServicio");
     if (lista.options.length>0){
         
         for(var i=lista.options.length-1;i>=0;i--){
             lista.remove(i);
         }
         
         $("#minsal_sifdabundle_sifdarutaciclovida_descripcion").prop('disabled', false);
         $("#minsal_sifdabundle_sifdarutaciclovida_referencia").prop('disabled', false);
         $("#minsal_sifdabundle_sifdarutaciclovida_jerarquia").prop('disabled', false);
         $("#minsal_sifdabundle_sifdarutaciclovida_peso").prop('disabled', false);

     }
               
      else
          alert('No hay elementos para remover');    
}// Fin de limpiarListbox

function verificarNumEtapas(){
    var NumEtapas = $("#minsal_sifdabundle_sifdarutaciclovida_numEtapas").val();
    if(NumEtapas !== ""){
        var valor = validarCampo(NumEtapas); 

        if (valor !== ""){ 
            if(valor === 1){
                $("#minsal_sifdabundle_sifdarutaciclovida_peso").val(100);
                $("#minsal_sifdabundle_sifdarutaciclovida_peso").prop('disabled', true);
            }
            else {
                $("#minsal_sifdabundle_sifdarutaciclovida_peso").val("");
                $("#minsal_sifdabundle_sifdarutaciclovida_peso").prop('disabled', false);
            }
        }
        else{
            alert ("Debe introducir un valor entero!"); 
            $("#minsal_sifdabundle_sifdarutaciclovida_numEtapas").select();
            $("#minsal_sifdabundle_sifdarutaciclovida_numEtapas").focus();
        }    
    }    
}// Fin de verificarNumEtapas

function validarCampo(valor){ 

    valor = parseInt(valor)

    if (isNaN(valor)) { 
       return "" 
    }else{ 
       return valor 
    } 
}// Fin de validarCampo

function getEtapasServicioIngresados(){
    
    $.blockUI({ message: "Espere un instante" }); 
    var lista=document.getElementById("minsal_sifdabundle_sifdarutaciclovida_etapaServicio");
    var etapaServicio = new Array();
    
   if(lista.length>0)
    {
        for(var i=0; i<lista.length; i++)  {
        etapaServicio[i] = lista.options[i].text;
        }   
        
        for ( var i = 0, l = lista.options.length, o; i < l; i++ )
        {
            o = lista.options[i];
            if ( etapaServicio.indexOf( o.text ) != -1 )
            {
                o.selected = true;                
            }
        } 
        return false; 
    } 
    else {
          return false;  
    }
}//Fin de getEtapasServicioIngresados

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