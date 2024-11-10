// script.js
document.addEventListener('DOMContentLoaded', function () {
    const dropdown = document.querySelector('.dropdown');
    dropdown.addEventListener('click', function () {
        const menu = dropdown.querySelector('ul');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    });
});
