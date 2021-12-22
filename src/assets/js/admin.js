(function ($, root, undefined) {
	
    // DOM ready, take it away
    $(function () {
    
        'use strict';
         // DOM ready, take it away
        var deleteButtons = document.querySelectorAll(".delete")
        for (let i = 0; i < deleteButtons.length; i++) {
            deleteButtons[i].addEventListener('click', function() {
                document.querySelector("#"+this.dataset.name+"_hidden_for_delete").value=this.dataset.id;
            });
        }
    
        var attivatoreLists = document.querySelectorAll(".attivatore")
        for (let i = 0; i < attivatoreLists.length; i++) {
            attivatoreLists[i].addEventListener('change', function() {
                document.getElementById('GaTrackingIdSettings').submit();
            });
        }

        var ups = document.querySelectorAll(".up");
        ups[0].style.display = "none";
        for (let i = 0; i < ups.length; i++) {
            ups[i].addEventListener('click', function() {
                document.querySelector("#"+this.dataset.name+"_hidden_for_up").value=this.dataset.id;
            });
        }
        
        var downs = document.querySelectorAll(".down");
        downs[downs.length- 1].style.display = "none";
        for (let i = 0; i < downs.length; i++) {
            downs[i].addEventListener('click', function() {
                document.querySelector("#"+this.dataset.name+"_hidden_for_down").value=this.dataset.id;
            });
        }
        
    });

})(jQuery, this);