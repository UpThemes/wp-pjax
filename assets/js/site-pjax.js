(function($){

  // We have accees to the variable 'pjaxData' thanks to wp_localize_script();
  // Uncomment for variable data
  //   console.log(pjaxData);
  //   console.log(typeof pjaxData.pjaxContainer + " : " + pjaxData.pjaxContainer);
  //   console.log(typeof pjaxData.pjaxFilters + " : " + pjaxData.pjaxFilters);
  //   console.log(typeof pjaxData.pjaxTarget + " : " + pjaxData.pjaxTarget);

  // Uncomment for pjax event testing
  // $(document).on('pjax:start', function() { console.log("starting pjax") });
  // $(document).on('pjax:end', function() {console.log("ending pjax") });
  // $(document).on('pjax:error', function(e, data, type) { return false; });
   $(document).on('pjax:timeout', function(e) { return false; });


  // requests ending with these strings will NOT trigger a pjax request. 
  var matchesTypes = function(requestHref) {

    var _return = false;
    $.each(pjaxData.pjaxFilters, function(i, v) {
      var regex, matches;
      regex = new RegExp("\\" + v + "$");
      matches = requestHref.match(regex);
      if( matches )
        _return = true;
    });    
    return _return;

  };

  // Add success callbacks
  if(typeof pjaxData.successCB !== "undefined"){
    $(document).on('pjax:success', function(e) {

      if(typeof pjaxData.successCB === "object"){
        $.each(pjaxData.successCB, function(index, value){
          eval(value);
        });
      } else if (typeof pjaxData.successCB === "string"){
        eval(pjaxData.successCB);
      }

    });
  }

  $(document).on('click', pjaxData.pjaxTarget, function(e) {

    if($(e.target).attr('href'))
      clickTarget = $(e.target).attr('href');
    else
      clickTarget = $(e.target).parents('a').attr('href');

    if( matchesTypes(clickTarget) ){
      // Bad match - business as usual
    } else {
      if($.support.pjax)
        $.pjax.click(e, { container: pjaxData.pjaxContainer });
    }
    
  });

})(jQuery);
