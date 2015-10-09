<script>
	$(function(){
		$("#calls_container").height($(window).height() - 150);
		load_report();
		$(".dp").datepicker();
	})//ready
	var report_ajax_call;
	function load_report()
	{
		$("#refresh_leads_btn").hide();
		$("#refresh_leads_gif").show();
		
		var dataString = $("#filter_form").serialize();
		var report_div = $("#report_container");
		console.log(dataString);
		//AJAX
			if(!(report_ajax_call===undefined))
			{
				report_ajax_call.abort();
			}
			report_ajax_call = $.ajax({
				
				url: "<?=base_url("index.php/calls/calls_report") ?>",
				type: "POST",
				data: dataString,
				cache: false,
				context: report_div,
				statusCode: {
					200: function(response){
						report_div.html(response);
						refresh_top_bar(dataString);
						//console.log(dataString);
					},
					404: function(){
						alert('Page not found');
					},
					500: function(response){
						alert("500 error! "+response);
					}
				}
			});//END AJAX
	}
	
	function refresh_top_bar(dataString)
	{
		var count_div = $("#count_div");
		
		if(!(report_ajax_call===undefined))
		{
			report_ajax_call.abort();
		}
		report_ajax_call = $.ajax({
			
			url: "<?=base_url("index.php/calls/refresh_top_bar") ?>",
			type: "POST",
			data: dataString,
			cache: false,
			context: count_div,
			statusCode: {
				200: function(response){
					count_div.html(response);
					$("#refresh_leads_btn").show();
					$("#refresh_leads_gif").hide();
				},
				404: function(){
					alert('Page not found');
				},
				500: function(response){
					alert("500 error! "+response);
				}
			}
		});//END AJAX
	}
</script>