(() => {
    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        const input = document.getElementById(button.dataset.togglePassword);
        const icon = button.querySelector('.bi');

        if (!input) {
            return;
        }

        button.addEventListener('click', () => {
            const showing = input.type === 'password';

            input.type = showing ? 'text' : 'password';
            icon.classList.toggle('bi-eye', !showing);
            icon.classList.toggle('bi-eye-slash', showing);

            // Mantém o estado acessível para leitores de tela
            button.setAttribute('aria-pressed', String(showing));
            button.setAttribute('aria-label', showing ? 'Ocultar senha' : 'Mostrar senha');
        });
    });
})();
