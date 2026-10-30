(() => {
    const forms = document.querySelectorAll('form[data-delete-form]');
    const modalElement = document.getElementById('delete-modal');

    if (forms.length === 0 || !modalElement) {
        return;
    }

    let pendingForm = null;

    forms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();

            if (!window.bootstrap) {
                if (window.confirm('Excluir este produto?')) {
                    form.submit();
                }
                return;
            }

            pendingForm = form;
            modalElement.querySelector('[data-delete-name]').textContent = form.dataset.productName;
            bootstrap.Modal.getOrCreateInstance(modalElement).show();
        });
    });

    modalElement.querySelector('[data-delete-confirm]').addEventListener('click', (event) => {
        if (!pendingForm) {
            return;
        }

        event.currentTarget.disabled = true;

        pendingForm.submit();
    });

    // Fechou sem confirmar: esquece o formulário pendente
    modalElement.addEventListener('hidden.bs.modal', () => {
        pendingForm = null;
    });
})();
