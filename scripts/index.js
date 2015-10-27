$(function(){
	hide_all_slides();
	$('#inbound_or_outbound_slide').show();
	
	$("#main_content").height($(window).height() - 115);
	$("#scrollable_filter_div").height($(window).height() - 278);
	$("#leads_container").height($(window).height() -170);
	
	$("#phone_number").focus();
	
	$("#submitted_to").change(function(){
		var selected = $("#submitted_to").val();
		if(selected=="Lobos School" || selected=="Lobos CDL")
		{
			$("#transferred_to_div").show();
			$("#reason_not_sold_div").hide();
			$("#reason_not_sold_input").val("");
			console.log(selected);
		}
		else if(selected=="Other")
		{
			//console.log(selected);
			$("#reason_not_sold_div").show();
			$("#transferred_to_div").hide();
			$("#transferred_to").val("");
		}
		else
		{
			$("#transferred_to_div").hide();
			$("#reason_not_sold_div").hide();
			$("#transferred_to").val("");
			$("#reason_not_sold_input").val("");
		}
	})
	
	$("#credit_check_question").change(function(){
		var selected = $("#credit_check_question").val();
		if(selected=="Yes")
		{
			$("#credit_karma_script").show();
			console.log(selected);
		}
		if(selected=="No")
		{
			$("#credit_karma_script").hide();
			$("#call_form_input").val("");
		}
	})
	
	$("#parole_question").change(function(){
		var selected = $("#parole_question").val();
		if(selected=="Yes")
		{
			$("#permission_question").show();
			console.log(selected);
		}
		if(selected=="No")
		{
			$("#permission_question").hide();
			$("#states_question").val("");
		}
	})
	
	$("#phone_number").keydown(function(e)
	{
		// Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [109,189,45,46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
             // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
	});
	
	
});//ready

//FILTERS
var min_age_cdl_filter = $("#min_age_cdl_filter").val();
var max_age_cdl_filter = $("#max_age_cdl_filter").val();
var min_age_school_filter = $("#min_age_cdl_filter").val();
var max_age_school_filter = $("#max_age_cdl_filter").val();
var max_tickets_filter = $("#max_tickets_filter").val();
var drivers_needed_filter = $("#drivers_needed_filter").val();

//GLOBAL VARIABLES
var call_type = null;
var reason_for_call = null;
function isInArray(value, array){
  return array.indexOf(value) > -1;
}
function accident_explanation_next(){
	
	//MAKE SLIDE 11 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	var accident_explanation = $('#accident_explanation_question').val();
	var reason_for_call = $("#reason_for_call_question").val();
	var tickets = $("#number_of_tickets_question").val();
	var max_tickets = $("#max_tickets_filter").val();
	var credit_filter = $("#credit_filter").val();
	var is_valid = true;
	
	if(accident_explanation=="")
	{
		is_valid = false;
		alert("Please write in an explanation.");
	}
	if(is_valid)
	{
		if(reason_for_call=="School" && tickets<=max_tickets)
		{
			if(credit_filter>0)
			{
				refresh_response_table();
				hide_all_slides();
				$("#credit_check_slide").show();
				$("#credit_check_question").focus();
			}
			else
			{
				refresh_response_table();
				hide_all_slides();
				$("#transfer_to_lobos_school_slide").show();
			}
		}
		else if(reason_for_call=="School" && tickets>max_tickets)
		{
			refresh_response_table();
			hide_all_slides();
			$('#school_opportunity_slide').show();
			$("#school_opportunity_question").focus();
		}
		else
		{
			refresh_response_table();
			hide_all_slides();
			$('#license_number_slide').show();
			$("#license_number_question").focus();
		}
	}
}//accident_explanation_next
function add_first_name(){
	var first_name=$("#first_name").val();
	$("#phone_first_name").html(first_name);
	$("#age_first_name").html(first_name);
	//console.log(first_name);
}
function address_next(){
	
	//MAKE SLIDE 5 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	var address = $('#address').val();
	var city = $('#city').val();
	var state = $('#state').val();
	var zip = $("#zip_code").val();
	
	var western_states = ['WA','OR','CA','ID','UT','AZ','MT','CO','WY','NM','TX','OK','KS','NE','SD','ND'];
	
	var is_valid = true;
	
	if(address==""||city==""||state==""||zip==""){
		is_valid = false;
		alert("Please input a valid address, city, state, and zip code.");
	}
	if(is_valid==true){
		hide_all_slides();
		if(isInArray(state,western_states)){
			refresh_response_table();
			
			$('#otr_6_slide').show();
			$("#otr_6_question").focus();
		}else{
			$('#school_opportunity_slide').show();
			$("#school_opportunity_question").focus();
		}
		
	}
}//address_next
function age_next(){
	
	//MAKE SLIDE 6 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	var reason_for_calling = $("#reason_for_call_question").val();
	var age = $("#age_question").val();
	var min_age_cdl = $("#min_age_cdl_filter").val();
	var max_age_cdl = $("max_age_cdl_filter").val();
	var min_age_school = $("#min_age_school_filter").val();
	var max_age_school = $("max_age_school_filter").val();
	var is_valid = true;
	
	if(age=="")
	{
		is_valid = false;
		alert("Please select an age.");
	}
	else if(age=="<21")
	{
		hide_all_slides();
		$("#non_qualifier_slide").show();
		refresh_response_table();
	}
	else if(is_valid==true && reason_for_calling == "School")
	{
		if(age<=min_age_school || age>max_age_school)
		{
			is_valid = false;
			refresh_response_table();
			hide_all_slides();
			$("#email").show();
		}
		else
		{
			refresh_response_table();
			hide_all_slides();
			$("#birthday_slide").show();
			$("#birth_month").focus();
		}
	}
	else if(is_valid==true && reason_for_calling == "Class A")
	{
		if(age<=min_age_cdl || age>max_age_cdl)
		{
			is_valid = false;
			refresh_response_table();
			hide_all_slides();
			$("#rhino_instructions_slide").show();
		}
		else
		{
			refresh_response_table();
			hide_all_slides();
			$("#birthday_slide").show();
			$("#birth_month").focus();
		}
	}
	else
	{
		refresh_response_table();
		hide_all_slides();
		$("#form_complete_slide").show();
		$("#transferred_to").focus();
	}
	
}//age_next
function availability_date_next(){
	var availability_date = $("#availability_date_question").val();
	var is_valid = true;
	
	if(availability_date=="")
	{
		is_valid = false;
		alert("Please enter a date.")
	}
	else
	{
		hide_all_slides();
		$('#form_complete_slide').show();
		$("#transferred_to").focus();
	}
}
function birthday_next(){
	
	//MAKE SLIDE 7 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	var month = $("#birth_month").val();
	var day = $("#birth_day").val();
	var year = $("#birth_year").val();
	
	var is_valid = true;
	
	if(month==""||day==""||year=="")
	{
		is_valid = false;
		alert("Please select a month, day, and year.");
	}
	else
	{
		refresh_response_table();
		hide_all_slides();
		$('#parole_slide').show();
		$("#parole_question").focus();
	}
}//birthday_next
function credit_check_next(){
	var credit_filter = $("#credit_filter").val();
	var credit_score = $("#credit_score_input").val();
	var credit_check_question = $("#credit_check_question").val();
	var states_question = $("#48_states_question").val();
	var is_valid = true;
	
	if(credit_check_question=="")
	{
		is_valid = false;
		alert("Please enter a response.");
	}
	if(credit_check_question=="Yes" && credit_score=="")
	{
		is_valid = false;
		alert("Please enter a credit score.");
	}
	if(credit_check_question=="Yes" && isNaN(credit_score))
	{
		is_valid = false;
		alert("Please enter a valid credit score.");
	}
	if(is_valid==true && credit_score>=credit_filter)
	{
		console.log("transfer to lobos");
		refresh_response_table();
		hide_all_slides();
		$('#transfer_to_lobos_school_slide').show();
	}
	else if(is_valid==true && credit_score<credit_filter)
	{
		console.log("transfer to school");
		refresh_response_table();
		hide_all_slides();
		$('#school_opportunity_slide').show();
		$("#school_opportunity_question").focus();
	}
}
function email_next(){
	
	hide_all_slides();
	if(reason_for_call=="Class A"){
		$("#license_number_slide").show();
	}else if(reason_for_call=="School"){
		$("#valid_license_slide").show();
	}
	
}
function form_complete_next(){
	refresh_response_table();
	var is_valid = true;
	var submitted_to = $("#submitted_to").val();
	var transferred_to = $("#transferred_to").val();
	console.log($("#transferred_to_div").is(':visible'));
	if(submitted_to=="")
	{
		alert("Please enter who you submitted the lead to.");
		is_valid = false;
	}
	if(transferred_to=="" && $("#transferred_to_div").is(':visible'))
	{
		alert("Please enter who you transferred the call to.");
		is_valid = false;
	}
	if(submitted_to=="Not Sold")
		{
			//console.log(selected);
			var reason_not_sold = $("#reason_not_sold_input").val();
			if(reason_not_sold=="")
			{
				is_valid = false;
				alert("Please enter a reason the call was not sold.");
			}
		}
	
	if(is_valid == true)
	{
		hide_all_slides();
		$("#notes_on_lead_slide").show();
		$("#notes_on_lead_input").focus();
	}
	
}
function get_trucker_jobs_qualifier_next(){
	//MAKE SLIDE 5 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	var gtj_resp = $("#get_trucker_jobs_qualifier_question").val();
	var is_valid = true;
	
	if(gtj_resp=="")
	{
		is_valid = false;
		alert("please select a response.");
	}
	if(gtj_resp=="Yes" && is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$('#transfer_to_school_slide').show();
	}
	else if(gtj_resp=="No" && is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$('#non_qualifier_slide').show();
	}
}//get_trucker_jobs_qualifier_next
function inbound_or_outbound_next(){
	
	//MAKE SLIDE 1 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	call_type = $("#inbound_or_outbound_question").val();
	var source_of_call = $("#source_of_call_question").val();
	var phone_number = $("#phone_number").val();
	var stripped_number = phone_number.replace(/\D/g,'');
	var first_name = $('#first_name').val();
	var last_name = $('#last_name').val();
	reason_for_call = $("#reason_for_call_question").val();
	var is_valid = true;
	//console.log("Stripped Number: "+stripped_number)
	
	if(stripped_number=="")
	{
		is_valid = false;
		alert("Please fill in the information.");
	}
	else if(stripped_number.length < 10 || stripped_number.length > 10)
	{
		is_valid = false;
		alert("Please fill in the information.");
	}
	else if(call_type=="" || source_of_call=="")
	{
		is_valid = false;
		alert("Please fill in the information.");
	}
	else if(first_name==""||last_name=="")
	{
		if(reason_for_call=="Other" || reason_for_call=="Ghost Call" || reason_for_call=="Wrong Number")
		{
			hide_all_slides();
			$('#form_complete_slide').show();
			$("#submitted_to").focus();
			refresh_response_table();
		}
		else
		{
			is_valid = false;
			alert("Please fill in the information.");
		}
	}
	else if(reason_for_call=="")
	{
		is_valid = false;
		alert("Please fill in the information.");
	}
	else if(is_valid==true && reason_for_call=="Other" || reason_for_call=="Ghost Call" || reason_for_call=="Wrong Number")
	{
		$("#phone_number").val(stripped_number);
		dataString = $("#lead_form").serialize();
		console.log(dataString)
		
		//AJAX
		$.ajax({
			url: "call_form/create_partial_lead/",
			type: "POST",
			data:dataString,
			cache: false,
			statusCode: {
				200: function(response){
					$("#input_id").val(response);
					hide_all_slides();
					$('#form_complete_slide').show();
					$("#submitted_to").focus();
					refresh_response_table();
					is_valid = false;
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
	else if(is_valid==true && (reason_for_call=="School" || reason_for_call=="Class A"))
	{
		$("#phone_number").val(stripped_number);
		dataString = $("#lead_form").serialize();
		console.log(dataString)
		
		//AJAX
		$.ajax({
			url: "call_form/create_lead/",
			type: "POST",
			data:dataString,
			cache: false,
			statusCode: {
				200: function(response){
					if(response=="Lead already in the system!")
					{
						var lead_id = $("#input_id").val();
						if(lead_id=='')
						{
							alert(response);
							console.log("Duplicate Lead: "+response);
						}
						else
						{
							hide_all_slides();
							$('#age_slide').show();
							$("#age_question").focus();
							refresh_response_table();
						}
					}
					else
					{
						$("#input_id").val(response);
						console.log("New Lead: "+response);
						
						hide_all_slides();
						$('#age_slide').show();
						$("#age_question").focus();
						refresh_response_table();
					}
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
}//inbound_or_outbound_next
function knight_or_crst_next(){
	
	//MAKE SLIDE 5 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	var knight_resp = $("#knight_or_crst_question").val();
	var is_valid = true;
	
	if(knight_resp=="")
	{
		is_valid = false;
		alert("Please select a response.");
	}
	else if(knight_resp=="Yes" && is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$('#rhino_instructions_slide').show();
		$("#rhino_instructions_question").focus();
	}
	else if(is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$('#get_trucker_jobs_qualifier_slide').show();
		$("#get_trucker_jobs_qualifier_question").focus();
	}
	
}//transfer_to_lobos_next
function license_number_next(){
	
	//MAKE SLIDE 11 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	var license_number = $('#license_number_question').val();
	var is_valid = true;
	
	if(license_number=="")
	{
		is_valid = false;
		alert("Please write the license number");
	}
	if(is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$('#license_state_slide').show();
		$("#license_state_question").focus();
	}
}//license_number_next
function license_state_next(){
	
	//MAKE SLIDE 13 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	var license_state = $('#license_state_question').val();
	var drivers_needed = $("drivers_needed_filter").val();
	var states_question = $("#48_states_question").val();
	var is_valid = true;
	
	if(license_state=="")
	{
		is_valid = false;
		alert("Please select the license state.");
	}
	if(is_valid)
	{
		if(drivers_needed=="FALSE" || states_question=="No")
		{
			refresh_response_table();
			hide_all_slides();
			$('#knight_or_crst_slide').show();
			$("#knight_or_crst_question").focus();
		}
		else
		{
			refresh_response_table();
			hide_all_slides();
			$('#team_driving_slide').show();
			$("#team_driving_question").focus();
		}
	}
}//license_state_next
function non_qualifier_next(){
	refresh_response_table();
	
	//MAKE SLIDE 5 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	hide_all_slides();
	$('#form_complete_slide').show();
	$("#transferred_to").focus();
}//non_qualifier_next
function notes_on_lead_next(){
	refresh_response_table();
	var is_valid = true;
	var notes = $("#notes_on_lead_input").val();
	
	if(notes=="")
	{
		is_valid = false;
		alert("Please enter some notes.");
	}
	if(is_valid==true)
	{
		hide_all_slides();
		$("#submit_lead_slide").show();
		$("#submit_btn").focus();
	}
}
function number_of_accidents_next(){
	//MAKE SLIDE 10 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	
	var accidents = $("#number_of_accidents_question").val()
	var reason_for_call = $("#reason_for_call_question").val();
	var tickets = $("#number_of_tickets_question").val();
	var max_tickets = $("#max_tickets_filter").val();
	var credit_filter = $("#credit_filter").val();
	var is_valid = true;
	
	if(accidents=="")
	{
		is_valid = false;
		alert("Please select a number of tickets.");
	}
	else if(accidents=="0" && is_valid)
	{
		if(reason_for_call == "School")
		{
			if(credit_filter>0)
			{
				refresh_response_table();
				hide_all_slides();
				$("#credit_check_slide").show();
				$("#credit_check_question").focus();
			}
			else
			{
				refresh_response_table();
				hide_all_slides();
				$("#transfer_to_lobos_school_slide").show();
			}
		}
		else
		{
			refresh_response_table();
			hide_all_slides();
			$('#license_number_slide').show();
			$("#license_number_question").focus();
		}
	}
	else if(is_valid && accidents > 0)
	{
		refresh_response_table();
		hide_all_slides();
		$('#accident_explanation_slide').show();
		$("#accident_explanation_question").focus();
		
	}
}//number_of_accidents_next
function number_of_tickets_next(){
	var tickets = $("#number_of_tickets_question").val();
	var max_tickets = $("#max_tickets_filter").val();
	var reason_for_call = $("#reason_for_call_question").val();
	var is_valid = true;
	
	//MAKE SLIDE 8 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	if(tickets=="")
	{
		is_valid = false;
		alert("Please select a number of tickets.");
	}
	
	else if(tickets=="0" && is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$('#number_of_accidents_slide').show();
		$("#number_of_accidents_question").focus();
	}
	else
	{
		refresh_response_table();
		hide_all_slides();
		$('#ticket_explanation_slide').show();
		$("#ticket_explanation_question").focus();
	}
}//number_of_tickets_next
function otr_6_next(){
	
	//MAKE SLIDE 13 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	var resp = $("#otr_6_question").val();
	var is_valid = true;
	
	if(resp=="")
	{
		is_valid = false;
		alert("Please select a response.");
	}
	else if(resp=="Yes" && is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$('#availability_date_slide').show();
		$("#availability_date_question").focus();
	}
	else if(is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$('#knight_or_crst_slide').show();
		$("#knight_or_crst_question").focus();
	}
	
}//otr_6_next
function parole_slide_next(){
	var parole_question = $("#parole_question").val();
	var states_question = $("#48_states_question").val();
	var reason_for_call = $("#reason_for_call_question").val();
	var is_valid = true;
	
	if(parole_question=="")
	{
		is_valid = false;
		alert("Please enter a response");
	}
	if(parole_question=="Yes" && states_question=="")
	{
		is_valid = false;
		alert("Please enter a response");
	}
	if(is_valid && states_question=="No")
	{
		if(reason_for_call=="School")
		{
			refresh_response_table();
			hide_all_slides();
			$('#school_opportunity_slide').show();
			$("#school_opportunity_question").focus();
		}
		else if(reason_for_call=="Class A")
		{
			refresh_response_table();
			hide_all_slides();
			$('#license_number_slide').show();
			$("#license_number_question").focus();
		}
	}
	if(is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$('#number_of_tickets_slide').show();
		$("#number_of_tickets_question").focus();
	}
}
function refresh_response_table(){
	//INBOUND OR OUTBOUND
	$('#type_of_call_response').html($('#inbound_or_outbound_question').val());
	
	//REASON
	$('#reason_for_calling_response').html($('#reason_for_call_question').val());
	
	//NAME
	$('#first_name_response').html($('#first_name').val());
	$('#last_name_response').html($('#last_name').val());
	$("#transfer_first_name").html($('#first_name').val());
	$('#transfer_last_name').html($('#last_name').val());
	
	//PHONE
	var phone_number = $("#phone_number").val();
	//console.log(phone_number);
	if(phone_number=="")
	{
	}
	else
	{
		$('#phone_number_response').html("("+phone_number.slice(0,3)+") "+phone_number.slice(3,6)+"-"+phone_number.slice(6,10));
		$('#phone_number_script').html("("+phone_number.slice(0,3)+") "+phone_number.slice(3,6)+"-"+phone_number.slice(6,10));
		$('#transfer_phone').html("("+phone_number.slice(0,3)+") "+phone_number.slice(3,6)+"-"+phone_number.slice(6,10));
	}
	
	//ADDRESS
	$('#address_response').html($('#address').val());
	$('#city_response').html($('#city').val());
	$('#state_response').html($('#state').val());
	$("#zip_code_response").html($("#zip_code").val());
	$('#transfer_city').html($('#city').val());
	$('#transfer_state').html($('#state').val());
	
	//AGE
	$('#age_response').html($('#age_question').val());
	
	//BIRTHDAY
	var birthday = $('#birth_month').val() + " " + $('#birth_day').val() + ", " + $('#birth_year').val();
	if($('#birth_month').val()=="")
	{
	}
	else
	{
		$('#date_of_birth_response').html(birthday);
	}
	
	//NUMBER OF TICKETS
	$('#number_of_tickets_response').html($('#number_of_tickets_question').val());
	
	//TICKET DETAILS
	$("#ticket_details_response").html($('#ticket_explanation_question').val());
	
	//NUMBER OF ACCIDENTS
	$('#number_of_accidents_response').html($('#number_of_accidents_question').val());
	
	//ACCIDENT DETAILS
	$("#accident_details_response").html($('#accident_explanation_question').val());
	
	$("#credit_score_response").html($('#credit_score_input').val());
	
	//LICENSE NUMBER
	$('#license_number_response').html($('#license_number_question').val());
	$('#transfer_class_a').html($('#license_number_question').val());
	
	//LICENSE STATE
	$('#license_state_response').html($('#license_state_question').val());
	
	//TEAM DRIVING
	$('#team_driving_response').html($('#team_driving_question').val());
	
	//OTR FOR 6 WEEKS
	$('#otr_6_weeks_response').html($('#otr_6_question').val());
	
	//AVAILABILITY DATE
	$('#availability_date_response').html($('#availability_date_question').val());
	
	//TRANSFERRED TO
	$('#transferred_to_response').html($('#transferred_to').val());
	
	//SUBMITTED TO
	$('#submitted_to_response').html($('#submitted_to').val());
	
	//NOTES
	$('#notes_response').html($('#notes_on_lead_input').val());
}//refresh_response_table
function rhino_instructions_slide_next(){
	
	//MAKE SLIDE 5 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	var rhino_resp = $("#rhino_instructions_question").val();
	var is_valid = true;
	
	if(rhino_resp=="")
	{
		is_valid = false;
		alert("Please select a response.");
	}
	else if(rhino_resp=="Did not submit/not available" && is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$('#get_trucker_jobs_qualifier_slide').show();
		$("#get_trucker_jobs_qualifier_question").focus();
	}
	else if(is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$('#form_complete_slide').show();
		$("#transferred_to").focus();
	}
	
}//transfer_to_lobos_next
function school_opportunity_next(){
	
	//MAKE SLIDE 7 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	var school_decision = $("#school_opportunity_question").val();
	var is_valid = true;
	
	if(school_decision=="")
	{
		is_valid = false;
		alert("Please select a decision.");
	}
	else if(school_decision=="Yes" && is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$("#transfer_to_school_slide").show();
	}
	else if(is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$("#non_qualifier_slide").show();
	}
	
}//school_opportunity_next
function show_name_div(){
	//console.log("clicked");
	var reason_for_call_question = $("#reason_for_call_question").val();
	if(reason_for_call_question=="School" ||reason_for_call_question=="Class A")
	{
		$("#name_input").show();
		$("#name_script").show();
	}
	else
	{
		$("#name_input").hide();
		$("#name_script").hide();
		$("#first_name").val("");
		$("#last_name").val("");
	}
}
function source_of_call_next(){
	var source_of_call = $("#source_of_call_question").val();
	
	var is_valid = true;
	
	if(source_of_call=="")
	{
		is_valid=false;
		alert("Please provide the source of the phone number.");
	}
	if(is_valid==true)
	{
		hide_all_slides();
		$('#reason_for_call_slide').show();
		$("#reason_for_call_question").focus();
		refresh_response_table();
	}
}
function submit_lead_next(){
	hide_all_slides();
	$("#submission_confirmation_slide").show();
	dataString = $("#lead_form").serialize();
	console.log(dataString);
	var lead_id = $("#input_id").val();
	if(lead_id=="")
	{
		setTimeout(function(){
			location.reload();
		},1500);
	}
	else
	{
		setTimeout(function(){
			$.ajax({
				url: "call_form/update_lead/",
				type: "POST",
				data:dataString,
				cache: false,
				statusCode: {
					200: function(){
						//console.log("Lead Submitted.");
						location.reload();
					},
					404: function(){
						alert('Page not found');
					},
					500: function(){
						alert("500 error! "+response);
					},
				}
			})//ajax
			//$("#lead_form").submit();
		}, 3000);
	}
}
function team_driving_next(){
	
	//MAKE SLIDE 13 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	var team_response = $('#team_driving_question').val();
	var is_valid = true;
	
	if(team_response=="")
	{
		is_valid = false;
		alert("Please select a response.");
	}
	else if(team_response=="Yes" && is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$('#otr_6_slide').show();
		$("#otr_6_question").focus()
	}
	else if(is_valid)
	{
		refresh_response_table();
		hide_all_slides();
		$('#knight_or_crst_slide').show();
		$("#knight_or_crst_question").focus();
	}
}//team_driving_next
function ticket_explanation_next(){
	
	//MAKE SLIDE 9 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	var ticket_explanation = $('#ticket_explanation_question').val();
	var is_valid = true;
	
	if(ticket_explanation=="")
	{
		is_valid = false;
		alert("Please write in an explanation.");
	}
	else
	{
		refresh_response_table();
		hide_all_slides();
		$('#number_of_accidents_slide').show();
		$("#ticket_explanation_question").focus();
	}
}//ticket_explanation_next
function to_number_of_accidents(){
	hide_all_slides();
	$("#number_of_accidents_slide").show();
	$("#ticket_explanation_question").focus();
}
function to_type_of_call_question(){
	hide_all_slides();
	$('#inbound_or_outbound_slide').show();
	$("#inbound_or_outbound_question").focus();
	refresh_response_table();
}
function transfer_to_lobos_next(){
	refresh_response_table();
	
	//MAKE SLIDE 5 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	hide_all_slides();
	$('#form_complete_slide').show();
	$("#transfer_lobos_btn").focus();
}//transfer_to_lobos_next
function transfer_to_lobos_school_next(){
	hide_all_slides();
	$("#form_complete_slide").show();
	$("#transferred_to").focus();
}
function transfer_to_school_next(){
	refresh_response_table();
	
	//MAKE SLIDE 13 INVISIBLE, MOVE ON TO NEXT QUESTION, BASED ON RESPONSE
	hide_all_slides();
	$('#form_complete_slide').show();
	$("#transferred_to").focus();
}//transfer_to_school_next
function to_availability_date(){
	hide_all_slides();
	$("#availability_date_slide").show();
	$("#availability_date_question").focus();
}
function to_form_complete(){
	hide_all_slides();
	$("#form_complete_slide").show();
	$("#transferred_to").focus();
}
function to_notes(){
	hide_all_slides();
	$("#notes_on_lead_slide").show();
	$("#notes_on_lead_input").focus();
}
function valid_license_next(){
	var valid_license = $("#valid_license_question").val();
	
	hide_all_slides();
	
	if(valid_license=="Yes"){
		$("#address_slide").show();
	}else if(valid_license=="No"{
		$("#non_qualifier_slide").show();
	}
}



function hide_all_slides(){
	$('#inbound_or_outbound_slide').hide();
	$('#source_of_call_slide').hide();
	$('#reason_for_call_slide').hide();
	$('#name_slide').hide();
	$('#phone_number_slide').hide();
	$('#address_slide').hide();
	$('#age_slide').hide();
	$('#birthday_slide').hide();
	$('#number_of_tickets_slide').hide();
	$('#ticket_explanation_slide').hide();
	$('#number_of_accidents_slide').hide();
	$('#accident_explanation_slide').hide();
	$('#license_number_slide').hide();
	$('#license_state_slide').hide();
	$('#team_driving_slide').hide();
	$('#otr_6_slide').hide();
	$('#transfer_to_school_slide').hide();
	$('#form_complete_slide').hide();
	$('#transfer_to_lobos_slide').hide();
	$('#school_opportunity_slide').hide();
	$('#non_qualifier_slide').hide();
	$("#knight_or_crst_slide").hide();
	$("#get_trucker_jobs_qualifier_slide").hide();
	$("#rhino_instructions_slide").hide();
	$("#transfer_to_lobos_school_slide").hide();
	$("#availability_date_slide").hide();
	$("#submission_confirmation_slide").hide();
	$("#submit_lead_slide").hide();
	$("#notes_on_lead_slide").hide();
	$("#notes_on_lead_slide").hide();
	$("#credit_check_slide").hide();
	$("#parole_slide").hide();
	$("#email_slide").hide();
}//hide_all_slides










