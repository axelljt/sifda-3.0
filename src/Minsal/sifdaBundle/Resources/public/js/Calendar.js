
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
			 yearSuffix: '',
                        
			 
			 };
			$.datepicker.setDefaults($.datepicker.regional['es']);
			$(function() {
				$( "#"+id_txt1 ).datepicker(
					
					);
                                
				}); 
}//Fin de Fecha normal

//Calendario anio actual
function calendarAnioActual(id_txt1){
    
    var fecha = new Date();
    var anio = fecha.getFullYear();
    
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
                         minDate: new Date(anio, 0, 1), 
                         maxDate: new Date(anio, 11, 31), 
                         dateFormat: 'yy-mm-dd',
			 firstDay: 1,
			 isRTL: false,
			 showMonthAfterYear: false,
			 yearSuffix: '',
                        
			 
			 };
			$.datepicker.setDefaults($.datepicker.regional['es']);
			$(function() {
				$( "#"+id_txt1 ).datepicker(
					
					);
                                
				}); 
}//Fin de Calendario anio actual

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
                         minDate: new Date(2015, 0, 1), 
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
 
} //Fin de Calendario Enlazado



/*Calendario Enlazado Vacaciones*/                               
 
function calendarEnlazadoVacaciones(id_txt1,id_txt2, data){
    
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
         minDate: new Date(2015, 0, 1), 
//                         maxDate: "1D",
         dateFormat: 'yy-mm-dd',
         firstDay: 1,
         isRTL: false,
         showMonthAfterYear: false,

         onClose: function (selectedDate) {

          $("#"+id_txt1).datepicker("option", "maxDate", selectedDate);
          $("#"+id_txt2).datepicker("option", "minDate", selectedDate);

         },

         yearSuffix: '',
         beforeShowDay: DisableDays
                                                  
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    $(function() {
        $( "#"+id_txt1 ).datepicker(

                );

        $( "#"+id_txt2 ).datepicker(

                );
    });
                                
    var RangeDates = new Array();
    var RangeDatesIsDisable = true;
     
    for (var i = 0; i < data.length; i++) {
        RangeDates[i]=data[i].fecha; 
    }
    
    function DisableDays(date) {

        var isd = RangeDatesIsDisable;
        var rd = RangeDates;
        
       // document.write(rd);
        var m = date.getMonth();
        var d = date.getDate();
        var y = date.getFullYear();
        for (i = 0; i < rd.length; i++) {
            var ds = rd[i].split(',');
            var di, df;
            var m1, d1, y1, m2, d2, y2;

            if (ds.length == 1) {
                di = ds[0].split('-');
                y1 = parseInt(di[0]);
                m1 = parseInt(di[1]);
                d1 = parseInt(di[2]);

                if (y1 == y && m1 == (m + 1) && d1 == d) return [!isd];
            } else if (ds.length > 1) {
                di = ds[0].split('-');
                df = ds[1].split('-');
                y1 = parseInt(di[0]);
                m1 = parseInt(di[1]);
                d1 = parseInt(di[2]);
                y2 = parseInt(df[0]);
                m2 = parseInt(df[1]);
                d2 = parseInt(df[2]);

                if (y1 >= y || y <= y2) {
                    if ((m + 1) >= m1 && (m + 1) <= m2) {
                        if (m1 == m2) {
                            if (d >= d1 && d <= d2) return [!isd];
                        } else if (m1 == (m + 1)) {
                            if (d >= d1) return [!isd];
                        } else if (m2 == (m + 1)) {
                            if (d <= d2) return [!isd];
                        } else return [!isd];
                    }
                }
            }
        }
         var weekenddate = $.datepicker.noWeekends(date);
    return weekenddate;
    
   
}
    
} //Fin de Fecha Enlazada Vacaciones

function calendarAsuetoOrden(id_txt1, data){
    
    var fecha = new Date();
    
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
                         minDate: new Date(fecha.getFullYear(), fecha.getMonth() - 1, fecha.getDate()), 
//                         maxDate: "+0D",
                         dateFormat: 'yy-mm-dd',
			 firstDay: 1,
			 isRTL: false,
			 showMonthAfterYear: false,
			 yearSuffix: '',
                         beforeShowDay: DisableDays
			 
			 };
			$.datepicker.setDefaults($.datepicker.regional['es']);
			$(function() {
				$( "#"+id_txt1 ).datepicker(
					
					);
                                
				}); 

    var RangeDates = new Array();
    var RangeDatesIsDisable = true;
     
    for (var i = 0; i < data.length; i++) {
        RangeDates[i]=data[i].fecha; 
    }
    
    function DisableDays(date) {

        var isd = RangeDatesIsDisable;
        var rd = RangeDates;
        
       // document.write(rd);
        var m = date.getMonth();
        var d = date.getDate();
        var y = date.getFullYear();
        for (i = 0; i < rd.length; i++) {
            var ds = rd[i].split(',');
            var di, df;
            var m1, d1, y1, m2, d2, y2;

            if (ds.length == 1) {
                di = ds[0].split('-');
                y1 = parseInt(di[0]);
                m1 = parseInt(di[1]);
                d1 = parseInt(di[2]);

                if (y1 == y && m1 == (m + 1) && d1 == d) return [!isd];
            } else if (ds.length > 1) {
                di = ds[0].split('-');
                df = ds[1].split('-');
                y1 = parseInt(di[0]);
                m1 = parseInt(di[1]);
                d1 = parseInt(di[2]);
                y2 = parseInt(df[0]);
                m2 = parseInt(df[1]);
                d2 = parseInt(df[2]);

                if (y1 >= y || y <= y2) {
                    if ((m + 1) >= m1 && (m + 1) <= m2) {
                        if (m1 == m2) {
                            if (d >= d1 && d <= d2) return [!isd];
                        } else if (m1 == (m + 1)) {
                            if (d >= d1) return [!isd];
                        } else if (m2 == (m + 1)) {
                            if (d <= d2) return [!isd];
                        } else return [!isd];
                    }
                }
            }
        }
         var weekenddate = $.datepicker.noWeekends(date);
        return weekenddate;
    }   
}//Fin de calendar Asueto Orden
