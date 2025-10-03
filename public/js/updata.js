
const sidebar = document.getElementById('sidebar');
const hamburger = document.getElementById('hamburger');
const overlay = document.getElementById('overlay');

function openSidebar(){
  sidebar.classList.add('open');
  overlay.classList.remove('hidden'); overlay.classList.add('show');
}
function closeSidebar(){
  sidebar.classList.remove('open');
  overlay.classList.add('hidden'); overlay.classList.remove('show');
}

hamburger.addEventListener('click', () => {
  if (sidebar.classList.contains('open')) closeSidebar(); else openSidebar();
});
overlay.addEventListener('click', closeSidebar);

function handleResize(){
  if (window.innerWidth > 900){
    sidebar.classList.remove('open');
    overlay.classList.add('hidden');
  }
}
window.addEventListener('resize', handleResize);