/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
inside services, handling booking
dform: date form
pform: pet form
iform: intermidiate form: to add more pet, remove previous pet or submit the request
*/

const dform_sbtn = document.getElementById('booking-d-submit'),
	  pform_sbtn = document.getElementById('booking-p-submit'),
	  iform_pbtn = document.getElementById('isp');
	  iform_sbtn = document.getElementById('iss');
	  iform_abtn = document.getElementById('isa');

const dform_error = document.getElementById('dform-error'),
	  pform_error = document.getElementById('pform-error'),
	  iform_error = document.getElementById('');

// support to dataframe.php
var data_frame = {'pets':[]};

// only display date form when load
window.addEventListener('load', ()=>{
	$(pform).hide();
	$(iform).hide();
});

// immediate invoke function, set today's date as a hidden input in date submission form
// datababse: corresponding to booked in dates table 
(function getDate(){
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0
    var yyyy = today.getFullYear();
    if(dd<10){dd='0'+dd;}
    if(mm<10){mm='0'+mm;}
    today = mm+"/"+dd+"/"+yyyy;
    document.getElementById("todayDate").value = today;
})();

// submit dates form
dform_sbtn.addEventListener('click', ()=>{
	var datas = $(dform).serializeArray()
	var flag = true;
	// validate empty input
	datas.forEach(dt => {
		if(dt.value === ""){
			flag = false;
			$(dform_error).html('Please do not leave any blank');
		}
	});
	if(flag){
		var sendin = new Date(datas[0].value);
		var pickup = new Date(datas[1].value);
		var startdate = new Date(datas[2].value);
		// validate invalid date input
		if(sendin == startdate){
			$(dform_error).html('Both date cannot equal to each other');
		}else if(pickup <= sendin){
			$(dform_error).html('Pick up date must be after send in date');
		}else if(sendin < startdate){
			$(dform_error).html('You cannot book for past');
		}else if(pickup <= startdate){
			$(dform_error).html('You cannot book for past');
		}else{
			// set up dataframe
			$(dform_error).html('Succeed');
			data_frame['user_id'] = mydude;
			data_frame['startdate'] = startdate.valueOf()/1000;
			data_frame['sendin'] = sendin.valueOf()/1000;
			data_frame['pickup'] = pickup.valueOf()/1000;
			$(dform).hide();
			$(pform).show();
		}
	}
});

// pet form submission
pform_sbtn.addEventListener('click', ()=>{
	var datas = $(pform).serializeArray();
	var flag = true;
	// validate pet form empty input
	datas.forEach(dt => {
		if(dt.value === ""){
			flag = false;
			$(pform_error).html('Please do not leave any blank');
		}
	});
	// validate invalid pet age
	if(flag){
		var category = datas[0].value;
		var petname = datas[1].value;
		var petage = datas[2].value;
		var petweight = datas[3].value;
		if(parseInt(petage) <= 0){
			$(pform_error).html('Invalid age for your pet');
		}
		// creates a pets collector
		var di = {};
		di['category'] = category;
		di['petname'] = petname;
		di['petage'] = petage;
		di['petweight'] = petweight;
		// associates collector to dataframe as a child of array
		data_frame['pets'].push(di);
		$(pform).hide();
		$(iform).show();
	}
});

// intermidiate form: previous clicked, remove last pet item frome dataframe
iform_pbtn.addEventListener('click', ()=>{
	data_frame['pets'].splice(-1, 1);
	$(iform).hide();
	$(pform).show();
});

// intermidiate form: submission triggered: save data into database
// in services: display to booking history
iform_sbtn.addEventListener('click', ()=>{
	$(iform_error).html('Please wait...');
	$.ajax({
		url: '../php/dataframe.php',
		method: 'POST',
		data: {'dataframe': data_frame},
	}).done(function(result){
		$(iform_error).html(result + ". Updating data...");
		$.ajax({
			url: '../php/tobook.php',
			method: 'POST',
			data: {'infill': mydude},
		}).done(function(result){
			var data = JSON.parse(result);
			$(iform).hide();
			$(tobook).html(data.htm);
			$('#bookingbtn').show();
			$(tobook).show();
		})
	});
});

// intermidiate form: add button clicked: add more pets 
iform_abtn.addEventListener('click', ()=>{
	$(iform).hide();	
	$(pform).show();	
});








