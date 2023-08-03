<script>
    jQuery(document).ready(function(){

        $("<?= $validator['selector']; ?>").each(function() {
            $(this).validate({
                errorElement: 'span',
                errorClass: 'help-block error-help-block',
                errorPlacement: function (error, element) {
                    if (element.parent('.validacion').length ||
                        element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                            error.insertAfter(element.parents('div[class*="validacion"]:last'));
                            error.addClass('text-danger');
                        //error.insertAfter(element.parent());
                        // else just place the validation message immediately after the input
                    } else {
                        error.insertAfter(element);
                    }
                },

                highlight: function (element) {
                    $(element).closest('.validacion').removeClass('has-success').addClass('has-error'); // add the Bootstrap error class to the control group
                },

                <?php if (isset($validator['ignore']) && is_string($validator['ignore'])): ?>

                ignore: "<?= $validator['ignore']; ?>",
                <?php endif; ?>
                ignore: "",
                /*
                 // Uncomment this to mark as validated non required fields
                 unhighlight: function(element) {
                 $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                 },
                 */
                success: function (element) {
                    $(element).closest('.validacion').removeClass('has-error').addClass('has-success'); // remove the Boostrap error class from the control group
                },

                focusInvalid: true,
                <?php if (Config::get('jsvalidation.focus_on_error')): ?>
                invalidHandler: function (form, validator) {

                    if (!validator.numberOfInvalids())
                        return;

                    $('html, body').animate({
                        scrollTop: $(validator.errorList[0].element).offset().top
                    }, <?= Config::get('jsvalidation.duration_animate') ?>);

                },
                <?php endif; ?>

                rules: <?= json_encode($validator['rules']); ?>
            });
        });
        /**
         * Valition add max lenght for input applicable
         */
        // var rules = $("form").validate().settings.rules;
        // $.each(rules, function( k, v ) {
        //     let input = k;
        //     let arr = v.laravelValidation;
        //     arr.forEach((casimax, index) => {
        //         if (casimax[0]=='Max') {
        //             //console.log(casimax[1]+' '+input);
        //             $("#"+input).prop('maxLength', casimax[1]);
        //         }
        //     });
        // });
    });
</script>
