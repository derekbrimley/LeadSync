<script>
	$(document).ready(function(){
		$("#main_content").height($(window).height() - 165);
		$("#leads_container").height($(window).height() - 150);
		$("#left_nav_filters").height($(window).height() - 115);
		$("#report_container").height($(window).height() - 192);
		$("#scrollable_filter_div").height($(window).height() - 308);
		$("#contact_after_date_filter").datepicker();
		$("#contact_before_date_filter").datepicker();
		$("#availability_after_date_filter").datepicker();
		$("#availability_before_date_filter").datepicker();
		$("#action_after_date_filter").datepicker();
		$("#action_before_date_filter").datepicker();
		$(".datepicker").datepicker();
		
		load_report();
		
		//ADD NOTES DIALOG
        $( "#add_notes").dialog(
        {
			autoOpen: false,
			height: 400,
			width: 420,
			modal: true,
			buttons: 
				[
					{
						text: "Save",
						click: function() 
						{
							if(!$("#new_note").val())
							{
								alert("You didn't enter a new note!");
							}
							else
							{
								var expense_id = $("#expense_id").val();
								save_note(expense_id);
								console.log(expense_id);

							}
							
							
							
						},//end add load
					},
					{
						text: "Close",
						click: function() 
						{
							//CLEAR TEXT AREA
							$("#new_note").val("");
							$( this ).dialog( "close" );
						}
					}
				]//end of buttons
        });//end add notes dialog
		
		
	})//ready
	
	var report_ajax_call;
	
	function add_action_item(id)
	{
		report_ajax_call = null;
		
		var action_item_note = $("#action_item_input_"+id).val();
		var due_date = $("#datepicker_due_date_"+id).val();
		var is_valid = true;
		
		if(due_date=="")
		{
			is_valid = false;
			alert("Please enter a valid due date.")
		}
		if(is_valid==true)
		{
			dataString = $("#action_item_form").serialize();
			
			//AJAX
			report_ajax_call = $.ajax({
				url: "<?= base_url("index.php/leads/create_action_item") ?>",
				type: "POST",
				data:{
					id:id,
					action_item_note:action_item_note,
					due_date:due_date,
				},
				cache: false,
				statusCode: {
					200: function(){
						refresh_lead_details(id);
					},
					404: function(){
						alert('Page not found');
					},
					500: function(){
						alert("500 error! "+response);
					},
				}
			})//ajax
			refresh_lead_row(id);
		}
	}
	
	function add_note(id)
	{
		report_ajax_call = null;
		
		var note = $("#note_input_"+id).val();
		var is_valid = true;
		
		if(note=="")
		{
			is_valid = false;
			alert("Please enter a note.")
		}
		if(is_valid==true)
		{
			dataString = $("#add_note_form").serialize();
			
			//AJAX
			report_ajax_call = $.ajax({
				url: "<?= base_url("index.php/leads/create_note") ?>",
				type: "POST",
				data:{
					id:id,
					note:note,
				},
				cache: false,
				statusCode: {
					200: function(){
						refresh_lead_details(id);
					},
					404: function(){
						alert('Page not found');
					},
					500: function(){
						alert("500 error! "+response);
					},
				}
			})//ajax
			refresh_lead_row(id);
		}
	}
	
	function complete_action_item(id)
	{
		var lead_id = $("#complete_action_item_"+id).attr("data-lead_id");
		//console.log(lead_id);
		
		report_ajax_call = $.ajax({
				url: "<?= base_url("index.php/leads/complete_action_item") ?>",
				type: "POST",
				data:{id:id},
				cache: false,
				statusCode: {
					200: function(){
						refresh_lead_details(lead_id);
						refresh_lead_row(lead_id);
					},
					404: function(){
						alert('Page not found');
					},
					500: function(){
						alert("500 error! "+response);
					},
				}
			})//ajax
	}
	
	function edit_lead_details(id){
		var dataString = $("#driver_details_form_"+id).serialize();
		var availability_date = $("#availability_date_input").val();
		console.log(dataString);
		//AJAX
		report_ajax_call = $.ajax({
			url: "<?= base_url("index.php/leads/update_lead_details") ?>",
			type: "POST",
			data:dataString,
			cache: false,
			statusCode: {
				200: function(){
					refresh_lead_row(id);
				},
				404: function(){
					alert('Page not found');
				},
				500: function(){
					alert("500 error! "+response);
				},
			}
		})//ajax
	}
	
	function load_report()
	{
		$("#refresh_leads_btn").hide();
		$("#refresh_leads_gif").show();
		
		//SHOW LOADING ICON
		$("#loading_icon").show();
		
		//GET THE REPORT DIV
		var report_div = $('#report_container');
		var count_div = $("#count_div");
		
		//GET THE POST DATA FROM THE FILTER BOX
		var dataString = $("#filter_form").serialize();
		
		//AJAX
		if(!(report_ajax_call===undefined))
		{
			report_ajax_call.abort();
		}
		report_ajax_call = $.ajax({
			
			url: "<?=base_url("index.php/leads/leads_report") ?>",
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
	
	function open_lead_details(id)
	{
		//console.log("clicked row " + id);
		if($("#row_details_"+id).css('display')=='none')
		{
			$("#row_details_"+id).show();
			refresh_lead_details(id);
		}
		else
		{
			$("#row_details_"+id).hide();
		}
	}
	
	//AJAX FOR GETTING NOTES
    function open_notes(lead_id)
    {
        //RESET LOADING GIF
        $("#notes_ajax_div").html("");
        
        $("#lead_id").val(lead_id); //this is the hidden field in the add notes form
        
        //OPEN THE DIALOG BOX
        $( "#add_notes").dialog( "open" );
        var selected_lead = lead_id;
        $("#tr_"+lead_id).addClass("blue_border");
        
        //alert('inside ajax');
                
        // GET THE DIV IN DIALOG BOX
        var notes_ajax_div = $('#notes_ajax_div');
        
        //POST DATA TO PASS BACK TO CONTROLLER
        
        // AJAX!
        $.ajax({
            url: "<?= base_url("index.php/leads/get_notes/")?>"+"/"+lead_id, // in the quotation marks
            type: "POST",
            cache: false,
            context: notes_ajax_div, // use a jquery object to select the result div in the view
            statusCode: {
                200: function(response){
                    // Success!
                    notes_ajax_div.html(response);
                    //alert(response);
                },
                404: function(){
                    // Page not found
                    alert('page not found');
                },
                500: function(response){
                    // Internal server error
                    alert("500 error!")
                }
            }
        });//END AJAX
        return false; 
    }//end open_add_notes()
	
	function refresh_lead_details(id)
	{
		var row_details = $("#row_details_"+id);
		var dataString = $("#filter_form").serialize();
		
		row_details.html("<img id='loading_icon' src='<?=base_url('images/ajax-loader_transparent.gif') ?>' style='margin-top:80px;margin-left:482px;height:60px;'/>")
		//console.log(id);
		
		report_ajax_call = null;
		report_ajax_call = $.ajax({
		
			url: "<?=base_url("index.php/leads/load_lead_details") ?>",
			type: 'POST',
			data: {id: id},
			cache: false,
			context: row_details,
			statusCode:{
				200: function(response){
					row_details.html(response);
					refresh_lead_row(id);
				},
				404: function(){
					alert('Page not found');
				},
				500: function(){
					alert('500 error! '+response);
				}
			}
		})//ajax
	}
	
	function refresh_lead_row(id)
	{
		var row_id = $("#tr_"+id);
		
		//console.log(id);
		
		report_ajax_call = null;
		report_ajax_call = $.ajax({
		
			url: "<?=base_url("index.php/leads/load_lead_row") ?>",
			type: 'POST',
			data: {id: id},
			cache: false,
			context: row_id,
			statusCode:{
				200: function(response){
					row_id.html(response);
				},
				404: function(){
					alert('Page not found');
				},
				500: function(){
					alert('500 error! '+response);
				}
			}
		})//ajax
	}
	
	function refresh_top_bar(dataString)
	{
		var count_div = $("#count_div");
		
		if(!(report_ajax_call===undefined))
		{
			report_ajax_call.abort();
		}
		report_ajax_call = $.ajax({
			
			url: "<?=base_url("index.php/leads/refresh_top_bar") ?>",
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
	
	function reset_filters()
	{
		// $("#search_filter").val("");
		// $("#lead_status_filter").val("Show Active");
		// $("#contact_after_date_filter").val("");
		// $("#contact_before_date_filter").val("");
		// $("#lead_type_filter").val("");
		// $("#lead_source_filter").val("");
		// $("#availability_after_date_filter").val("");
		// $("#availability_before_date_filter").val("");
		// $("#action_after_date_filter").val("");
		// $("#action_before_date_filter").val("");
		
		load_report();
	}
	
	function save_note(lead_id)
    {
        var dataString = $("#add_note_form").serialize();
        console.log(dataString);
        //CLEAR TEXT AREA
        $("#new_note").val("");
        
        //-------------- AJAX TO LOAD TRUCK DETAILS -------------------
        // GET THE DIV IN DIALOG BOX
        var notes_ajax_div = $('#notes_ajax_div');
        
        //POST DATA TO PASS BACK TO CONTROLLER
        
        // AJAX!
        $.ajax({
            url: "<?= base_url("index.php/leads/save_note")?>", // in the quotation marks
            type: "POST",
            data: dataString,
            cache: false,
            context: notes_ajax_div, // use a jquery object to select the result div in the view
            statusCode: {
                200: function(response){
                    // Success!
                    notes_ajax_div.html(response);
                    $("#lead_notes_"+lead_id).attr("title",response.replace(/<br>/gi,"\n"));
                    $("#lead_notes_"+lead_id).attr("src","/images/add_notes.png");
                    refresh_lead_details(lead_id)
                    //$("#notes_details").html(response);
                    //update_notes_td($("#billing_note_load_id").val());
                    
                    //alert(response);
                },
                404: function(){
                    // Page not found
                    alert('page not found');
                },
                500: function(response){
                    // Internal server error
                    alert("500 error!")
                }
            }
        });//END AJAX
        
    }//end save_note()
	
	function update_action_item(id)
	{
		var dataString = $("#action_item_form_"+id).serialize();
		var lead_id = $("#lead_id_input").val();
		//alert(dataString);
		//AJAX
			report_ajax_call = $.ajax({
				url: "<?= base_url("index.php/leads/update_action_items") ?>",
				type: "POST",
				data:dataString,
				cache: false,
				statusCode: {
					200: function(){
						refresh_lead_details(lead_id);
						refresh_lead_row(lead_id);
					},
					404: function(){
						alert('Page not found');
					},
					500: function(){
						alert("500 error! "+response);
					},
				}
			})//ajax
			
	}
	
</script>