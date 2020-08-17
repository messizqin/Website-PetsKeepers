/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
if user forget password they need to reset through the email they used to register
*/

// form elements
const passwordEntry = document.getElementById('password'),
      passwordConfirmEntry = document.getElementById('confirm-password'),
      reset_btn = document.getElementById('reset-btn'), 
      loader = document.getElementById('loader'), 
      talert = document.getElementById('tform-alert'),
      warning = document.querySelector('.tform-result');


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

reset_btn.addEventListener('click', e=>{
	if(all_valid([passwordEntry, passwordConfirmEntry])){
		$(loader).show();
		e.preventDefault();
		$.ajax({
			url: '../php/password.php', 
			method: 'POST',
			data: {'password': passwordConfirmEntry.value},
		}).done(function(result){
			$(loader).hide();
			var arr = JSON.parse(result);
			// failed, show message
			if(arr.status === 0){
				$(talert).show();
				$(warning).html(arr.msg);
			}else{
				// succeed, redirect
				window.location.href = arr.location;
			}
		});
	}
});
