(function($){

  // Uncomment for pjax event testing
  $(document).on('pjax:start', function() { console.log("starting pjax") });
  $(document).on('pjax:end', function() {console.log("ending pjax") });

  // requests ending with these strings will NOT trigger a pjax request.
  var types = [".jpg", ".png", ".pdf"];

  var matchesTypes = function(requestHref) {

    var _return = false;

    $.each(types, function(i, v) {

      var regex = new RegExp("\\" + v + "$");
      var matches = requestHref.match(regex);

      if( matches )
        _return = true;
      
    });    

    return _return;
  };


  $(document).on('click', 'a', function(e) {

    var req = matchesTypes($(e.srcElement).attr('href'));

    if(req){
      // Business as usual
    } else {
      if($.support.pjax) {
        $.pjax.click(e, { container: $('body') });
      }
    }
    
  });

})(jQuery);
