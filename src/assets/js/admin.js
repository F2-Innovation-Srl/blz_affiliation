document.addEventListener("DOMContentLoaded", function() {

    var deleteButtons = document.querySelectorAll(".delete")
    for (i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener('click', function() {
            document.querySelector("#hidden_for_delete").value=this.dataset.id;
        });
    }

    var attivatoreLists = document.querySelectorAll(".attivatore")
    for (i = 0; i < attivatoreLists.length; i++) {
        attivatoreLists[i].addEventListener('change', function() {
            var form = document.getElementById('GaTrakingIdSettings');
            form.submit();
        });
    }
    
});