


jQuery(document).ready(function() {
	jQuery('.blog-single.post-desc p img').parent().addClass('blog-single-img');
var maxLength = 15;
jQuery('.jqte_editor').keyup(function() {
	// alert('zee'); 
  var textlen = maxLength - jQuery(this).val().length;
  jQuery('#rchars').text(textlen);
});
});


jQuery(document).click(function (event) {
        var $target = jQuery(event.target);
        if (!$target.closest('#searchResult').length && (!jQuery(event.target).parents().hasClass('form-group')) ) {
            jQuery("#searchResult").hide();
        }
    });

/*jQuery('body').on("click", function (event) {
    if(!jQuery(event.target).parents().hasClass('navbar-collapse')) {
        jQuery('.navbar-collapse').removeClass('show');
        jQuery('body').removeClass('overflow_y_hid');
        jQuery('body .main-head').removeClass('hgt_chng');
      } else{
        let closeicon = event.target.className;
        if(closeicon.indexOf('close-icon') >= 0){
            jQuery('.navbar-collapse').removeClass('show');
            jQuery('body').removeClass('overflow_y_hid');
            jQuery('body .main-head').removeClass('hgt_chng');
          }
      }
    });*/

/*jQuery(document).mouseup(function(e) {
  var container = jQuery('#searchResult');
if (!container.is(e.target) && container.has(e.target).length === 0)
{
container.hide();
}
});*/

/*jQuery(function(){				
	var $win = jQuery(window); // or $box parent container
	var $searchresult = jQuery("#searchResult");
	$win.on("click", function(event){		
		if ( 
	$searchresult.has(event.target).length == 0 //checks if descendants of $box was clicked
	&&
	!$searchresult.is(event.target) //checks if the $box itself was clicked
	){
		jQuery("#searchResult").hide();
	}
	});
	  
});*/