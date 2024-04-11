(function ($) {
	function initialize_field($field) {
		/**
		 * $field is a jQuery object wrapping field elements in the editor.
		 */
		console.log("Speaker Select field initialized", $field);
	}

	if (typeof acf.add_action !== "undefined") {
		/**
		 * Run initialize_field when existing fields of this type load,
		 * or when new fields are appended via repeaters or similar.
		 */
		acf.add_action("ready_field/type=speaker_select", initialize_field);
		acf.add_action("append_field/type=speaker_select", initialize_field);

		var Field = acf.models.SelectField.extend({
			type: "speaker_select", // Your custom field type name
			// Additional custom logic specific to your field can go here
		});

		acf.registerFieldType(Field);

		// Optionally, if you need to run code after your field is initialized:
		acf.addAction("new_field/type=speaker_select", function (field) {
			// Code to run after the 'speaker_select' field is initialized
			// For example, you might want to further customize the Select2 instance here
		});
	}
})(jQuery);
