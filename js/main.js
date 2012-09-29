$(document).ready(function() {
        /**
         * Automatically sets focus to the first field of this class
         */
        $('.text-field-auto-focus:first').focus();
        
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