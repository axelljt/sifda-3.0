
//$(document).ready(function() { 
//	alert('El documento está preparado'); 
//});

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
				$( "#minsal_sifdabundle_sifdasolicitudservicio_fechaRecepcion" ).datepicker(
					
					);
                                
                                $( "#minsal_sifdabundle_sifdasolicitudservicio_fechaRequiere" ).datepicker(
					
					);
				}); 