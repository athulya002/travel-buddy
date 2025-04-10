// File: ../public/js/dashboard.js
document.addEventListener('DOMContentLoaded', function () {
    // Target all sortable tables
    const tables = document.querySelectorAll('table[id]');
    if (!tables || tables.length === 0) {
        console.log('No sortable tables found on the page');
        return;
    }

    tables.forEach(table => {
        const headers = table.querySelectorAll('th[data-sort]');
        if (!headers || headers.length === 0) {
            console.log(`No sortable headers found in table ${table.id}`);
            return;
        }

        let sortDirection = {};

        headers.forEach(header => {
            header.addEventListener('click', () => {
                const sortKey = header.getAttribute('data-sort');
                const isAscending = sortDirection[sortKey] !== 'asc';
                sortDirection[sortKey] = isAscending ? 'asc' : 'desc';

                // Reset sort direction for other columns
                headers.forEach(h => {
                    if (h !== header) sortDirection[h.getAttribute('data-sort')] = undefined;
                });

                sortTable(table, sortKey, isAscending);
            });
        });
    });

    function sortTable(table, key, isAscending) {
        const tbody = table.querySelector('tbody');
        if (!tbody) {
            console.log(`No tbody found in table ${table.id}`);
            return;
        }

        const rows = Array.from(tbody.querySelectorAll('tr'));
        if (rows.length === 0) {
            console.log(`No rows found in table ${table.id}`);
            return;
        }

        rows.sort((a, b) => {
            let aValue = a.querySelector(`td:nth-child(${getColumnIndex(table, key) + 1})`).textContent.trim();
            let bValue = b.querySelector(`td:nth-child(${getColumnIndex(table, key) + 1})`).textContent.trim();

            // Handle special cases for sorting
            if (key === 'budget' || key === 'total_members') {
                aValue = aValue === 'N/A' ? -Infinity : parseFloat(aValue);
                bValue = bValue === 'N/A' ? -Infinity : parseFloat(bValue);
            } else if (key === 'travel_date' || key === 'created_at') {
                aValue = aValue === 'N/A' ? '' : new Date(aValue).getTime();
                bValue = bValue === 'N/A' ? '' : new Date(bValue).getTime();
            }

            if (aValue < bValue) return isAscending ? -1 : 1;
            if (aValue > bValue) return isAscending ? 1 : -1;
            return 0;
        });

        // Clear and re-append sorted rows
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }
        rows.forEach(row => tbody.appendChild(row));
    }

    function getColumnIndex(table, key) {
        const headers = table.querySelectorAll('th[data-sort]');
        const headersArray = Array.from(headers);
        return headersArray.findIndex(header => header.getAttribute('data-sort') === key);
    }
});