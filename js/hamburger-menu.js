// hamburger-menu.js
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');

    // Controleer of de elementen bestaan om fouten te voorkomen
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            // Toggle open klasse op menu
            navMenu.classList.toggle('open');
            // Toggle actieve klasse voor animatie
            hamburger.classList.toggle('active');
        });

        // 🔗 Optioneel: menu sluiten na klikken op een link (op mobiel)
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('open');
                hamburger.classList.remove('active');
            });
        });
    }
});