(() => {
    const form = document.querySelector('form[data-validate]');

    if (!form) {
        return;
    }

    const isEdit = form.dataset.isEdit === 'true';

    const length = (text) => [...text].length;

    const textRule = (label, { required, min = 0, max }) => (value) => {
        const text = value.trim();

        if (text === '') {
            return `${label} é ${required}.`;
        }
        if (min && length(text) < min) {
            return `${label} deve ter pelo menos ${min} caracteres.`;
        }
        if (length(text) > max) {
            return `${label} deve ter no máximo ${max} caracteres.`;
        }
        return '';
    };

    const rules = {
        nome: textRule('O nome do produto', { required: 'obrigatório', min: 3, max: 100 }),
        marca: textRule('A marca do produto', { required: 'obrigatória', min: 3, max: 100 }),
        cor: textRule('A cor do produto', { required: 'obrigatória', max: 50 }),
        senha: (value) => {
            if (!isEdit && value === '') {
                return 'A senha do produto é obrigatória.';
            }
            if (value !== '' && length(value) < 8) {
                return 'A senha do produto deve ter pelo menos 8 caracteres.';
            }
            return '';
        },
        valor: (value) => {
            const number = Number(value.replace(',', '.'));

            if (value.trim() === '' || !Number.isFinite(number) || number <= 0) {
                return 'O valor do produto é inválido.';
            }
            if (number > 99999999.99) {
                return 'O valor máximo é R$ 99.999.999,99.';
            }
            return '';
        },
    };

    const showError = (input, message) => {
        const feedback = document.getElementById(`${input.id}-feedback`);

        input.classList.toggle('is-invalid', message !== '');
        input.setAttribute('aria-invalid', message !== '' ? 'true' : 'false');

        if (feedback) {
            feedback.textContent = message;
        }
    };

    const validateField = (input) => {
        const message = rules[input.name](input.value);
        showError(input, message);
        return message === '';
    };

    const fields = Object.keys(rules)
        .map((name) => form.elements[name])
        .filter(Boolean);

    const touched = new Set();

    fields.forEach((input) => {
        input.addEventListener('blur', () => {
            touched.add(input);
            validateField(input);
        });

        input.addEventListener('input', () => {
            if (touched.has(input) || input.classList.contains('is-invalid')) {
                validateField(input);
            }
        });
    });

    form.addEventListener('submit', (event) => {
        const invalid = fields.filter((input) => !validateField(input));

        if (invalid.length > 0) {
            event.preventDefault();
            invalid.forEach((input) => touched.add(input));
            invalid[0].focus();
            return;
        }

        const submit = form.querySelector('[type="submit"]');
        submit.disabled = true;
        submit.textContent = 'Salvando...';
    });

    form.addEventListener('reset', () => {
        touched.clear();
        fields.forEach((input) => showError(input, ''));
    });
})();
