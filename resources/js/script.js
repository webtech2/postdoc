var deleteFrms = document.querySelectorAll('.delete-frm');

for (var i = 0; i < deleteFrms.length; i++) {
    deleteFrms[i].addEventListener('submit', function(event) {
        var choice = confirm(this.getAttribute('data-confirm'));
        if (choice) {
            return true;
        }  
        else {
            event.preventDefault();
                return false;
        }
    });
}