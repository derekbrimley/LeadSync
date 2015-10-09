<script>
	$(function()
	{
		$(".dp").datepicker();
		$("#action_items_report_container").height($(window).height() - 207);
		load_action_items_report();
		
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
								var lead_id = $("#lead_id").val();
								save_note(lead_id);
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
	})

	function action_complete_action_item(id)
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
						update_action_item(id);
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
	function load_action_items_report()
	{
		$("#refresh_actions_gif").show();
		$("#refresh_actions_btn").hide();
		
		var report_div = $("#action_items_report_container");
		var dataString = $("#action_items_filter").serialize();
		//console.log(dataString);
		// AJAX!
        $.ajax({
            url: "<?= base_url("index.php/action_items/update_action_item_report/")?>",
            type: "POST",
			data: dataString,
            cache: false,
            context: report_div, // use a jquery object to select the result div in the view
            statusCode: {
                200: function(response){
                    // Success!
                    report_div.html(response);
					refresh_action_item_count();
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
	}
	
	function refresh_action_item_count()
	{
		var count_div = $("#action_item_count");
		var dataString = $("#action_items_filter").serialize();
		console.log(dataString);
		// AJAX!
        $.ajax({
            url: "<?= base_url("index.php/action_items/update_action_item_count/")?>",
            type: "POST",
			data: dataString,
            cache: false,
            context: count_div, // use a jquery object to select the result div in the view
            statusCode: {
                200: function(response){
                    // Success!
                    count_div.html(response);
					$("#refresh_actions_gif").hide();
					$("#refresh_actions_btn").show();
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
	
	function reset_action_filters()
	{
		// $("#lead_filter").val("");
		// $("#due_date_after_filter").val("");
		// $("#due_date_before_filter").val("");
		// $("#availability_date_after_filter").val("");
		// $("#availability_date_before_filter").val("");
		// $("#lead_type_filter").val("");
		// $("#action_lead_status_filter").val("Show Active");
		
		load_action_items_report();
	}
	
	function save_note(lead_id)
    {
        var dataString = $("#add_note_form").serialize();
        
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
	}//end save note
	
	function update_action_item(id)
	{
		var row = $("#tr_"+id);
		
		$.ajax({
			url:"<?= base_url("index.php/action_items/update_action_item") ?>",
			type: "POST",
			data:{id:id},
			cache: false,
			statusCode: {
				200: function(response){
					row.html(response);
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