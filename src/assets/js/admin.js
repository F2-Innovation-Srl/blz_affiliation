(function ($, root, undefined) {
	

		'use strict';
		
		// DOM ready, take it away
        document.addEventListener("DOMContentLoaded", function() {

            var deleteButtons = document.querySelectorAll(".delete")
            for (let i = 0; i < deleteButtons.length; i++) {
                deleteButtons[i].addEventListener('click', function() {
                    document.querySelector("#hidden_for_delete").value=this.dataset.id;
                });
            }
        
            var attivatoreLists = document.querySelectorAll(".attivatore")
            for (let i = 0; i < attivatoreLists.length; i++) {
                attivatoreLists[i].addEventListener('change', function() {
                    document.getElementById('GaTrakingIdSettings').submit();
                });
            }
            
        });

	
})(jQuery, this);