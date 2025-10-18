const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const hamburger = document.getElementById('hamburger');

hamburger.addEventListener('click', () => {
  sidebar.classList.toggle('show');
  overlay.classList.toggle('hidden');
});

overlay.addEventListener('click', () => {
  sidebar.classList.remove('show');
  overlay.classList.add('hidden');
});
