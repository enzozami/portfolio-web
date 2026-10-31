// Valida o formulário de produto antes de enviar
function setupFormValidation() {
    const form = document.querySelector('form[data-validate]');

    if (!form) {
        return;
    }

    const isEdit = form.dataset.isEdit === 'true';
    const fields = ['nome', 'marca', 'senha', 'cor', 'valor'];

    // Retorna a mensagem de erro do campo, ou '' se estiver válido
    function getError(name, value) {
        const text = value.trim();

        if (name === 'nome') {
            if (text === '') return 'O nome do produto é obrigatório.';
            if (text.length < 3) return 'O nome do produto deve ter pelo menos 3 caracteres.';
            if (text.length > 100) return 'O nome do produto deve ter no máximo 100 caracteres.';
        }

        if (name === 'marca') {
            if (text === '') return 'A marca do produto é obrigatória.';
            if (text.length < 3) return 'A marca do produto deve ter pelo menos 3 caracteres.';
            if (text.length > 100) return 'A marca do produto deve ter no máximo 100 caracteres.';
        }

        if (name === 'senha') {
            if (!isEdit && value === '') return 'A senha do produto é obrigatória.';
            if (value !== '' && value.length < 8) return 'A senha do produto deve ter pelo menos 8 caracteres.';
        }

        if (name === 'cor') {
            if (text === '') return 'A cor do produto é obrigatória.';
            if (text.length > 50) return 'A cor do produto deve ter no máximo 50 caracteres.';
        }

        if (name === 'valor') {
            const number = Number(value);
            if (text === '' || isNaN(number) || number <= 0) return 'O valor do produto é inválido.';
            if (number > 99999999.99) return 'O valor máximo é R$ 99.999.999,99.';
        }

        return '';
    }

    // Mostra ou esconde o erro embaixo do campo
    function validateField(input) {
        const message = getError(input.name, input.value);
        const feedback = document.getElementById(input.id + '-feedback');

        if (message === '') {
            input.classList.remove('is-invalid');
            input.setAttribute('aria-invalid', 'false');
        } else {
            input.classList.add('is-invalid');
            input.setAttribute('aria-invalid', 'true');
        }

        feedback.textContent = message;

        return message === '';
    }

    fields.forEach(function (name) {
        const input = form.elements[name];

        input.addEventListener('blur', function () {
            validateField(input);
        });

        input.addEventListener('input', function () {
            if (input.classList.contains('is-invalid')) {
                validateField(input);
            }
        });
    });

    form.addEventListener('submit', function (event) {
        let firstInvalid = null;

        fields.forEach(function (name) {
            const input = form.elements[name];
            const valid = validateField(input);

            if (!valid && firstInvalid === null) {
                firstInvalid = input;
            }
        });

        if (firstInvalid !== null) {
            event.preventDefault();
            firstInvalid.focus();
            return;
        }

        const button = form.querySelector('button[type="submit"]');
        button.disabled = true;
        button.textContent = 'Salvando...';
    });

    form.addEventListener('reset', function () {
        fields.forEach(function (name) {
            const input = form.elements[name];
            input.classList.remove('is-invalid');
            input.setAttribute('aria-invalid', 'false');
            document.getElementById(input.id + '-feedback').textContent = '';
        });
    });
}

setupFormValidation();
