// Pede confirmação em um modal antes de excluir um produto
function setupDeleteConfirm() {
    const modalElement = document.getElementById('delete-modal');
    const forms = document.querySelectorAll('form[data-delete-form]');

    // Sem o Bootstrap carregado, o formulário é enviado normalmente
    if (typeof bootstrap === 'undefined') {
        return;
    }

    const modal = new bootstrap.Modal(modalElement);
    const productName = modalElement.querySelector('[data-delete-name]');
    const confirmButton = modalElement.querySelector('[data-delete-confirm]');
    let selectedForm = null;

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            selectedForm = form;
            productName.textContent = form.dataset.productName;
            modal.show();
        });
    });

    confirmButton.addEventListener('click', function () {
        confirmButton.disabled = true;
        selectedForm.submit();
    });
}

setupDeleteConfirm();
