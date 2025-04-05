// File: ../public/pages/dashboard.js
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('tripsTable');
    const headers = table.querySelectorAll('th');
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

            sortTable(sortKey, isAscending);
        });
    });

    function sortTable(key, isAscending) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.sort((a, b) => {
            let aValue = a.querySelector(`td:nth-child(${getColumnIndex(key) + 1})`).textContent.trim();
            let bValue = b.querySelector(`td:nth-child(${getColumnIndex(key) + 1})`).textContent.trim();

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

    function getColumnIndex(key) {
        const headersArray = Array.from(headers);
        return headersArray.findIndex(header => header.getAttribute('data-sort') === key);
    }
});