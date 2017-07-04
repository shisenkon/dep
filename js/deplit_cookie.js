$(document).ready(function(){

save_cookie_list = $("#deplit_type").text();

function save_session_param() {
		//Работа с куки
		seed_for_cookie = $("#this_seed").text();
		is_plintus100 = 0;
		if ($("#plintus100").prop( "checked" ) == true) {is_plintus100 = 1;}
		if ($("#seed_fix_check").prop( "checked" ))  {seed_for_cookie = $("#seed_fix").val();}
		this_session_param = save_cookie_list + ":" + $("#surface_lenght").val() + ":" + $("#surface_height").val() + ":" + is_plintus100 + ":" + $("#seed_fix_check").prop( "checked" ) + ":" + seed_for_cookie + ":" + "метка";
		return this_session_param;
	};
//сохранить куки
$("#save_param").click(function(){
	cookie_loft_param = "";
	semicolon_add = "";
	if (!($.cookie(save_cookie_list))) {cookie_loft_param = $.cookie(save_cookie_list);}
	else {cookie_loft_param = ";" + $.cookie(save_cookie_list);}
	$.cookie(save_cookie_list, save_session_param() + cookie_loft_param , {expires: 7, path: '/'});
	$("#show_param").click()
	});

//вывести куки
$("#show_param").click(function(){
		var p = document.createElement('p'); // создаём параграф
		arr = "";
		if ($.cookie(save_cookie_list) ) {txt = $.cookie(save_cookie_list);arr = txt.split(';');}
		$(".render_cookie").empty();


		jQuery.each(arr, function() {
			//$("#" + this).text("Mine is " + this + ".");
			arr_param = this.split(':');
			render_text = "";
			//render_text = render_text + arr_param[0];
			render_text = render_text + " " + arr_param[1] + "x" + arr_param[2] + "мм" ;
			render_plintus = "";
			form_plintus = "";
			//alert(arr_param[3]);
			if (arr_param[3] > 0)
				{render_plintus = "с нижним плинтусом";
				form_plintus = "on";}
			else
				{render_plintus = "без нижнего плинтуса";
				form_plintus = "off";}
			render_text = render_text + " " + render_plintus + " " + arr_param[5];
			render_text = render_text + "<form method=\"post\" action=\"\">";
			render_text = render_text + "<input type=\"hidden\" name=\"surface_lenght\" value=\"" + arr_param[1] + "\">";
			render_text = render_text + "<input type=\"hidden\" name=\"surface_height\" value=\"" + arr_param[2] + "\">";
			render_text = render_text + "<input type=\"hidden\" name=\"plintus100\" value=\"" + form_plintus + "\">";
			render_text = render_text + "<input type=\"hidden\" name=\"seed_fix_check\" value=\"on\">";
			render_text = render_text + "<input type=\"hidden\" name=\"seed_fix\" value=\"" + arr_param[5] + "\">";
			render_text = render_text + "<button type=\"submit\"  class=\"btn btn-primary btn-block\">Открыть</button></form>";
			$(".render_cookie").append('<div class="col-md-12 col-xs-4" style="font-size: 12px;">' + render_text + '</div>');
			//$(".render_cookie").append('<p>' + this + '<p>');
		});

    });

//очистить куки
$("#del_param").click(function(){
	$.cookie(save_cookie_list, '', {expires: 7, path: '/'});
	$("#show_param").click();
    });
//Работа с куки

		//переключение видимости текстового поля определенного зерна
    $("#seed_fix_check").change(function(){
        $("#seed_fix_div").toggle();
    });

    function replace_seed_modern() {
 this.value = this.value.replace(/[^0-2]*/g,'') ;
} ;
function replace_not_numbers() {
 this.value = this.value.replace(/[^0-9]*/g,'') ;
} ;
$("#seed_fix").keyup(replace_seed_modern);
$("#surface_height").keyup(replace_not_numbers);
$("#surface_lenght").keyup(replace_not_numbers);
$('.spoiler-body').hide();
    $('.spoiler-head').click(function(){
        $(this).next().toggle();
    });
//код для масштабирования панно
  /*  if ($(".deplit_block").width() > $(window).width())
    	{
    		var k_scale;
    		var k_css;
    		k_scale = $(window).width() / ($(".deplit_block").width()+($(".deplit_block").width()*0.1));
    		k_scale = Math.round(k_scale*100)/100;
    		//alert(k_scale);
    		k_css = "transform:scale(" + k_scale + "," + k_scale + ")";
    		//alert(k_css);
			$(".deplit_block").css({
            '-webkit-transform': 'scale(' + k_scale + ')',
            '-moz-transform': 'scale(' + k_scale + ')',
            '-ms-transform': 'scale(' + k_scale + ')',
            '-o-transform': 'scale(' + k_scale + ')',
            '-o-transform': 'scale(' + k_scale + ')',
            'margin-right' : '0px',
            'margin-left' : '-' + ($(".deplit_block").width()*(1-k_scale))/2 + 'px',
           	'margin-top' : 0-(($(".deplit_block").height()*(1-k_scale))/2)+150 + 'px',
            'margin-bottom' : '-' + ($(".deplit_block").height()*(1-k_scale))/2+20 + 'px'
    	});
//alert($(".deplit_block").width()*k_scale);
			//'height' : $(".deplit_block").width()*k_scale + 'px'
		};*/
//код для масштабирования панно

//нажимаем кнопку показать куки
$("#show_param").click();

$k_scale = 1;
//эксперимент с масштабированием панно
function change_size_render() {

	$container_render_pl_width = $(".container_render_pl").width();
	$deplit_surface_width = $(".deplit_surface").width()

	if ($container_render_pl_width < $deplit_surface_width)
		{$k_scale = $container_render_pl_width / $deplit_surface_width;
			//$k_scale = Math.round(($k_scale * 100) / 100 );
		}
//alert('container_render_pl_width:' + $container_render_pl_width + ' deplit_surface:' + $deplit_surface_width + ' k=' + $k_scale);

	// цикл проходит по всем элементам конечных плиток в линии
    $('.pl_last_horiz').each(function(){
        $last_line_width = $(this).width();
        $(this).css({'width': $last_line_width*$k_scale + 'px'});
    });
   $(".pl_len100").css({'width': 100*$k_scale + 'px'});
   $(".pl_len150").css({'width': 150*$k_scale + 'px'});
	$(".pl_len200").css({'width': 200*$k_scale + 'px'});
	$(".pl_len250").css({'width': 250*$k_scale + 'px'});
	$(".pl_len300").css({'width': 300*$k_scale + 'px'});
	$(".pl_len350").css({'width': 350*$k_scale + 'px'});

	$(".pl_wide40").css({'height': 40*$k_scale + 'px'});
	$(".pl_wide60").css({'height': 60*$k_scale + 'px'});
	$(".pl_wide100").css({'height': 100*$k_scale + 'px'});
	$(".deplit_block").css({'width': $deplit_surface_width *$k_scale + 'px'});
	$(".deplit_surface").css({'width': $deplit_surface_width *$k_scale + 'px'});
	$(".deplit_block").css({'display': 'block'});
    };

     $("#change_bg").click(function(){
         $(".deplit_cell").css({'background-position-x': 350*$k_scale + 'px'});
    });
//$(".deplit_block").css({'display': 'block'});
    change_size_render();
});