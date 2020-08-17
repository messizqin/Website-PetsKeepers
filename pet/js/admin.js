/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
admin.php first executed script
form similar to rpiform, archive `edit` and `delete`
*/

// delete confirmation
const cancel = document.getElementById('delete-cancel');
const proceed = document.getElementById('delete-proceed');
const cform = document.getElementById('delete-confirm');
const nscheck = document.getElementById('delete-notshow');

var showconfirm = true;
var cthrow;

// form always exist, but content
const wform = document.getElementById('admin-w');
// function collector supporting parser.php, after edit, refresh content
var last_called_parser; 

// hide form initially
window.addEventListener('load', ()=>{
	$(wform).toggle('wform-display');
	$(cform).toggle('alert-display');
});

// parse input form
function table_parser_header(){return "<div id='wform-error' class='booking-up'></div><div id='wform-dismiss' onclick=\"$(wform).toggle('wform-display')\">&#215;</div><div class='booking-mid'>";}
function table_parser_end(){return "</div><div id='booking-p-submit' class='booking-down'>Update</div>";}

function table_parser(di){
	var str = table_parser_header();
	for(const [k, v] of Object.entries(di)){
		if(!['table', 'id'].includes(k)){
			str += `<div id='${k}'>`;
			str += `<div class='inbox-title'>${k}</div>`;
			str += `<input id='${k}-input' name='${k}' class='otminput' type='text' value='${v}'/>`;
			str += `</div>`;
		}
	};
	str += `<input name="id" type="hidden" value="${di['id']}" />`;
	str += table_parser_end();
	return str;
}

// 2020-08-03 -> 08/03/2020
function reformat(val){
	var out = val.split('-');
	out.push(out.shift());
	return out.join('/');
}

// 08/03/2020 -> 2020-08-03
function rereformat(val){
	var out = val.split('/');
	out.unshift(out.pop());
	return out.join('-');
}

// dateform differences: datedropper.js input format and date input validtion
function dates_table_parser(di){
	var str = table_parser_header();
	var sendin = reformat(di['sendin']);
	var pickup = reformat(di['pickup']);
	str += `<div id='sendin'>`;
	str += `<div class='inbox-title'>sendin</div>`;
	str += `<input id='sendin-input' name='sendin' class='otminput' type='text' value='${sendin}' />`;
	str += `<script>$('#sendin-input').dateDropper({defaultDate:'${sendin}'});</script>`;
	str += `</div>`;
	str += `<div id='pickup'>`;
	str += `<div class='inbox-title'>pickup</div>`;
	str += `<input id='pickup-input' name='pickup' class='otminput' type='text' date-dd-theme='leaf' value='${pickup}' />`;
	str += `<script>$('#pickup-input').dateDropper({theme:'leaf', defaultDate:'${pickup}'});</script>`;
	str += `</div>`;
	str += `<input name="id" type="hidden" value="${di['id']}" />`;
	str += table_parser_end();
	return str;
}

// due to that admin.js is executed before parser.php, these functions are approach-able 
function emailValidate(str){if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(str)){return true;}return false;}
function ausPhoneValidate(str){if (/^(?:\+?(61))? ?(?:\((?=.*\)))?(0?[2-57-8])\)? ?(\d\d(?:[- ](?=\d{3})|(?!\d\d[- ]?\d[- ]))\d\d[- ]?\d[- ]?\d{3})$/.test(str)){return true;}return false;}
function isInt(value) {return !isNaN(value) && (function(x) {return (x | 0) === x;})(parseFloat(value));}

// {id: 1, firstname: john}
function get_input(){
	var arr = {};
	document.querySelectorAll('input').forEach(ip=>{
		arr[ip.getAttribute('name')] = ip.value;
	});
	return arr;
}

// triggered by form update button, update into database and reload content
function users_update(di){
	di['table'] = 'users';
	$.ajax({
		url: '../php/update.php',
		method: 'POST',
		data: di,
	}).done(function(result){
		if(result.trim() === 'success'){
			// reload content 
			last_called_parser();
			$(wform).toggle('wform-display');
		}else{
			document.getElementById('wform-error').innerHTML = result.trim();
		}
	});
}

// date update requires complex format conversion, update.php->dates.php ultilised $sdate = date('Y-m-d H:i:s', $sendin);
function dates_update(di){
	var ds = new Date(di['sendin']);
	var dsm = ds.valueOf() / 1000;
	var ps = new Date(di['pickup']);
	var psm = ps.valueOf() / 1000;
	var duration = (psm-dsm) / 86400;
	di['table'] = 'dates';
	di['sendin'] = dsm;
	di['pickup'] = psm;
	di['duration'] = duration;
	$.ajax({
		url: '../php/update.php',
		method: 'POST',
		data: di,
	}).done(function(result){
		if(result.trim() === 'success'){
			// reload content 
			last_called_parser();
			$(wform).toggle('wform-display');
		}else{
			document.getElementById('wform-error').innerHTML = result.trim();
		}
	});
}

function pets_update(di){
	di['table'] = 'pets';
	$.ajax({
		url: '../php/update.php',
		method: 'POST',
		data: di,
	}).done(function(result){
		if(result.trim() === 'success'){
			// reload content 
			last_called_parser();
			$(wform).toggle('wform-display');
		}else{
			document.getElementById('wform-error').innerHTML = result.trim();
		}
	});
}

// form submission eventlistener, trigger validation message
function script_render(scr){return `<script type="text/javascript">document.getElementById("booking-p-submit").addEventListener("click", ()=>{${scr}})</script>`;}

function users_script(){
	var str = '';
	str += `if(!emailValidate(document.getElementById('email-input').value)){document.getElementById('wform-error').innerHTML = 'Invalid Email'; return;};`;
	str += `if(!ausPhoneValidate(document.getElementById('phone-input').value)){document.getElementById('wform-error').innerHTML = 'Invalid Phone'; return;};`;
	str += `if(document.getElementById('firstname-input').value.length>20){document.getElementById('wform-error').innerHTML = 'firstname field exceeds max length of 20'; return;};`;
	str += `if(document.getElementById('lastname-input').value.length>20){document.getElementById('wform-error').innerHTML = 'last field name exceeds max length of 20'; return;};`;
	str += `if(document.getElementById('email-input').value.length>60){document.getElementById('wform-error').innerHTML = 'email field exceeds max length of 60'; return;};`;
	// validation passed, save into database
	str += `document.getElementById('wform-error').innerHTML = 'Processing...';`;
	str += `users_update(get_input());`;
	str += ``;
	return script_render(str);
}

function dates_script(){
	var str = '';
	str += `var sendin = document.getElementById('sendin-input').value;`;
	str += `var pickup = document.getElementById('pickup-input').value;`;
	str += "var dsendin = new Date(`${sendin}`);var stime = dsendin.getTime();";
	str += "var dpickup = new Date(`${pickup}`);var ptime = dpickup.getTime();";
	str += `if(stime >= ptime){document.getElementById('wform-error').innerHTML = 'Pickup date cannot be before sendin date'; return;}`;
	// validation passed, save into database
	str += `document.getElementById('wform-error').innerHTML = 'Processing...';`;
	str += `dates_update(get_input());`;
	str += ``;
	return script_render(str);
}

function pets_script(){
	var str = '';
	str += `if(!isInt(document.getElementById('petage-input').value)){document.getElementById('wform-error').innerHTML = 'Age field requires whole number'; return;};`;
	str += `if(document.getElementById('category-input').value.length>50){document.getElementById('wform-error').innerHTML = 'category field exceeds max length of 50'; return;};`;
	str += `if(document.getElementById('petname-input').value.length>20){document.getElementById('wform-error').innerHTML = 'petname field exceeds max length of 20'; return;};`;
	str += `if(document.getElementById('petage-input').value.length>3){document.getElementById('wform-error').innerHTML = 'petage field exceeds max length of 3'; return;};`;
	str += `if(document.getElementById('petweight-input').value.length>20){document.getElementById('wform-error').innerHTML = 'petweight field exceeds max length of 20'; return;};`;
	// validation passed, save into database
	str += `document.getElementById('wform-error').innerHTML = 'Processing...';`;
	str += `pets_update(get_input());`;
	str += ``;
	return script_render(str);
}

// edit button click
function start_edit(di){
	switch(di.table){
		case 'users':
			$(wform).html(table_parser(di) + users_script());
		break; 
		case 'dates':
			$(wform).html(dates_table_parser(di) + dates_script());
		break; 
		case 'pets':
			$(wform).html(table_parser(di) + pets_script());
		break; 
	}
	$(wform).toggle('wform-display');
}

// execute delete
function delete_record(di){
	$.ajax({
		url: '../php/cascade.php', 
		method: 'POST', 
		data: {'table': di.table, 'id': di.id},
	}).done(function(result){
		last_called_parser();
	});
}

// delete button click
function confirm_delete(di){
	if(showconfirm){
		$(cform).toggle('alert-display');
		cthrow = di;
	}else{
		// run delete
		delete_record(di);
	}
}

// delete button click: confirm dialog, cancel, continue and not show this dialog again
cancel.addEventListener('click', ()=>{
	$(cform).toggle('alert-display');
});

proceed.addEventListener('click', ()=>{
	$(cform).toggle('alert-display');
	if(nscheck.checked){
		showconfirm = false;
	}
	// run delete
	delete_record(cthrow);
});


