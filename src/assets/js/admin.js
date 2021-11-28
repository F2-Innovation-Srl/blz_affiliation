(function () {

		'use strict';
		
        document.addEventListener("DOMContentLoaded", function() {

          // DOM ready, take it away
          var deleteButtons = (document.querySelectorAll(".delete")) ? document.querySelectorAll(".delete") : 0
          for (let i = 0; i < deleteButtons.length; i++) {
              deleteButtons[i].addEventListener('click', function() {
                  document.querySelector("#hidden_for_delete").value=this.dataset.id;
              });
          }
      
          var attivatoreLists = (document.querySelectorAll(".attivatore")) ? document.querySelectorAll(".delete") : 0
          for (let i = 0; i < attivatoreLists.length; i++) {
              attivatoreLists[i].addEventListener('change', function() {
                  document.getElementById('GaTrakingIdSettings').submit();
              });
          }

        });
        
});