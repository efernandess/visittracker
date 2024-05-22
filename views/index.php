<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracked Visits</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mw-100 mt-5">
        <h1 class="mb-4">Tracked Visits</h1>
        <div id="visits-container">
            <table class="table table-responsive table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                        <th>Visited Page</th>
                        <th>Referrer</th>
                        <th>Visit DateTime</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Device</th>
                        <th>Screen Resolution</th>
                        <th>Browser</th>
                        <th>Browser Version</th>
                        <th>Operating System</th>
                    </tr>
                </thead>
                <tbody id="visits-tbody">
                </tbody>
            </table>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center" id="pagination">
                </ul>
            </nav>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const protocol = window.location.protocol;
            const host = window.location.host;
            const pathname = window.location.pathname;
            const baseUrl = protocol + '//' + host + pathname;
            let currentPage = 1;
            const perPage = 10;

            function fetchData(page) {
                fetch(`${baseUrl}?page=${page}&per_page=${perPage}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            populateTable(data.data);
                            setupPagination(data.total, page, perPage);
                        } else {
                            alert('Failed to fetch data');
                        }
                    });
            }

            function populateTable(visits) {
                const tbody = document.getElementById('visits-tbody');
                tbody.innerHTML = '';
                visits.forEach(visit => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                            <td>${visit.id}</td>
                            <td>${visit.ip_address}</td>
                            <td>${visit.user_agent}</td>
                            <td>${visit.visited_page}</td>
                            <td>${visit.referrer}</td>
                            <td>${visit.visit_datetime}</td>
                            <td>${visit.country}</td>
                            <td>${visit.city}</td>
                            <td>${visit.device}</td>
                            <td>${visit.screen_resolution}</td>
                            <td>${visit.browser}</td>
                            <td>${visit.browser_version}</td>
                            <td>${visit.operating_system}</td>
                        `;
                    tbody.appendChild(row);
                });
            }

            function setupPagination(totalItems, currentPage, perPage) {
                const totalPages = Math.ceil(totalItems / perPage);
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = '';

                const createPageItem = (page, label, disabled = false, active = false) => {
                    const li = document.createElement('li');
                    li.classList.add('page-item');
                    if (disabled) li.classList.add('disabled');
                    if (active) li.classList.add('active');

                    const a = document.createElement('a');
                    a.classList.add('page-link');
                    a.href = '#';
                    a.innerText = label;
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        if (!disabled && page !== currentPage) {
                            fetchData(page);
                        }
                    });

                    li.appendChild(a);
                    return li;
                };

                const maxVisiblePages = 5;
                let startPage, endPage;

                if (totalPages <= maxVisiblePages) {
                    startPage = 1;
                    endPage = totalPages;
                } else {
                    const halfVisible = Math.floor(maxVisiblePages / 2);
                    startPage = Math.max(1, currentPage - halfVisible);
                    endPage = Math.min(totalPages, currentPage + halfVisible);

                    if (currentPage <= halfVisible + 1) {
                        endPage = maxVisiblePages;
                    } else if (currentPage >= totalPages - halfVisible) {
                        startPage = totalPages - maxVisiblePages + 1;
                    }
                }

                pagination.appendChild(createPageItem(1, 'First', currentPage === 1));
                pagination.appendChild(createPageItem(currentPage - 1, 'Previous', currentPage === 1));

                if (startPage > 1) {
                    pagination.appendChild(createPageItem(startPage - 1, '...', false, false));
                }

                for (let i = startPage; i <= endPage; i++) {
                    pagination.appendChild(createPageItem(i, i, false, i === currentPage));
                }

                if (endPage < totalPages) {
                    pagination.appendChild(createPageItem(endPage + 1, '...', false, false));
                }

                pagination.appendChild(createPageItem(currentPage + 1, 'Next', currentPage === totalPages));
                pagination.appendChild(createPageItem(totalPages, 'Last', currentPage === totalPages));
            }

            fetchData(currentPage);
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>