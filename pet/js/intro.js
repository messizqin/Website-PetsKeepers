/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
about page image slider
image slider is achieved by adding and removing classes
*/

const sliderImages = document.querySelectorAll('.slide'),
      arrowLeft = document.querySelector('#arrow-left'),
      arrowRight = document.querySelector('#arrow-right');

// hide navigation bar in image slider
const navheader = document.querySelector('.navheader'),
      complex = document.getElementById('complex'), 
      intro = document.querySelector('.intro'),
      home = document.querySelector('.home'),
      services = document.querySelector('.services');

// hide registeration, signin, password reset forms and services (booking and booking history)
const rform = document.getElementById('rform'),
      sform = document.getElementById('sform'),
      eform = document.getElementById('eform'),
      // booking
      booked = document.getElementById('booked'),
      // booking history
      tobook = document.getElementById('tobook');

var going; 
var cascading; 
var closed = false; 
var add_sticky_flag = false; 

let direction = null;
let current = 0; 

// iterate images
function * cascade(){
    for(let i = current; i < sliderImages.length; i++){
        yield i; 
    }
    for(let i = 0; i < current; i++){
        yield i; 
    }
}

var generator = cascade();
var control = 0; 

// reinitialize the image slider
// this function can be called in any other javascript files
function restart(){
    sliderImages.forEach(img=>{
        img.classList.remove('final');
        img.classList.remove('slide_out_left'); 
        img.classList.remove('slide_out_right');
        img.style.zIndex = '0';
    });
    direction = null; 
    current = 0;
    closed = false;  
    generator = cascade(); 
    control = 0;
    hiset(current);
    repeat();
}

// fake url change, actually display change
function url_manage(element, show_nav){
    navheader.style.display = 'none'; 
    intro.style.display = 'none'; 
    home.style.display = 'none'; 
    services.style.display = 'none'; 
    element.style.display = 'block'; 
    if([services].includes(element)){
        document.body.style.overflow = 'hidden'; 
        complex.classList.add('sticky');
        add_sticky_flag = true; 
    }else{
        document.body.style.overflow = 'visible';
        add_sticky_flag = false;
    }
    if(show_nav){
        navheader.style.display = 'block';
    }
}

// to about page, restart the image slider and hide everything behind
function go_about(){
    restart();
    url_manage(intro, false); 
}

// redirections
function go_home(){
    url_manage(home, true);
}

function go_services(){
    url_manage(services, true); 
}

// positional control 
function reset(ind){
    sliderImages[ind].style.zIndex = '0'; 
}

function toset(ind){
    sliderImages[ind].style.zIndex = '1'; 
}

function hiset(ind){
    sliderImages[ind].style.zIndex = '2'; 
}

// reinitialize slided image
function detrue(){
    if(current == 0){
        sliderImages[sliderImages.length - 1].classList.remove('slide_out_left'); 
        reset(sliderImages.length - 1); 
        hiset(current); 
    }else{
        sliderImages[current - 1].classList.remove('slide_out_left'); 
        reset(current - 1); 
        hiset(current); 
    }
}

function defalse(){
    if(current + 1 == sliderImages.length){
        sliderImages[0].classList.remove('slide_out_right'); 
        reset(0); 
        hiset(current); 
    }else{
        sliderImages[current + 1].classList.remove('slide_out_right');
        reset(current + 1); 
        hiset(current); 
    }
}

function roll(){
    if(direction == null) return; 
    if(direction){
        detrue(); 
    }else{
        defalse();
    }
}

// start with assigning the image with highest zindex 
window.addEventListener('load', hiset(current)); 

function slider_arrow_left(){
    roll(); 
    sliderImages[current].classList.add('slide_out_right');  
    current -= 1; 
    if(current == -1){
        current = sliderImages.length - 1;
    }
    toset(current);
    direction = false; 
}

// right arrow clicked
function slider_arrow_right(){
    roll(); 
    sliderImages[current].classList.add('slide_out_left');  
    current += 1;
    if(current == sliderImages.length){
        current = 0;
    } 
    toset(current); 
    direction = true; 
}

// deactivate auto slide
function close(){
    if(!closed){
        closed = true; 
        clearTimeout(going);
    }
}

arrowLeft.addEventListener('click', ()=>{
    slider_arrow_left(); 
    close(); 
}); 

arrowRight.addEventListener('click', ()=>{
    slider_arrow_right(); 
    close(); 
});

// support termiation fade out effect
function preset(){
    roll(); 
    var cas = []; 
    for(let i = current; i < sliderImages.length; i++){
        cas.push(i); 
    }
    for(let i = 0; i < current; i++){
        cas.push(i); 
    } 
    var a = sliderImages.length; 
    cas.forEach(i=>{
        a--; 
        sliderImages[i].style.zIndex = a; 
    }); 
}

// fade out effect calculation
function overload(){
    var gen = generator.next().value; 
    control += 1; 
    sliderImages[gen].classList.add('final'); 
    if(control == sliderImages.length) return;
    et = 2/(sliderImages.length);
    cascading = setTimeout(overload, et*1000); 
}

// show image fade out effect before displaying next page
function terminate(){
    clearTimeout(going);
    preset(); 
    overload();  
    setTimeout(go_home, 3000);
} 

// time control for each auto slide
function repeat(){
    slider_arrow_right(); 
    going = setTimeout(repeat, 4000);
}

// left right arrow click keyboard shortcut
function KeyPress(e){
    var evtobj = window.event? event : e; 
    if (evtobj.keyCode == 37){
        slider_arrow_left(); 
        close(); 
    }else if(evtobj.keyCode == 39){
        slider_arrow_right(); 
        close(); 
    }
}

document.onkeydown = KeyPress; 

// initialize with auto slide
$(document).ready(()=>{
    repeat(); 
}); 