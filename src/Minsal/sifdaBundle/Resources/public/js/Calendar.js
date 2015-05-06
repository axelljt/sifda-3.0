
function calendarNormal(id_txt1){
    
    $.datepicker.regional['es'] = {
			 closeText: 'Cerrar',
			 prevText: '<Ant',
			 nextText: 'Sig>',
			 currentText: 'Hoy',
			 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
			 dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi�rcoles', 'Jueves', 'Viernes', 'S�bado'],
			 dayNamesShort: ['Dom','Lun','Mar','Mi�','Juv','Vie','S�b'],
			 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S�'],
			 weekHeader: 'Sm',
			 showButtonPanel:false,
			 showAnim:'show',
                         changeMonth: true,
			 changeYear: true,
//                         minDate: new Date(1980, 11, 1), 
//                         maxDate: "+0D",
                         dateFormat: 'yy-mm-dd',
			 firstDay: 1,
			 isRTL: false,
			 showMonthAfterYear: false,
			 yearSuffix: ''
			 
			 };
			$.datepicker.setDefaults($.datepicker.regional['es']);
			$(function() {
				$( "#"+id_txt1 ).datepicker(
					
					);
                                
				}); 
}//Fin de Fecha normal


//Fecha Enlazada
function calendarEnlazado(id_txt1,id_txt2){
    
    $.datepicker.regional['es'] = {
			 closeText: 'Cerrar',
			 prevText: '<Ant',
			 nextText: 'Sig>',
			 currentText: 'Hoy',
			 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
			 dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi�rcoles', 'Jueves', 'Viernes', 'S�bado'],
			 dayNamesShort: ['Dom','Lun','Mar','Mi�','Juv','Vie','S�b'],
			 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S�'],
			 weekHeader: 'Sm',
			 showButtonPanel:false,
			 showAnim:'show',
                         changeMonth: true,
			 changeYear: true,
                         minDate: new Date(2015, 1, 1), 
//                         maxDate: "1D",
                         dateFormat: 'yy-mm-dd',
			 firstDay: 1,
			 isRTL: false,
			 showMonthAfterYear: false,
			 			                         
                         onClose: function (selectedDate) {
                         
                          $("#"+id_txt1).datepicker("option", "maxDate", selectedDate);
                          $("#"+id_txt2).datepicker("option", "minDate", selectedDate);
                          
                         },
                         
                         yearSuffix: ''
                         
			 };
			$.datepicker.setDefaults($.datepicker.regional['es']);
			$(function() {
				$( "#"+id_txt1 ).datepicker(
					
					);
                                
                                $( "#"+id_txt2 ).datepicker(
					
					);
                                
                                
				});   
} //Fin de Fecha Enlazada
