// Mostra ou oculta a senha ao clicar no botão do olho
function setupPasswordToggle() {
    const button = document.querySelector('[data-toggle-password]');

    if (!button) {
        return;
    }

    const input = document.getElementById('senha');
    const icon = button.querySelector('i');

    button.addEventListener('click', function () {
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
            button.setAttribute('aria-label', 'Ocultar senha');
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
            button.setAttribute('aria-label', 'Mostrar senha');
        }
    });
}

setupPasswordToggle();
