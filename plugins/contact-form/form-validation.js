jQuery('form#cf_form').validate({
	rules : {
		name:{
			required: true,
			lettersonly: true
		},
		email: {
			required: true,
		},
		phone:{
			required: true,
			number_spe: true,
			minlength: 10,
		}
	},
	messages:{
		name:{
			required: "Required Field!",
			lettersonly: "Letters only!",
		}
	}
});
/* jQuery('#cf_form').submit(function(event) {
	event.preventDefault();
	if (jQuery(this).valid()) {
		alert('Thanks');
	}
}); */
// Character only validation 
jQuery.validator.addMethod("lettersonly", function(value, element) {
  return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
}, "Letters only please"); 
// phone number validation
jQuery.validator.addMethod("number_spe", function(value, element) {
  return this.optional(element) || /^[+-\d\s]+$/.test(value);
}, "Please enter valid number"); 
