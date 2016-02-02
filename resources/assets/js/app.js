
if($('html').hasClass('embedded')) {
    if(window.frameElement === null) {
        location.href = location.href+(location.href.indexOf('?') > 0 ? '&' : '?')+'unembed=1';
    }
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

$(document).on('change init', '.checkbox-role', function(){
    var $this = $(this),
        $target = $('#'+$this.data('target'));
    if($this.is(':checked')) {
        $target.hide();
    } else {
        $target.show();
    }
});
$('.checkbox-role').trigger('init');