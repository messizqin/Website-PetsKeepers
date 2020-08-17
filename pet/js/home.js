/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
home pages consists of three sections: textarea, google map and calendar
google maps and calendar has svg header
each of the section has a background image
*/

// svg location 
const lp = document.querySelectorAll('.location path'); 
// svg calendar
const cp = document.querySelectorAll('.calendar path');
// background images
const img1 = document.querySelector('.banner'); 
const img2 = document.querySelector('.canner'); 
const img3 = document.querySelector('.danner');
// textarea
const essay = document.querySelector('.essay'); 
const supply = document.querySelector('.supply');
// google map
const mapp = document.querySelector('.mapp');
// calendar
const calens = document.querySelectorAll('.cal'); 
const calcon = document.querySelector('.calcon'); 

// support section overflow calc
const maxis = window.innerHeight / 2.0;

// hide svg
function prelogo(lg){
	for(let i=0; i<lg.length; i++){
		var len = lg[i].getTotalLength(); 
		lg[i].style.strokeDasharray = len;
		lg[i].style.strokeDashoffset = len; 
		lg[i].style.animation = 'none'; 
	}
}

// show svg
function logo(lg){
	var fs = 0; 
	for(let i=0; i<lp.length; i++){
		var len = lg[i].getTotalLength(); 
		lg[i].style.strokeDasharray = len;
		lg[i].style.strokeDashoffset = len; 
		lg[i].style.animation = `anime 2s ease forwards ${fs}s`; 
		fs += 0.3; 
	}
}

// svg scroll control, if it's in page, display it
function topbottom(lg){
	var dif = window.innerHeight; 
	var tt = window.scrollY; 
	var bb = tt + dif; 
	if(lg[0].getBoundingClientRect().top > 0 && lg[0].getBoundingClientRect().bottom < window.innerHeight){
		logo(lg);
	}else{
		prelogo(lg); 
	}
}

// caculate image position in relation to page position
function axisaway(img){
	var iaxis = img.getBoundingClientRect().top + maxis; 
	if(-1 * maxis < iaxis && iaxis < 3 * maxis){
		return (Math.abs(maxis - iaxis))/(maxis * 2); 
	}else{
		return false; 
	}
}

// image blur control: opacity depends on how far away is the horizontal axis of the image to the page's horizontal axis
function imgblur(img){	
	var away = axisaway(img); 
	if(away.toString() != "false"){
		var opa = 0.2 + 0.8 * away;
		img.style.opacity = opa; 
	}
}

// support window resize
function article_align(){
	var wd = window.innerWidth; 
}

// support section overflowing position
function maxis_in(ind){
	var taxis = ((2 * ind) - 1) * maxis;
	if(window.scrollY < taxis && taxis < window.scrollY + 2 * maxis){
		return true; 
	}
	return false; 
}

// scroll control for textarea
function supply_control(){
	supply.style.top = `${0.4 * maxis - window.scrollY}px`; 
	if(maxis_in(1)){
		supply.classList.remove('ridof1'); 
		supply.classList.add('getin1'); 
	}else{
		supply.classList.remove('getin1'); 
		supply.classList.add('ridof1'); 
	}
}

// scroll control for google map
function map_control(){
	mapp.style.top = `${2.8 * maxis - window.scrollY}px`; 
	if(maxis_in(2)){
		mapp.classList.remove('ridof2'); 
		mapp.classList.add('getin2'); 
	}else{
		mapp.classList.remove('getin2'); 
		mapp.classList.add('ridof2'); 
	}
}

// scroll control for calendar
function calcon_control(){
	calcon.style.top = `${4.9 * maxis - window.scrollY}px`; 
	if(maxis_in(3)){
		calcon.classList.remove('ridof2'); 
		calcon.classList.add('getin2'); 
	}else{
		calcon.classList.remove('getin2'); 
		calcon.classList.add('ridof2'); 
	}
}

window.addEventListener('load', ()=>{
	// setup first section
	imgblur(img1);
	article_align();
	supply.style.top = '20vh';
	supply_control();
	map_control(); 
	calcon_control();
}); 

window.addEventListener('resize', ()=>{
	// rearange page by relative measurements
	article_align();
}); 

window.addEventListener('scroll', ()=>{
	// when scroll, these may be triggered: 
	//     background go blurry
	//     section scales
	//     path animation
	topbottom(lp); 
	topbottom(cp); 
	imgblur(img1);
	imgblur(img2);
	imgblur(img3);
	supply_control();
	map_control();
	calcon_control();
}); 
