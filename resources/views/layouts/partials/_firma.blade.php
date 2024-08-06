<script>
    document.getElementById('certificate_file-upload').addEventListener('change', handleParsingCertFile, false);
    document.getElementById('privkey_file-upload').addEventListener('change', handleParsingPrivKeyFile, false);

    function ConfirmFirma() {
        var status = $("#form").valid();
        if(status){
            if (confirm("Desea continuar con el firmado de la constancia de movimiento?"))
               firmar();
			//$("#form").submit();
        }else{
            return ;
        }
    }

    //Se envia un xml a firma
    function firmar() {
        $('#campos').hide();
        $('#body_loader').show();
        $('#btn-firma').html('<span class="spinner-border spin-x" role="status" aria-hidden="true"></span> Firmando...');
        $('#btn-firma').prop('disabled', true);
        archivobase64 = $('#archivo_firmar').val();
        var token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ route('firmar') }}",
            dataType: "json",
            type: "POST",
            data: {
                "id": 1,
                "certificado": cer64,
                "archivo": archivobase64,
                _token: token,
            },
            success: function(respuesta) {
                resultado = respuesta;
                hash = respuesta.hash;
                signData();
                console.log(respuesta);
            },
            error: function() {
                alert('Error al generar la petición');
                $('#body_loader').hide();
                $('#campos').show();
                $('#btn-firma').html('Firmar y guardar');
                $('#btn-firma').prop('disabled', false);
            }
        });
    }

    //Se finaliza la firma del xml, se genera el pdf con la firma del xml y se inicia el proceso
    //de firma del pdf.
    function finalizarfirma() {
        var datosConstancia = {!! json_encode($datosConstancia) !!};
        console.log(datosConstancia);
        console.log(signaturepck7);
        var token = '{{ csrf_token() }}'
        $.ajax({
            url: "{{ route('finalizarfirma') }}",
            dataType: "json",
            type: "POST",
            data: {
                //motivo rechazo y estatus
                "motivo_rechazo" : $('#motivo_rechazo').val()??null,
                "estatus" : $('input[name="estatus"]:checked').val()??null,
                "multilaterald": resultado.multilateralId,
                "tokenfirma": resultado.token,
                "numeroserie": resultado.numeroSerie,
                "nombrecertificado": resultado.nombreCertificado,
                "cer64": cer64,
                "data": signaturepck7,
                "hash": resultado.hash,
                "datosConstancia": datosConstancia,
                "_token": token,
            },
            success: function(respuesta) {
                $("#acuse_xml").val(respuesta.datosXML.acuse_xml);
                $("#constancia_xml").val(respuesta.datosXML.constancia_xml);
                $("#id_proceso_xml").val(respuesta.datosXML.id_proceso_xml);
                $("#hash_xml").val(respuesta.datosXML.hash_xml);

                idPDF = respuesta.multilateralIdPDF;
                hashPDF = respuesta.hashPDF;
                signData();
                console.log('Fin e Inicio')
                console.log(respuesta.datosXML)
            },
            error: function() {
                alert('Error al generar la petición');
                $('#campos').show();
                $('#body_loader').hide();
                $('#btn-firma').html('Firmar y guardar');
                $('#btn-firma').prop('disabled', false);
            }
        });
    }

    //Se finaliza la firma del pdf.
    function finalizarfirmaPDF() {
        var token = '{{ csrf_token() }}'
        $.ajax({
            url: "{{ route('finalizarfirmapdf') }}",
            dataType: "json",
            type: "POST",
            data: {
                "multilateraldPDF": idPDF,
                "token": resultado.token,
                "numeroserie": resultado.numeroSerie,
                "hashPDF": hashPDF,
                "dataPDF": signaturepck7,
                _token: token,
            },
            success: function(respuesta) {
                $("#documento_firmado").val(respuesta.resultado[0].data);
                $("#hash").value = hashPDF;
                console.log('FIN');
                $("#acuse_pdf").val(respuesta.datosPDF.acuse_pdf);
                console.log($("#acuse_pdf").val());
                $("#id_proceso_pdf").val(respuesta.datosPDF.id_proceso_pdf);
                $("#hash_pdf").val(respuesta.datosPDF.hash_pdf);
                //$("#save").click();
                $("#form").submit();
            },
            error: function() {
                alert('Error al generar la petición');
                $('#campos').show();
                $('#body_loader').hide();
                $('#btn-firma').html('Firmar y guardar');
                $('#btn-firma').prop('disabled', false);
            }
        });
    }
</script>
