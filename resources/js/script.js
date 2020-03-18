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
    var parent = $(this);
    var child = $('.sub-type-select[data-parent="'+parent.attr('id')+'"]');
    var prev_value = child.val();
    var options = child.children('option');
    options.addClass('d-none').prop("selected",false)
            .filter('[parent-type="'+parent.val()+'"]')
            .removeClass('d-none').eq(0).prop('selected',true);
    var new_value = child.val();
    if (prev_value!=new_value) {
        child.change();
    };
});
