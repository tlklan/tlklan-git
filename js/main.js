$(document).ready(function() {
        /**
         * Clears the field value when gaining focus if the field value is the 
         * same as the default value
         */
        $('.clear-on-focus').focus(function() {
                if($(this)[0].defaultValue == $(this).val()) {
                        $(this).val('');
                }
        });
});