<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?=$title?></title>
		<link type="text/css" href="<?php echo base_url("css/index.css"); ?>" rel="stylesheet" />
		<link type="text/css" href="<?php echo base_url("css/jquery-ui.min.css"); ?>" rel="stylesheet" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url("js/jquery-ui.min.js"); ?>" ></script>
		<script src="<?php echo base_url("scripts/index.js"); ?>" ></script>
		<link href='http://fonts.googleapis.com/css?family=Roboto:300' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="<?=base_url("/favicon-16x16.png")?>" />
		<style>
			#call_form_tab{
				background-color: white;
				color:orange;
			}
		</style>
	</head>
	<?php include("call_form/call_form_scripts.js"); ?>
	<body>
		<div id="main_content" class="main_content">
			<?php include("main_menu.php"); ?>
			
			<div id="response_table" class="response_table">
				<div style="text-align:center;
				height:42px;line-height:50px;middle;font-weight:bold;
				font-size:24px;">Driver Info</div>
				<table>
					<tr>
						<td style="font-weight:bold;text-align:left; width:240px"><b>Question</b></td>
						<td style="font-weight:bold;text-align:left; width:240px"><b>Response</b></td>
					</tr>
					<tr>
						<td>Phone Number</td>
						<td><a class="response_link" onclick="to_type_of_call_question()" id="phone_number_response"></a></td>
					</tr>
					<tr>
						<td>Type of Call</td>
						<td><a class="response_link" onclick="to_type_of_call_question()" id="type_of_call_response"></a></td>
					</tr>
					<tr>
						<td>Reason for Calling</td>
						<td><a class="response_link" onclick="to_type_of_call_question()" id="reason_for_calling_response"></a></td>
					</tr>
					<tr>
						<td>First Name</td>
						<td><a class="response_link" onclick="to_type_of_call_question()" id="first_name_response"></a></td>
					</tr>
					<tr>
						<td>Last Name</td>
						<td><a class="response_link" onclick="to_type_of_call_question()" id="last_name_response"></a></td>
					</tr>
					<tr>
						<td>Address</td>
						<td><a class="response_link" onclick="inbound_or_outbound_next()" id="address_response"></a></td>
					</tr>
					<tr>
						<td>City</td>
						<td><a class="response_link" onclick="inbound_or_outbound_next()" id="city_response"></a></td>
					</tr>
					<tr>
						<td>State</td>
						<td><a class="response_link" onclick="inbound_or_outbound_next()" id="state_response"></a></td>
					</tr>
					<tr>
						<td>Zip Code</td>
						<td><a class="response_link" onclick="inbound_or_outbound_next()" id="zip_code_response"></a></td>
					</tr>
					<tr>
						<td>Age</td>
						<td><a class="response_link" onclick="address_next()" id="age_response"></a></td>
					</tr>
					<tr>
						<td>Date of Birth</td>
						<td><a class="response_link" onclick="age_next()" id="date_of_birth_response"></a></td>
					</tr>
					<tr>
						<td>Number of Tickets</td>
						<td><a class="response_link" onclick="birthday_next()" id="number_of_tickets_response"></a></td>
					</tr>
					<tr>
						<td>Ticket Details</td>
						<td><a class="response_link" onclick="number_of_tickets_next()" id="ticket_details_response"></a></td>
					</tr>
					<tr>
						<td>Number of Accidents</td>
						<td><a class="response_link" onclick="to_number_of_accidents()" id="number_of_accidents_response"></a></td>
					</tr>
					<tr>
						<td>Accident Details</td>
						<td><a class="response_link" onclick="number_of_accidents_next()" id="accident_details_response"></a></td>
					</tr>
					<tr>
						<td>Credit Score</td>
						<td><a class="response_link" onclick="accident_explanation_next()" id="credit_score_response"></a></td>
					</tr>
					<tr>
						<td>License Number</td>
						<td><a class="response_link" onclick="accident_explanation_next()" id="license_number_response"></a></td>
					</tr>
					<tr>
						<td>License State</td>
						<td><a class="response_link" onclick="license_number_next()" id="license_state_response"></a></td>
					</tr>
					<tr>
						<td>Team Driving</td>
						<td><a class="response_link" onclick="license_state_next()" id="team_driving_response"></a></td>
					</tr>
					<tr>
						<td>OTR for 6 Weeks</td>
						<td><a class="response_link" onclick="team_driving_next()" id="otr_6_weeks_response"></a></td>
					</tr>
					<tr>
						<td>Availability Date</td>
						<td><a class="response_link" onclick="to_availability_date()" id="availability_date_response"></a></td>
					</tr>
					<tr>
						<td>Transferred To</td>
						<td><a class="response_link" onclick="to_form_complete()" id="transferred_to_response"></a></td>
					</tr>
					<tr>
						<td>Submitted To</td>
						<td><a class="response_link" onclick="to_form_complete()" id="submitted_to_response"></a></td>
					</tr>
					<tr>
						<td>Notes</td>
						<td><a class="response_link" onclick="to_notes()" id="notes_response"></a></td>
					</tr>
				</table>
			</div>
			<div class="call_form_container">
				<?php $attributes = array('name'=>'lead_form','id'=>'lead_form','onsubmit'=>'return false;')?>
				<?=form_open(base_url('index.php/call_form/update_lead'),$attributes);?>
					<input id="input_id" name="input_id" type="hidden" value=""/>
					<input id="min_age_cdl_filter" name="min_age_cdl_filter" type="hidden" value="<?=$min_age_setting_cdl['setting_value'] ?>"/>
					<input id="max_age_cdl_filter" name="max_age_cdl_filter" type="hidden" value="<?=$max_age_setting_cdl['setting_value'] ?>"/>
					<input id="min_age_school_filter" name="min_age_school_filter" type="hidden" value="<?=$min_age_setting_school['setting_value'] ?>"/>
					<input id="max_age_school_filter" name="max_age_school_filter" type="hidden" value="<?=$max_age_setting_school['setting_value'] ?>"/>
					<input id="max_tickets_filter" name="max_tickets_filter" type="hidden" value="<?=$max_tickets_setting['setting_value'] ?>"/>
					<input id="drivers_needed_filter" name="drivers_needed_filter" type="hidden" value="<?=$drivers_needed_setting['setting_value'] ?>"/>
					<input id="credit_filter" name="credit_filter" type="hidden" value="<?=$credit_setting['setting_value'] ?>"/>
					<input id="location_filter" name="location_filter" type="hidden" value="<?=$location_setting['setting_value'] ?>"/>
					<div style="float:right" class="slide" id="accident_explanation_slide">
						<div class="slide_title">Accident Explanation</div>
						<div class="slide_script">
							"OK. Please explain the accident(s):"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Explanation
									</td>
									<td>
										<textarea style="float:right;" name="accident_explanation_question" id="accident_explanation_question" rows="4" cols="50"></textarea>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="accident_explanation_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="address_slide">
						<div class="slide_title">Home Address?</div>
						<div class="slide_script">
							"Great, thanks! Now could you give me your current home address?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Address
									</td>
									<td>
										<input class="call_form_input" type="text" name="address" id='address'>
									</td>
								</tr>
								<tr>
									<td>
										City
									</td>
									<td>
										<input class="call_form_input" type="text" name="city" id='city'>
									</td>
								</tr>
								<tr>
									<td>
										State
									</td>
									<td>
										<select class="call_form_input" name="state" id="state" style="width:203px;">
											<option value="">Select</option>
											<option>AL</option>
											<option>AK</option>
											<option>AZ</option>
											<option>AR</option>
											<option>CA</option>
											<option>CO</option>
											<option>CT</option>
											<option>DE</option>
											<option>FL</option>
											<option>GA</option>
											<option>HI</option>
											<option>ID</option>
											<option>IL</option>
											<option>IN</option>
											<option>IA</option>
											<option>KS</option>
											<option>KY</option>
											<option>LA</option>
											<option>ME</option>
											<option>MD</option>
											<option>MA</option>
											<option>MI</option>
											<option>MN</option>
											<option>MS</option>
											<option>MO</option>
											<option>MT</option>
											<option>NE</option>
											<option>NV</option>
											<option>NH</option>
											<option>NJ</option>
											<option>NM</option>
											<option>NY</option>
											<option>NC</option>
											<option>ND</option>
											<option>OH</option>
											<option>OK</option>
											<option>OR</option>
											<option>PA</option>
											<option>RI</option>
											<option>SC</option>
											<option>SD</option>
											<option>TN</option>
											<option>TX</option>
											<option>UT</option>
											<option>VT</option>
											<option>VA</option>
											<option>WA</option>
											<option>WV</option>
											<option>WI</option>
											<option>WY</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										Zip Code:
									</td>
									<td>
										<input class="call_form_input" id="zip_code" name="zip_code" type="text" />
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="address_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="age_slide">
						<div class="slide_title">Age</div>
						<div class="slide_script">
							"Ok perfect, thanks! The legal age to drive over the road is 21. Can you provide proof that you are 21 or older, <span id="age_first_name"></span>?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Age
									</td>
									<td>
										<select class="call_form_input" name="age_question" id="age_question">
							<option value="">Age</option>
							<option value="<21">Under 21</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							<option value="31">31</option>
							<option value="32">32</option>
							<option value="33">33</option>
							<option value="34">34</option>
							<option value="35">35</option>
							<option value="36">36</option>
							<option value="37">37</option>
							<option value="38">38</option>
							<option value="39">39</option>
							<option value="40">40</option>
							<option value="41">41</option>
							<option value="42">42</option>
							<option value="43">43</option>
							<option value="44">44</option>
							<option value="45">45</option>
							<option value="46">46</option>
							<option value="47">47</option>
							<option value="48">48</option>
							<option value="49">49</option>
							<option value="50">50</option>
							<option value="51">51</option>
							<option value="52">52</option>
							<option value="53">53</option>
							<option value="54">54</option>
							<option value="55">55</option>
							<option value="56">56</option>
							<option value="57">57</option>
							<option value="58">58</option>
							<option value="59">59</option>
							<option value="60">60</option>
							<option value="61">61</option>
							<option value="62">62</option>
							<option value="63">63</option>
							<option value="64">64</option>
							<option value="65">65+</option>
						</select>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="age_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="availability_date_slide">
						<div class="slide_title">Availability Date</div>
						<div class="slide_script">
							If you are interested, when would you be available to start working?
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Availability Date
									</td>
									<td>
										<input class="call_form_input" type="text" name="availability_date_question" id="availability_date_question"/>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="availability_date_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="birthday_slide">
						<div class="slide_title">Date of birth</div>
						<div class="slide_script">
							"Thanks! What is your date of birth?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
									Month
									</td>
									<td>
										<select style="width:203px;"class="call_form_input" name="birth_month" id="birth_month">
											<option value="">Month</option>
											<option>January</option>
											<option>February</option>
											<option>March</option>
											<option>April</option>
											<option>May</option>
											<option>June</option>
											<option>July</option>
											<option>August</option>
											<option>September</option>
											<option>October</option>
											<option>November</option>
											<option>December</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>
									Day
									</td>
									<td>
										<input placeholder="DD" class="call_form_input" type="number" name="birth_day" id="birth_day">
									</td>
								</tr>
								<tr>
									<td>
									Year
									</td>
									<td>
										<input placeholder="YYYY" class="call_form_input" type="number" name="birth_year" id="birth_year">
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="birthday_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="credit_check_slide">
						<div class="slide_title">Credit Check</div>
						<div class="slide_script" style="height:138px;">
							"OK, now we need to do a credit check. We do these checks through 
							Credit Karma dot com. This is a soft check which will not 
							affect your credit at all. Is that OK?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Response
									</td>
									<td>
										<select class="call_form_input" name="credit_check_question" id="credit_check_question">
											<option value="">Select</option>
											<option>Yes</option>
											<option>No</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<div id="credit_karma_script" name="credit_karma_script" style="display:none;">
							<div class="slide_script" style="height:150px;" >
								Go to <a href="https://www.creditkarma.com/signup" target="_blank">CreditKarma.com</a> 
								and go through the process with the lead to create an account. When
								you find the score, input it into the box.
							</div>
							<div class="slide_response">
								<table>
									<tr>
										<td>
											Credit Score
										</td>
										<td>
											<input class="call_form_input" name="credit_score_input" id="credit_score_input" type="number" />
										</td>
									</tr>
								</table>
							</div>
						</div>
						<button type="button" onclick="credit_check_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="email_slide">
						<div class="slide_title">Email Address</div>
						<div class="slide_script" style="height:138px;">
							"What is your email address?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Email Address
									</td>
									<td>
										<input class="call_form_input" name="email_input" id="email_input" type="email" />
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="email_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="form_complete_slide">
						<div class="slide_title">Submit Lead</div>
						<div class="slide_script">
							Once the call has been transferred or ended, answer these questions.
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Where did you submit the lead to?
									</td>
									<td>
										<select class="call_form_input" name="submitted_to" id="submitted_to">
											<option value="">Select</option>
											<option>Lobos CDL</option>
											<option>Lobos School</option>
											<option>Get Trucker Jobs CDL</option>
											<option>Get Trucker Jobs School</option>
											<option>Knight or CRST Dedicated</option>
											<option>Other</option>
											<option>Non-Recruiting Call</option>
										</select>
									</td>
								</tr>
							</table>
							<div id="transferred_to_div" name="transferred_to_div" style="display:none;">
								<table>
									<tr>
										<td>
											Who did you transfer the call to?
										</td>
										<td>
											<?php echo form_dropdown('transferred_to',$recruiter_options,'','id="transferred_to" class="call_form_input"');?>
										</td>
									</tr>
								</table>
							</div>
							<div id="reason_not_sold_div" style="display:none;margin-left:3px;margin-right:2px;padding-top:1px;">
								Why was the lead not sold? <textarea id="reason_not_sold_input" type="textbox" style="resize:none;float:right;width:300px;height:75px;margin-bottom:10px;"></textarea>
							</div>
						</div>
						<button type="button" onclick="form_complete_next()" class="next_button">Next</button>
					</div>
					<div style="float:right" class="slide" id="get_trucker_jobs_qualifier_slide">
						<div class="slide_title">Get Trucker Jobs Qualifier</div>
						<div class="slide_script">
							<p>"Are you willing to be on the road for at least 2 weeks at a time?"</p>
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Response
									</td>
									<td>
										<select class="call_form_input" id="get_trucker_jobs_qualifier_question">
											<option value="">Select</option>
											<option>Yes</option>
											<option>No</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="get_trucker_jobs_qualifier_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="inbound_or_outbound_slide">
						<div class="slide_title">Call Type</div>
						<div class="slide_script" style="height:119px;">
							<p>Lead Generated by: <?php echo $this->session->userdata('username'); ?></p>
							<p>"Hello, you've reached Lobos Interstate Services. 
							My name is <?php echo $this->session->userdata('first_name'); ?>.
							In case we get disconnected, what is the best 
							call-back number for you?"</p>
						</div>
						<div class="slide_response" style="margin-bottom:0px;height:107px;" >
							<table>
								<tr>
									<td>
										Is this an inbound or an outbound call?
									</td>
									<td>
										<select class="call_form_input" name="inbound_or_outbound_question" id="inbound_or_outbound_question">
											<option value="">Call Type</option>
											<option value="Inbound">Inbound</option>
											<option value="Outbound">Outbound</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										Call Source
									</td>
									<td>
										<select class="call_form_input" name="source_of_call_question" id="source_of_call_question">
											<option value="">Call Source</option>
											<option value="1">Craigslist</option>
											<option value="2">BackPage</option>
											<option value="3">Rhino</option>
											<option value="4">Other</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>
										Phone Number
									</td>
									<td>
										<input style="width:196px;" placeholder="555-555-5555" class="call_form_input" type="tel" name="phone_number" id="phone_number">
									</td>
								</tr>
							</table>
						</div>
						<div class="slide_script" style="height:25px;">
							"Great, thanks! How can I help you today?"
						</div>
						<div class="slide_response" style="height:20px;" >
							<table>
								<tr>
									<td>
										What is the reason for the call?
									</td>
									<td>
									<select onchange="show_name_div()" class="call_form_input" class="call_form_input" id="reason_for_call_question" name="reason_for_call_question">
										<option value="">Reason</option>
										<option value="School">CDL School</option>
										<option value="Class A">Class A CDL Driving</option>
										<option value="Wrong Number">Wrong Number</option>
										<option value="Ghost Call">Ghost Call</option>
										<option value="Other">Other</option>
									</select>
									</td>
								</tr>
							</table>
						</div>
						<div id="name_script" class="slide_script" style="height:50px;display:none;">
							"Excellent, you definitely called the right place. 
							We have great options for people looking to get 
							their CDL. What is your first and last name?"
						</div>
						<div id="name_input" class="slide_response" style="style:50px;display:none;">
							<table>
								<tr>
									<td>
										First Name
									</td>
									<td>
										<input onchange="add_first_name()" class="call_form_input" type="text" name="first_name" id="first_name" />
									</td>
								</tr>
								<tr>
									<td>
										Last Name
									</td>
									<td>
										<input class="call_form_input" type="text" name="last_name" id="last_name" />
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="inbound_or_outbound_next()" class="next_button" >CALL ANSWERED</button>
						<div id="call_form_extra_space"></div>
					</div>
					<div style="float:right" class="slide" id="knight_or_crst_slide">
						<div class="slide_title">Knight or CRST</div>
						<div class="slide_script">
							"Ok, it looks like we might have an opportunity 
							available to you through Knight or CRST dedicated. 
							Do either of those options sound good to you?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Response
									</td>
									<td>
										<select class="call_form_input" id="knight_or_crst_question">
											<option value="">Select</option>
											<option>Yes</option>
											<option>No</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="knight_or_crst_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="license_number_slide">
						<div class="slide_title">License Number</div>
						<div class="slide_script">
							"What is your license number?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										License Number
									</td>
									<td>
										<input class="call_form_input" name="license_number_question" id="license_number_question" type="text"></input>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="license_number_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="license_state_slide">
						<div class="slide_title">License State</div>
						<div class="slide_script">
							"What state is your license from?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										State
									</td>
									<td>
										<select class="call_form_input" name="license_state_question" id="license_state_question">
											<option value="">Select</option>
											<option>AL</option>
											<option>AK</option>
											<option>AZ</option>
											<option>AR</option>
											<option>CA</option>
											<option>CO</option>
											<option>CT</option>
											<option>DE</option>
											<option>FL</option>
											<option>GA</option>
											<option>HI</option>
											<option>ID</option>
											<option>IL</option>
											<option>IN</option>
											<option>IA</option>
											<option>KS</option>
											<option>KY</option>
											<option>LA</option>
											<option>ME</option>
											<option>MD</option>
											<option>MA</option>
											<option>MI</option>
											<option>MN</option>
											<option>MS</option>
											<option>MO</option>
											<option>MT</option>
											<option>NE</option>
											<option>NV</option>
											<option>NH</option>
											<option>NJ</option>
											<option>NM</option>
											<option>NY</option>
											<option>NC</option>
											<option>ND</option>
											<option>OH</option>
											<option>OK</option>
											<option>OR</option>
											<option>PA</option>
											<option>RI</option>
											<option>SC</option>
											<option>SD</option>
											<option>TN</option>
											<option>TX</option>
											<option>UT</option>
											<option>VT</option>
											<option>VA</option>
											<option>WA</option>
											<option>WV</option>
											<option>WI</option>
											<option>WY</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="license_state_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="non_qualifier_slide">
						<div class="slide_title">Non-Qualified Lead</div>
						<div class="slide_script">
							"Unfortunately, it doesn't look like you qualify for any of our opportunities 
							we have available at this time. If you become interested in the future, please 
							call us back. Thank you very much for calling in and have a wonderful day!"
						</div>
						<div class="slide_response">
							End the call.
						</div>
						<button type="button" onclick="non_qualifier_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="notes_on_lead_slide">
						<div class="slide_title">Notes on the Lead</div>
						<div class="slide_script">
							Please write some helpful notes in the space provided.
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Notes
									</td>
									<td>
										<textarea id="notes_on_lead_input" name="notes_on_lead_input" style="resize:none;float:right;width:300px;height:75px;margin-bottom:10px;"></textarea>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="notes_on_lead_next()" class="next_button">Next</button>
					</div>
					<div style="float:right" class="slide" id="number_of_accidents_slide">
						<div class="slide_title">Number of Accidents</div>
						<div class="slide_script">
							"Thank you. How many accidents have you been in over the past three years?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Number of Accidents
									</td>
									<td>
										<select class="call_form_input" name="number_of_accidents_question" id="number_of_accidents_question">
											<option value="">Select</option>
											<option>0</option>
											<option>1</option>
											<option>2</option>
											<option>3</option>
											<option>4</option>
											<option>5</option>
											<option>6</option>
											<option>7</option>
											<option>8</option>
											<option>9</option>
											<option>10+</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="number_of_accidents_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="number_of_tickets_slide">
						<div class="slide_title">Number of Tickets</div>
						<div class="slide_script">
							"How many tickets have you received in the past three years?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Number of Tickets
									</td>
									<td>
										<select class="call_form_input" name="number_of_tickets_question" id="number_of_tickets_question">
											<option value="">Select</option>
											<option>0</option>
											<option>1</option>
											<option>2</option>
											<option>3</option>
											<option>4</option>
											<option>5</option>
											<option>6</option>
											<option>7</option>
											<option>8</option>
											<option>9</option>
											<option>10+</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="number_of_tickets_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="otr_6_slide">
						<div class="slide_title">OTR Driving for 6 Weeks</div>
						<div class="slide_script">
							"Are you OK with driving over the road for 6 weeks at a time?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Response
									</td>
									<td>
										<select class="call_form_input" name="otr_6_question" id="otr_6_question">
											<option value="">Select</option>
											<option>Yes</option>
											<option>No</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="otr_6_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="parole_slide">
						<div class="slide_title">OTR Driving for 6 Weeks</div>
						<div class="slide_script" style="height:100px;">
							"Are you currently on parole?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Response
									</td>
									<td>
										<select class="call_form_input" name="parole_question" id="parole_question">
											<option value="">Select</option>
											<option>Yes</option>
											<option>No</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<div id="permission_question" style="display:none;">
							<div class="slide_script" style="height:100px;">
								"Do you have permission to travel in all 48 states?"
							</div>
							<div class="slide_response" style="height:100px;">
								<table>
									<tr>
										<td>
											Response
										</td>
										<td>
											<select class="call_form_input" name="48_states_question" id="48_states_question">
												<option value="">Select</option>
												<option>Yes</option>
												<option>No</option>
											</select>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<button type="button" onclick="parole_slide_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="rhino_instructions_slide">
						<div class="slide_title">Rhino Labs Instructions</div>
						<div class="slide_script">
							<p>Go to this website: http://crash.smartrhinolabs.com, and use 
							the username <b>IR_chasepaulson</b> and password: <b>Contactics1!</b> to login, click call 
							center, then call center forms, under search details, select "Possesses current, 
							valid commercial driver's license", input the city, state, and zip, and click "Find 
							Matches". after you do that, look for Knight or CRST Dedicated to be available, 
							if one is available ask the applicant who they would rather be submitted to and 
							start filling in the form. Before submitting the applicant's information, read the 
							following disclaimer below and make sure they are ok with all of this:</p>
							<p>"[Knight Transportation/CRST Dedicated, wherever you are submitting 
							their information] is looking for experienced drivers to drive freight all 
							around the United States. This is an 'Over The Road' position driving an 
							18-Wheeler Semi Truck where you could be gone for 1 to 3 weeks at a time. Is that 
							what you're looking for? They have Dry Van, Refrigerated, Intermodal and other 
							opportunities available to drivers in your area and have lots of perks available for 
							you. [Knight or CRST] will be contacting you 
							shortly. The phone number I have for you is <span id="phone_number_script"></span>. Is that correct? This 
							is a great opportunity, but it's very important that you answer your phone when they 
							call so that they can offer you a job. Will you make sure you watch for your phone 
							to ring for the next 24 hours and make sure you answer their call?"</p>
						</div>
						<div class="slide_response" style="margin-top:25px;">
							<table>
								<tr>
									<td>
										Submitted to
									</td>
									<td>
										<select class="call_form_input" id="rhino_instructions_question">
											<option value="">Select</option>
											<option>Knight</option>
											<option>CRST</option>
											<option>Did not submit/not available</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="rhino_instructions_slide_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="school_opportunity_slide">
						<div class="slide_title">Transfer to Get Trucker Jobs School</div>
						<div class="slide_script">
							"Ok, so I do have a great option for you to get your CDL. 
							I am going to transfer you to my friend over at Get 
							Trucker Jobs. They've got a ton of great opportunities 
							for applicants such as yourself that don't have their 
							CDLs yet. They will hook you up with a great trucking 
							company that will train you to become a truck driver 
							and you will drive a semi-truck across the nation 
							delivering important loads. The driving school is 
							typically a 2-3 week program where you will learn 
							everything about driving a semi-truck and get your CDL 
							license. After that you will drive with a trainer for 
							several weeks and get paid weekly. Then, the company that 
							provided your training will offer you different driving 
							positions driving one of their company trucks. How does 
							this opportunity sound?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Response
									</td>
									<td>
										<select class="call_form_input" id="school_opportunity_question">
											<option value="">Select</option>
											<option>Yes</option>
											<option>No</option>
										</select>
									</td>
								</tr>
							</table>
						</div>						
						<button type="button" onclick="school_opportunity_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="submit_lead_slide">
						<div class="slide_title">Submit Lead</div>
						<div class="slide_script">
							Click the button below to submit the lead.
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
									</td>
									<td>
									</td>
								</tr>
							</table>
						</div>
						<input id="submit_btn" type="button" value="Submit Lead" onclick="submit_lead_next()" action="" class="next_button"/>
					</div>
					<div style="float:right" class="slide" id="submission_confirmation_slide">
						<div class="slide_title">Lead Submitted!</div>
						<div class="slide_script">
							In a few moments, you will be directed back to the beginning.
							<div>
								<img style="position:relative;margin-top:50px;left:267px;" src="<?=base_url("images/ajax-loader.gif")?>"/>
							</div>
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
									</td>
									<td>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div style="float:right" class="slide" id="team_driving_slide">
						<div class="slide_title">Team Driving</div>
						<div class="slide_script">
							"Awesome! Would you be interested in driving team if 
							it meant you could make up to $1,500 dollars or more 
							per week?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Response
									</td>
									<td>
										<select class="call_form_input" name="team_driving_question" id="team_driving_question">
											<option value="">Select</option>
											<option>Yes</option>
											<option>No</option>
										</select>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="team_driving_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="ticket_explanation_slide">
						<div class="slide_title">Ticket Explanation</div>
						<div class="slide_script">
							"OK, could you please explain the ticket(s)?"
						</div>
						<div class="slide_response">
							<table>
								<tr>
									<td>
										Explanation
									</td>
									<td>
										<textarea style="float:right;" name="ticket_explanation_question" id="ticket_explanation_question" rows="4" cols="50"></textarea>
									</td>
								</tr>
							</table>
						</div>
						<button type="button" onclick="ticket_explanation_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="transfer_to_lobos_school_slide">
						<div class="slide_title">Transfer to Lobos Driving Academy</div>
						<div class="slide_script">
							"Excellent, I'm going to transfer you to Lobos Driving 
							Academy, they will be able to provide more details about 
							the school and what options are available to you, please 
							hold for just a moment, thanks"
						</div>
						<div class="slide_response">
							Transfer call to 355 and provide caller details.
						</div>
						<button type="button" onclick="transfer_to_lobos_school_next()" class="next_button">NEXT</button>
					</div>
					<div style="float:right" class="slide" id="transfer_to_lobos_slide">
						<div class="slide_title">Transfer the call to Lobos</div>
						<div class="slide_script">
							<p>"Awesome! That's great. I'm going to transfer you to Lobos 
							Interstate Services and they will be able to provide you more 
							information about this great career opportunity. Please stay 
							on the line."</p>
						</div>
						<div class="slide_response">
							Transfer Call To Lobos: Click Transfer, dial 355, Warm transfer, 
							wait for the other line to connect, provide name and number of driver, 
							and brief detail of you qualifying them, and then complete the transfer
						</div>
						<button id="transfer_lobos_btn" type="button" onclick="transfer_to_lobos_next()" class="next_button">Complete</button>
					</div>
					<div style="float:right" class="slide" id="transfer_to_school_slide">
						<div class="slide_title">Transfer to Get Trucker Jobs CDL</div>
						<div class="slide_script" style="height:0px;">
							<p>Ok, it looks like this opportunity is offered in the state where 
							you have your CDL and that you are qualified. I'm going to transfer you now."</p>
						</div>
						<div class="slide_response">
							<p>Provide Get Trucker Jobs the following information:</p>
							<p>First Name - <span id="transfer_first_name"></span></p>
							<p>Last Name - <span id="transfer_last_name"></span></p>
							<p>Phone # - <span id="transfer_phone"></span></p>
							<p>City - <span id="transfer_city"></span></p>
							<p>State - <span id="transfer_state"></span></p>
							<p>CLD Class A # - <span id="transfer_class_a"></span></p>
							<p>Transfer the call to (888)895-1317, wait for the line to pick up,
							provide details of applicant you have on the other line, name and phone number, 
							and complete transfer</p>
						</div>
						<button type="button" onclick="transfer_to_school_next()" class="next_button" style="margin-top:180px;">Complete</button>
					</div>
				</form>
			</div>
		</div>
	</body>
</html>