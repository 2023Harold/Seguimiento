$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(".control-label").each(function() {
        var texto = $(this).text();
        if (texto.includes('*')) { 
            var textohtml = texto.replace("*", "<span class='text-danger'>*</span>", "gi");            
            $(this).html(textohtml);
        }        
    });

    $(".form-label").each(function() {
        var texto = $(this).text();       
        if (texto.includes('*')) { 
            var textohtml = texto.replace("*", "<span class='text-danger'>*</span><small class='mx-2'>", "gi");
            var textohtml2 = textohtml + "</small>";
            $(this).html(textohtml2);
        }        
    });

    (() => {
        'use strict'
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')
            // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })();

    $('.return-parent').click(function(event) {
        parent.$.fn.colorbox.close();
        event.preventDefault();
    });

    $('.popupSinLocation').colorbox({
        width: "900px",
        height: "600px",
        iframe: true,
        //scrolling:true,
        overlayClose: true, //true permite cerrar el popup al dar clic en el espacio negro o esc
        onClosed: function() {
            // se activa ésta línea, en caso de que se requiera recargar la ventana padre
            //window.location = window.location;
        }
    });

    $('.popupLocation').colorbox({
        width: "900px",
        height: "600px",
        iframe: true,
        overlayClose: true,
        onClosed: function() {
            location.reload(true);
        }
    });

    $('.popupConLocation').colorbox({
        width: "900px",
        height: "600px",
        iframe: false,
        overlayClose: false,
        fastIframe : true,
        onClosed: function() {
            location.reload(true);
            //jQuery().colorbox.close();
            //$.colorbox.remove();
        }
    });

    $('.popupLocationSiniFrame').colorbox({
        width: "900px",
        height: "600px",
        iframe: false,
        overlayClose: false,
        fastIframe : true,
        onClosed: function() {
            location.reload(true);
            //jQuery().colorbox.close();
            //$.colorbox.remove();
        }
    });

    //$.fn.fileinputBsVersion = "5.1.2";
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-grey',
        radioClass: 'iradio_square-grey',
    });

    $(document).on("keyup", ".uppercase", function (e) {
        if (e.which == 13 || e.which >= 97 && e.which <= 122) {
            var newKey = e.which - 32;
            e.keyCode = newKey;
            e.charCode = newKey;
        }
        $(this).val(($(this).val()).toUpperCase());
    });

    $(document).on("keypress", ".sololetras", function (e) {
        var regex = new RegExp("^[a-zA-Z ]*$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (e.which == 13 || regex.test(str)) {
             return true;
        }
        e.preventDefault();
        return false;
    });

    $(document).on("keypress", ".sololetrasynum", function (e) {
        var regex = new RegExp("^[a-zA-Z0-9]*$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (e.which == 13 || regex.test(str)) {
             return true;
        }
        e.preventDefault();
        return false;
    });

    $(document).on("keypress", ".numeros", function (event) {
        return enteros(event, this);
    });

    function enteros(evt, element) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 13 && (charCode < 48 || charCode > 57)){
            return false;
        }else{
            return true;
        }
    }

    $(document).on("keypress", ".decimal", function (event) {
        return decimal(event, this);
    });

    function decimal(evt, element) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode != 13 && (charCode != 46 || $(element).val().indexOf('.') != -1) &&
            (charCode < 48 || charCode > 57)){
            return false;
        }else{
            return true;
        }
    }

    $("input:text").each(function() {
        $(this).parent().addClass('validacion');
    });
    $("textarea").each(function() {
        $(this).parent().addClass('validacion');
    });
    $("div.checkbox").each(function() {
        $(this).parent().addClass('validacion');
    });
    $("span.select2").each(function() {
        $(this).parent().addClass('validacion');
    });
    $("label.radio-inline").each(function() {
        $(this).parent().addClass('validacion');
    });
    $("div.radio").each(function() {
        $(this).parent().addClass('validacion');
    });

    //$(".icheckbox_square-grey").click(function() {
    $('.icheckbox_square-grey').on('ifChanged', function(event) {
        let check = $(this).find("input");
        $('#' + check.attr('name') + '-error').html('');
    });

    $('.iradio_square-grey').on('ifChanged', function(event) {
        let check = $(this).find("input");
        $('#' + check.attr('name') + '-error').html('');
    });

    $("select").on('change', function(e, e2) {
        let spanid = $(this).attr('id');
        /*let spanid = $(this).find("span").find('span').find('span').attr('id');
        let id = spanid.replace("select2-", "").replace("-container", "");*/
        $('#' + spanid + '-error').html('');
    });

    $('input[type="file"]').on('change', function() {
        let spanid = $(this).attr('id');
        let id = spanid.replace("-upload", "");
        $('#' + id).next("span").text("");
        $('#' + id+'-error').text("");
        return false;
    });

    $('.rechazado').on('ifChanged', function(event) {
        if (event.target.value.indexOf('Rechaz') != -1) {
            $('#justificacion').show();
            $('#enviar').hide();
        } else {
            $('#justificacion').hide();
            $('#enviar').show();
        }
    });

    $(document).on('click', '.submit', function(e) {
        let btn = $(this);
        let lbl = $(this).text();
        var isValid = $(e.target).parents('form').valid();
        if (isValid) {
            $(this).html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Enviando...');
            $(this).prop('disabled', true);
            $(e.target).parents('form').submit();
            setTimeout(function () {
                let errors = $(e.target).parents('form').find('span.help-block').text();
                if (errors!="") {
                    $(btn).text(lbl);
                    $(btn).prop('disabled', false);
                }
            }, 1000);
        }
        e.preventDefault();
    });

    $(document).on('click', '.btn-submit', function (e) {
        $(this).addClass('disabled');
        $(this).html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Enviando...');
    });

    $(document).on("keypress", ".enviar-firma", function (e) {
        if(e.which == 13 && e.shiftKey == false){
            $("#btn-firma").trigger("click");
            return false;
        }
    });

    $('.numeric').each(function(index, value){
        new AutoNumeric(this, {
            currencySymbol: '$',
            maximumValue: '99999999999.99',
            minimumValue: '0.00',
            unformatOnSubmit: true,
        });
    });

    $("input:file").each(function() {
        var id = '#' + $(this).attr("id");
        var fileSize = $(this).attr("data-maxFileSize") ? $(this).attr("data-maxFileSize") : 20;
        var fileExtension = $(this).attr("data-allowedFileExtensions") ? $(this).attr("data-allowedFileExtensions").split(',') : ["pdf"];
        var placeholder = $(this).attr("placeholder") ? $(this).attr("placeholder") : 'Seleccionar archivo ...';
        if (!$(this).hasClass("key")) {
            $(id).fileinput({
                maxFileSize: (fileSize * 1024), // maxFileSize in KB
                allowedFileExtensions: fileExtension,
                msgPlaceholder: placeholder,
                showPreview: false, // Valor requerido cuando se usa uploadUrl
                showUpload: false,
                showCancel: false,
                msgUploadEmpty: 'El archivo seleccionado no es válido para su envío. Revise que el tamaño y tipo de archivo coincida con lo permitido.',
                uploadUrl: uploadFile,
                language: 'es',
                progressClass: 'progress-bar progress-bar-primary progress-bar-striped active',
                progressCompleteClass: 'progress-bar progress-bar-primary',
                progressErrorClass: 'progress-bar progress-bar-danger',
                deleteUrl: removeFile,
                showRemove: false, //true muestra el botón de quitar
                autoReplace: true,
                maxFileCount: 1,
                elErrorContainer: id + "-errorBlock",
                enableResumableUpload: false,
                initialPreviewAsData: true,
                uploadAsync: true,
                overwriteInitial: true,
            }).on("filebatchselected", function(event, files) {
                $(id).fileinput("upload");
            }).on('fileuploaded', function(event, data, previewId, index) {
                var form = data.form,
                    files = data.files,
                    extra = data.extra,
                    response = data.response.initialPreviewConfig,
                    reader = data.reader;
                $('.kv-upload-progress').fadeOut(1500);
                $(id + '-span').show();
                $(id + '-upload-link').attr({ href: response[0].downloadUrl, style: "display:show" });
                $(id + '-delete-link').attr({ style: "display:show" });
                $(id.replace('-upload', '')).val(response[0].filename);
            }).on('fileclear', function() {
                var aborted = window.confirm('¿Eliminar el archivo?');
                if (aborted) {
                    $(id + '-link').attr({ href: '', style: "display:none" });
                };
                return aborted;
            });
        }else{
            $(id).fileinput({
                maxFileSize: (fileSize * 1024), // maxFileSize in KB
                allowedFileExtensions: fileExtension,
                msgPlaceholder: placeholder,
                showPreview: false, // Valor requerido cuando se usa uploadUrl
                showUpload: false,
                showCancel: false,
                msgUploadEmpty: 'El archivo seleccionado no es válido para su envío. Revise que el tamaño y tipo de archivo coincida con lo permitido.',
                uploadUrl: uploadFile,
                language: 'es',
                progressClass: 'progress-bar progress-bar-primary progress-bar-striped active',
                progressCompleteClass: 'progress-bar progress-bar-primary',
                progressErrorClass: 'progress-bar progress-bar-danger',
                deleteUrl: removeFile,
                showRemove: false, //true muestra el botón de quitar
                autoReplace: true,
                maxFileCount: 1,
                elErrorContainer: id + "-errorBlock",
                enableResumableUpload: false,
                initialPreviewAsData: true,
                uploadAsync: true,
                overwriteInitial: true,
            }).on("filebatchselected", function(event, files) {
                $(id.replace('-upload', '')).val('archivo_cargado');
            }).on('fileclear', function() {
                $(id.replace('-upload', '')).val();
            });
        }
    });

    $("#password").val('12121212Qw.');

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
});


function agregarDiasHabiles(fecha, dias) {
    let pickedDate = fecha;
    let date = new Date(pickedDate);
    date.setDate(date.getDate() + 2);
    for (let index = 1; index < dias; index++) {
        date.setDate(date.getDate() + 1);
        if (date.getDay() == 6 || date.getDay() == 0)
            index--;
    }
    var dd = String(date.getDate()).padStart(2, '0');
    var mm = String(date.getMonth() + 1).padStart(2, '0');
    var yyyy = date.getFullYear();
    fechaFinal = yyyy + '-' + mm + '-' + dd;
    return fechaFinal;
}