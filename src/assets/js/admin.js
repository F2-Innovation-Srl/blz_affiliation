document.addEventListener("DOMContentLoaded", function() {

    var deleteButtons = document.querySelectorAll(".delete")
    for (i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener('click', function() {
            document.querySelector("#hidden_for_delete").value=this.dataset.id;
        });
    }
});