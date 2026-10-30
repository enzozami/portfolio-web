(() => {
    const input = document.getElementById('table-search');

    if (!input) {
        return;
    }

    const rows = [...document.querySelectorAll('#products-table tbody tr')];
    const counter = document.getElementById('search-count');
    const emptyMessage = document.getElementById('search-empty');
    const table = document.getElementById('products-table');

    const normalize = (text) => text.normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim();

    const searchable = rows.map((row) => normalize(row.dataset.search || row.textContent));

    const filter = () => {
        const term = normalize(input.value);
        let visible = 0;

        rows.forEach((row, index) => {
            const matches = searchable[index].includes(term);
            row.hidden = !matches;
            visible += matches ? 1 : 0;
        });

        table.hidden = visible === 0;
        emptyMessage.hidden = visible > 0;
        counter.textContent = term === ''
            ? `${rows.length} produto(s)`
            : `${visible} de ${rows.length} produto(s)`;
    };

    input.addEventListener('input', filter);

    input.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            input.value = '';
            filter();
        }
    });
})();
