// Validation du mot de passe
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password-confirm');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordHelp = document.getElementById('passwordHelp');
    const form = document.getElementById('registerForm');

    if (password && confirmPassword) {
        // Vérification de la force du mot de passe
        password.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            let message = '';

            if (val.length >= 8) strength += 25;
            if (val.match(/[a-z]+/)) strength += 25;
            if (val.match(/[A-Z]+/)) strength += 25;
            if (val.match(/[0-9]+/)) strength += 25;

            passwordStrength.style.width = strength + '%';

            if (strength < 50) {
                passwordStrength.className = 'progress-bar bg-danger';
                message = 'Faible';
            } else if (strength < 75) {
                passwordStrength.className = 'progress-bar bg-warning';
                message = 'Moyen';
            } else {
                passwordStrength.className = 'progress-bar bg-success';
                message = 'Fort';
            }

            if (passwordHelp) {
                passwordHelp.textContent = `Force du mot de passe: ${message}`;
            }
        });

        // Vérification de la correspondance des mots de passe
        confirmPassword.addEventListener('input', function() {
            if (this.value !== password.value) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }

    // Gestion des erreurs de formulaire
    if (form) {
        form.addEventListener('submit', function(e) {
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                e.preventDefault();
                terms.classList.add('is-invalid');
                alert('Veuillez accepter les conditions d\'utilisation');
            }

            if (password && confirmPassword && password.value !== confirmPassword.value) {
                e.preventDefault();
                confirmPassword.classList.add('is-invalid');
            }
        });
    }
});
