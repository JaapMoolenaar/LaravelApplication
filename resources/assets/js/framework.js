$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="token"]').attr('content')
    }
});

function loadScript(url, callback){
    var head = document.getElementsByTagName("head")[0] || document.documentElement;
    var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = url;

    // Handle Script loading
    var done = false;

    // Attach handlers for all browsers
    script.onload = script.onreadystatechange = function() {
        if ( 
            !done && 
           (!this.readyState || this.readyState === "loaded" || this.readyState === "complete") 
        ){
            done = true;
            if(typeof callback == 'function') {
                callback();
            }
            
            // Handle memory leak in IE
            script.onload = script.onreadystatechange = null;
            if ( head && script.parentNode ) {
                head.removeChild( script );
            }
        }
    };

    // Use insertBefore instead of appendChild  to circumvent an IE6 bug.
    // This arises when a base node is used (#2709 and #4378).
    head.insertBefore( script, head.firstChild );
}

function getMetaContent(metaname) {
    var metas = document.getElementsByTagName('meta');
    for (i=0; i<metas.length; i++) {
       if (metas[i].getAttribute("name") === metaname)
          return metas[i].getAttribute("content");
    }
    return "";
}

loadScript("/js/bootstrap-select/i18n/defaults-"+getMetaContent('language')+".js", function(){
    $('select').selectpicker({
        size: 4
    });
});


$(document).on('click', '.btn-drop', function(e){
    var $btn = $(this),
        $drop = $('<span class="drop">'),
        btnX = $btn.offset().left,
        btnY = $btn.offset().top,
        inBtnX = e.clientX - btnX,
        inBtnY = e.clientY - btnY,
        scale = (Math.max($btn.height(), $btn.width()) / 549) * 1.2;
                if(scale < 0.2) {
            scale = 0.2;
        }

    $drop.css({top: inBtnY, left: inBtnX, transform: "scale("+scale+")"}).appendTo($btn);
    setTimeout(function(){
        $drop.remove();
    },2000);
});

$('[data-confirm-txt]').click(function(e){
    e.preventDefault();

    var $this = $(this),
        text = $this.data('confirm-txt'),
        confirmed = $this.data('confirmed') === true;

    if(!confirmed) {
        // Deze functie moet worden uitgevoerd als de bezoeker op "OK" heeft gedrukt
        // Dit kan dus via een standaard confirm, maar eventueel ook uit een andere
        // modal
        function confirmedCallback() {
            $this.data('confirmed', true);

            // Bij een link gaan we naar de href
            if($this.is('a')) {
                window.location = $this.attr('href');
            }
            // Bij een button of input type="submit" submitten we het formulier
            else if($this.is('[type="submit"]')) {
                $this.closest('form').submit();
            }
        }

        if(confirm(text)) confirmedCallback();
    }
});