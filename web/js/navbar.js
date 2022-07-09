function getNavbarState(){
  return $("body").hasClass("navbar-active") ? true : false;
}
function toggleNavbar(){
  if(getNavbarState()){
    hideNavbar();
  } else {
    showNavbar();
  }
}
function hideNavbar(event){
  if(!getNavbarState()) return false;
  if(event) event.preventDefault();

  $("body").removeClass("navbar-active");
  setCookie('navbar-state', 'hidden', 7);
  setTimeout(function(){
    if(typeof resizeToolbar == "function") resizeToolbar();
  }, 350);
}
function showNavbar(){
  if(getNavbarState()) return false;

  $("body").addClass("navbar-active");
  setCookie('navbar-state', 'shown', 7);
  setTimeout(function(){
    if(typeof resizeToolbar == "function") resizeToolbar();
  }, 350);

}

$old_width = $(window).width();
$(window).resize(function(){
  $w = $(window).width();
  if($w <= 1200 && $old_width != $w){
    hideNavbar();
  }

  $old_width = $w;
})

$(document).ready(function(){
  $navbar_state = getCookie('navbar-state') ? getCookie('navbar-state') : 'hidden';

  if( /*!$mobile && */ $navbar_state == "shown" ) {
    showNavbar();
  }
})

$("#content").on("click", function($event){
  $navbar_button = $($event.target).hasClass("navbar-toggler") ? true : false;
  if($mobile && getNavbarState() && !$navbar_button){
    if($event) $event.preventDefault();
    hideNavbar();
  }
})
