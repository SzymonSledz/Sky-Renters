var bars = document.getElementById('bars')
var nav = document.getElementById('mobile-nav-links')


bars.addEventListener('click', function(e) {
    nav.classList.toggle("bars-hidden");
});