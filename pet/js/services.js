/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
services including two different part:
	1. authentication
	2. booking
*/

// registration
const passwordEntry = document.getElementById('password'),
      passwordConfirmEntry = document.getElementById('confirm-password');

// authentication
const password_reset_btn = document.getElementById('password-reset-btn'),
      register_btn = document.getElementById('register-btn'),
      login_btn = document.getElementById('login-btn');

// registration, signin, password-reset: dismissable alert
const rform_alert = document.getElementById('rform-alert'),
      sform_alert = document.getElementById('sform-alert'),
      eform_alert = document.getElementById('eform-alert'),
      rform_dismiss = document.getElementById('rform-dismiss'),
      sform_dismiss = document.getElementById('sform-dismiss'),
      eform_dismiss = document.getElementById('eform-dismiss');

// booking: date, pet, intermediate forms
const dform = document.getElementById('booking-d'),
      pform = document.getElementById('booking-p'),
      iform = document.getElementById('booking-i');

// user id
var mydude;

// add booking
function bookingbtn_click(){
	$('#bookingbtn').hide();
	$(tobook).hide(); 
	$(booked).show();
	$(pform).hide();
	$(iform).hide();
	$(dform).show();
	$('#dform-error').html('');
	$('#pform-error').html('');
	$('#iform-error').html('');
}

// dismiss authentication alert
rform_dismiss.addEventListener('click', ()=>{rform_alert.style.display = 'none';});
sform_dismiss.addEventListener('click', ()=>{sform_alert.style.display = 'none';});
eform_dismiss.addEventListener('click', ()=>{eform_alert.style.display = 'none';});

// auto login by cookie
$(document).ready(function(){
	$.ajax({
		url: '../php/action.php', 
		method: 'POST', 
		data: 'action=checkCookie', 
	}).done(function(result){
		var data = JSON.parse(result);
		$('#sform-email').val(data.email);
		$('#sform-password').val(data.password);
	});
});

// display handle
function to_signin(){
	rform.style.display = 'none';
	eform.style.display = 'none';
	sform.style.display = 'block';	
}

function to_signup(){
	rform.style.display = 'block';
	eform.style.display = 'none';
	sform.style.display = 'none';
}

function to_password_reset(){
	eform.style.display = 'block';
	rform.style.display = 'none';
	sform.style.display = 'none';
}

// validate password unmatch by setting a regex for password confirmation
function setReference(){
	passwordConfirmEntry.setAttribute('pattern', passwordEntry.value);
}

passwordConfirmEntry.addEventListener('keydown', setReference);

// check if all information is checked to be valid by form.css
function all_valid(elements){
	var flag = true;
	elements.forEach(ip=>{
		if(ip.validity.valid){}else{
			flag = false;
		}
	});
	return flag; 
}

// registration: ajax add inactivated account
register_btn.addEventListener('click', e=>{
	var inputs = document.querySelectorAll('.rform-input');
	e.preventDefault();
	$('#loader').show();
	var formData = $('#register-form').serialize();
	if(all_valid(inputs)){
		$.ajax({
			url: '../php/action.php', 
			method: 'POST',
			data: formData + "&action=register" 
		}).done(function(result){
			$('#loader').hide();
			$('#rform-alert').show();
			$('.rform-result').html(result);
		})
	}
})

// login: set cookie
// authorize booking service
login_btn.addEventListener('click', e=>{
	var inputs = document.querySelectorAll('.sform-input');
	e.preventDefault();
	$('#loader').show();
	var formData = $('#signin-form').serialize();
	if(all_valid(inputs)){
		$.ajax({
			url: '../php/action.php',
			method: 'POST',
			data: formData + "&action=login",
			xhrFields: {withCredentials: true},
		}).done(function(result){
			$('#loader').hide();
			var data = JSON.parse(result);
			if(data.status == 0){
				$('#sform-alert').show();
				$('.sform-result').html(data.msg);
			}else{
				// if administer, redirect and quit
				if(data.msg == 'redirect'){
					window.location.href = data.path;
				}else{
					$('#btn-placeholder').html('<button id="bookingbtn" type="button" class="btn btn-outline-success btn-lg" onclick="bookingbtn_click();" >Add Booking</button>')
					// assigning user id to global javascript variable
					mydude = data.id;
					$.ajax({
						url: '../php/tobook.php',
						method: 'POST',
						data: {'infill': mydude},
					}).done(function(result){
						// login success: display booking history in services
						var data = JSON.parse(result);
						$(sform).hide();
						$(tobook).html(data.htm);
						$(tobook).show();
					});
				}
			}
		})
	}
})

password_reset_btn.addEventListener('click', e=>{
	event.preventDefault();
	$('#loader').show();
	var formData = $('#password-form').serialize();
	$.ajax({
		url: '../php/action.php', 
		method: 'POST', 
		data: formData + '&action=resetPass', 
	}).done(function(result){
		$('#loader').hide();
		var data = JSON.parse(result);
		$('#eform-alert').show();
		$('.eform-result').html(data.msg);
	});
});
