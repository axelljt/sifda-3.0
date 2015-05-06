
$(document).ready(function (){
    
    document.getElementById("pdf").checked=false;
    document.getElementById("excel").checked=false;
    document.getElementById("grafico").checked=false;

    document.getElementById("cmb1").value=0;
    document.getElementById('txt_fechaInicio').value="";
    document.getElementById('txt_fechaFin').value="";
    
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
    
    if(pdf!==false)
       alert('pdf:'+pdf);
    
    if(excel!== false)
        window.open('/reports/phpexcel/solicitudesFinalizadasExcel.php?fi='+'txt_fechaInicio'+'&ff='+'txt_fechaFin'+'&tp='+1+'&user='+'cmartin');
//       alert('excel:'+excel);
    
    if(graf!== false)   
       alert('graf:'+graf);
}