jQuery(document).ready(function () {

    // fix the image select modal input. The bootstrap styles do odd things to it
    jQuery('.fsj a.modal').addClass('hide');
    setTimeout("fsj_sort_modal_links()", 500);
});

function fsj_sort_modal_links() {
    jQuery('.fsj a.modal').each(function () {
        jQuery(this).removeClass('modal');
        jQuery(this).removeClass('hide');
    });
    
}