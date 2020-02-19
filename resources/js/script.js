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

var typeSelect = $('.type-select').on('change', function(event) {
    $('.sub-type-select option').addClass('d-none').prop("selected",false);
    $('.sub-type-select option[parent-type="'+$(this).val()+'"]').removeClass('d-none').eq(0).prop('selected',true);
});


