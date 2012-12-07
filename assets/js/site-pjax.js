(function(){

  jQuery('body').pjax('a')
    .on('pjax:start', function() { console.log("starting pjax") })
    .on('pjax:end', function() {console.log("ending pjax") });
})();
