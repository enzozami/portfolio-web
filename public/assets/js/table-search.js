// Filtra as linhas da tabela enquanto o usuário digita na busca
function setupTableSearch() {
    const input = document.getElementById('table-search');

    if (!input) {
        return;
    }

    const rows = document.querySelectorAll('#products-table tbody tr');
    const table = document.getElementById('products-table');
    const counter = document.getElementById('search-count');
    const emptyMessage = document.getElementById('search-empty');

    function filter() {
        const term = input.value.toLowerCase().trim();
        let visible = 0;

        rows.forEach(function (row) {
            const text = row.dataset.search.toLowerCase();

            if (text.includes(term)) {
                row.hidden = false;
                visible++;
            } else {
                row.hidden = true;
            }
        });

        table.hidden = visible === 0;
        emptyMessage.hidden = visible > 0;

        if (term === '') {
            counter.textContent = rows.length + ' produto(s)';
        } else {
            counter.textContent = visible + ' de ' + rows.length + ' produto(s)';
        }
    }

    input.addEventListener('input', filter);

    input.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            input.value = '';
            filter();
        }
    });
}

setupTableSearch();
