function handleParsingPrivKeyFile(evt) {
    var temp_reader = new FileReader();

    var current_files = evt.target.files;

    temp_reader.onload =
        function (event) {
            privateKeyBuffer = event.target.result;
        };

    temp_reader.readAsArrayBuffer(current_files[0]);
}

function handleParsingCertFile(evt) {
    var temp_reader = new FileReader();

    var current_files = evt.target.files;

    temp_reader.onload =
        function (event) {
            certificateBuffer = event.target.result;
        };

    temp_reader.readAsArrayBuffer(current_files[0]);

    const reader = new FileReader();
    reader.onloadend = () => {
        cer64 = reader.result.replace("data:", "").replace(/^.+,/, "");
    };

    reader.readAsDataURL(current_files[0]);
}

function signData() {
    var hashAlgorithm = "sha256";
    var cipheredKey;
    var privateKeyBufferString = arrayBufferToString(privateKeyBuffer);
    var pKey = privateKeyBufferString.replace(/(-----(BEGIN|END) PRIVATE KEY-----|\r\n)/g, '');

    if (pKey.charAt(0) === "M") {
        cipheredKey = window.atob(pKey);
    } else {
        cipheredKey = privateKeyBufferString;
    }

    var certX509;
    var certificateBufferString = arrayBufferToString(certificateBuffer);
    var pCert = certificateBufferString.replace(/(-----(BEGIN|END) CERTIFICATE-----|\r\n)/g, '');

    if (pCert.charAt(0) === "M") {
        certX509 = window.atob(pCert);
    } else {
        certX509 = certificateBufferString;
    }

    try {
        //Getting password and data to sign
        var password = $("#password").val();
        var hashToSign;
        if (hashPDF != null) {
            hashToSign = hashPDF;
        } else {
            hashToSign = hash;
        }
        // Signing hash
        if (window.Promise) {
            var signPromise = pkcs7FromHash(password, cipheredKey, certX509, hashAlgorithm, hashToSign, true);
            signPromise.then(function (Signature) {
                signaturepck7 = Signature;

                if (hashPDF != null) {
                    setTimeout(finalizarfirmaPDF(), 9000);
                } else {
                    setTimeout(finalizarfirma(), 9000);
                }
            }, function (error) {
                if (error.indexOf("Unexpected format or file") != -1) {
                    var result1 = openOldKey(cipheredKey, password);

                    if (result1.indexOf("Error") != -1) {
                        alert("[SgDataCrypto] - " + result1);
                        signaturepck7 = "";
                        $('#body_loader').hide();
                        $('#campos').show();
                        $('#btn-firma').html('Firmar y guardar');
                        $('#btn-firma').prop('disabled', false);
                    } else {
                        result1 = signHash_2(hashToSign, hashAlgorithm, btoa(certX509), result1, password, true);
                        if (result1.indexOf("Error") != -1) {
                            alert("[SgDataCrypto] - " + result1);
                            $('#body_loader').hide();
                            $('#campos').show();
                            signaturepck7 = "";
                            $('#btn-firma').html('Firmar y guardar');
                            $('#btn-firma').prop('disabled', false);
                        } else {
                            signaturepck7 = result1;
                        }
                    }
                } else {
                    alert("[SgDataCrypto] - " + error);
                    signaturepck7 = "";
                    $('#body_loader').hide();
                    $('#campos').show();
                    $('#btn-firma').html('Firmar y guardar');
                    $('#btn-firma').prop('disabled', false);
                }
            });
        } else {
            alert("Your current browser does not support Promises! This page will not work.");
            $('#body_loader').hide();
            $('#campos').show();
            $('#btn-firma').html('Firmar y guardar');
            $('#btn-firma').prop('disabled', false);
        }
    } catch (err) {
        alert("[SgDataCrypto] - " + err.message + "\n" + err.stack);
    }
}

