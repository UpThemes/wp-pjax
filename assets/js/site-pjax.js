(function($){

  // Uncomment for pjax event testing
  $(document).on('pjax:start', function() { console.log("starting pjax") });
  $(document).on('pjax:end', function() {console.log("ending pjax") });

  // requests ending with these strings will NOT trigger a pjax request.
  var types = [".jpg", ".png", ".pdf"];

  var matchesTypes = function(requestHref) {

    $.each(types, function(i, v) {
//      var regex = "\\" + v + "$"; // escape the period & add string end modifier
      var regex = ".jpg";
      var matches = toString(requestHref).match(regex);

      console.log("matches: " + matches + " -  requestHref: " + requestHref + " - regex: " + regex );

      if( matches )
        return true;
      
    });    

    return false;
  };


  $(document).on('click', 'a', function(e) {

    e.preventDefault();
    var req = matchesTypes($(e.srcElement).attr('href'));

    // Exit if it matches something in our filter
    if(req){
      return false;
    } else {
//      if($.support.pjax) {
//        $.pjax.click(e, { container: $('body') });
//      }
    }
    
  });

})(jQuery);
