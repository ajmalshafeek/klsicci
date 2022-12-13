cleanto_holder=document.getElementById('cleanto');
var sites_urls=document.getElementById('cleanto').getAttribute('data-url');

cleanto_holder.innerHTML='<object id="cleanto_content" style="width:100%;height: -webkit-fill-available;" type="text/html" data="'+sites_urls+'" onload="cleantodivload()" ></object>';

var count_click = 0;
function cleantodivload(){ 	
	jQuery(document).ready(function(){
		jQuery('#cleanto object').contents().find('#ct-price-scroll').css('top',"940px");
		jQuery('#cleanto object').contents().find('#ct-not-scroll').css('top',"-280px");
		setTimeout(function() {	cleanto_demo_height();  }, 2000);
	});
	
	jQuery('#cleanto object').contents().find('.ct-list-services').click(function(e){
		count_click++;
		if(count_click == 4){
			setTimeout(function() {	cleanto_demo_height();  }, 5000);
			count_click = 0;
		}
	});
	
	jQuery('#cleanto object').contents().find('.ct-radio-list').click(function(e){
		count_click++;
		if(count_click == 2){
			setTimeout(function() {	cleanto_demo_height();  }, 1000);
			count_click = 0;
		}
	});
	
	jQuery('#cleanto object').contents().find('.cal_info').click(function(e){
		setTimeout(function() {	cleanto_demo_height();  }, 2000);
	});
	
	jQuery('#cleanto object').contents().find('.bi-terms-agree').click(function(e){
		setTimeout(function() {	cleanto_demo_height();  }, 1000);
	});
	
	jQuery('#cleanto object').contents().find('#login_existing_user').click(function(e){
		setTimeout(function() {	cleanto_demo_height();  }, 2000);
	});
}

function cleanto_demo_height(){
	jQuery('#cleanto object').contents().find('#ct-price-scroll').css('top',"940px");
	jQuery('#cleanto object').contents().find('#ct-not-scroll').css('top',"-280px");
	
	var new_page_height = jQuery('#cleanto object').contents().find('.ct-main-wrapper').height();
	
	jQuery('#cleanto').height(new_page_height);
}