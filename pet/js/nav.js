/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
sticky nav bar scolling effect
*/

window.addEventListener('scroll', ()=>{
	var header = document.querySelector('header');
	header.classList.toggle('sticky', window.scrollY > 0);
	if(add_sticky_flag){
		header.classList.add('sticky');
	}
}); 