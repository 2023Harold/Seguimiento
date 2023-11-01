//*********Funcion para Mostrar  y Ocultar elementos e la vez*********//
function MostrarOcultar(objMostrar, objOcultar) {
    $(objMostrar).show();
    $(objOcultar).hide();
}
//********Funcion para Mostrar elementos Masivamente***********//
function Mostrar(objMostrar=[]) {
    if (objMostrar.length > 0)
    for(var i=0; i<objMostrar.length; i++)
    $(objMostrar[i]).show();
}
//*******Funcion para Ocultar elementos Masivamente**********//
function Ocultar(objOcultar=[]) {
    if (objOcultar.length > 0)
    for (var i=0; i<objOcultar.length; i++)
    $(objOcultar[i]).hide();
}
//*******Funcion para marcado de secciones opcionales*********////
function updateAutorizado(campoid, event) {
    var autorizado = null;
    if(event.target.checked == true) {
        autorizado = event.target.value;
    }
    $.ajax({
        method : "PUT",
        url: autorizadoUrl,
        data : {
            _token: autorizadoToken,
            id: campoid,
            autorizado: autorizado
        }
    }).done(function(data) {
    });
}
//******Agregar el selector de calendario para campos de tipo fecha**********//
function setDatepicker(objetosid=[], minDate='01-01-1900', maxDate=Date.now()) {
    for(var i=0;i<objetosid.length;i++) {
        $(objetosid[i]).daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minDate: new Date(minDate),
            maxDate: new Date(maxDate),
            locale: {
                format: 'YYYY-MM-DD',
                firstDay: 0,
                daysOfWeek: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            }
        });
    }
}
$(document).ready(function() {   
   //visualizar el primer error de las validacion de carga de un archivo//
   $('span').attr('tabindex','-1');
   $(".help-block").first().focus();   
});

//****Funcion para enfocar el primer error en los formularios ***//
$("[type=submit]").on('click',function() { 
    $(document).ready(function() { 
         $('span').attr('tabindex','-1');
         var PrimerError=0;

         $('.is-invalid').each(function() {
             if(PrimerError<1){
                 PrimerError++;
                 $(this).focus();
             }
         });
         $('.invalid-feedback').each(function() {             
             if(PrimerError<1 && Boolean($(this).text())){                
                PrimerError++;
                $(this).focus();
             }
         }); 
         /*si no se encontraron errores y se tiene la propiedad de data link verificar_spiner se muestra la animación*/
         if(PrimerError==0){
            $('button[data-link="verificar_spinner"]').each(function(){
                $('#divSpinner').show(); 
            });             
         }    
    });   
 });  

//****Funcion para obtener la url actual y devolver una ruta  ****//
function Obtener_URLActual(vista, metodo, id) {
    url_actual = location.pathname;
    url_actual = url_actual.substr(0, url_actual.search(vista));
    return url_actual + vista + "/"+ metodo +"/" + id;
}
