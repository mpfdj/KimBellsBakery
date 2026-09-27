    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contactForm');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Simpele feedback
                const naam = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const subject = document.getElementById('subject').value.trim();
                const bericht = document.getElementById('message').value.trim();

                if (naam && email && subject && bericht) {
                    alert('Bedankt voor je bericht, ' + naam + '! We nemen zo snel mogelijk contact met je op.');
                    form.reset();
                } else {
                    alert('Vul alle verplichte velden in (Naam, E-mail, Onderwerp en Bericht).');
                }
            });
        }
    });