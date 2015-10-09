<script>

$(function(){
	$("#filter_setting_box").height($(window).height() - 125);
	$("#settings_box").height($(window).height() - 149);
	load_settings();
})

function edit_settings()
{
	$("#edit_icon").hide();
	$("#save_icon").show();
	$("#loading_icon").hide();
	$(".display_setting_value").hide();
	$(".edit_setting_value").show();
}

function load_settings()
{
	var this_div = $("#settings_box");
	
	$.ajax({
		
		url: "settings/load_settings/",
		type: "POST",
		cache: false,
		statusCode: {
			200: function(response){
				this_div.html(response);
			},
			404: function(){
				alert('Page not found');
			},
			500: function(){
				alert("500 error! "+response);
			},
		}
		
	})
}

function save_settings()
{
	$("#edit_icon").hide();
	$("#save_icon").hide();
	$("#loading_icon").show();
	
	var dataString = $("#settings_form").serialize();
	console.log(dataString);
	$.ajax({
		
		url: "settings/update_settings/",
		type: "POST",
		data:dataString,
		cache: false,
		statusCode: {
			200: function(){
				load_settings();
				$("#loading_icon").hide();
				$("#edit_icon").show();
			},
			404: function(){
				alert('Page not found');
			},
			500: function(){
				alert("500 error! "+response);
			},
		}
		
	})
	
	
	$(".display_setting_value").show();
	$(".edit_setting_value").hide();
}
</script>