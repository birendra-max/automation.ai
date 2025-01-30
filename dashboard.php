<script>
    document.title = 'Dashboard | AI'
</script>

<?php
include 'inclu/hd.php';
?>

<section id="dashboard" class="bg-gray-50 py-12">
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-xl">
        <!-- Dashboard Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-gray-800">Email Dashboard</h1>
            <p class="text-lg text-gray-600">Monitor your email statistics and track email status</p>
        </div>

        <!-- Dashboard Metrics Section -->
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-blue-100 p-6 rounded-xl shadow-md flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Emails</p>
                    <p class="text-3xl font-semibold text-gray-800" id="total-emails">0</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
            <div class="bg-green-100 p-6 rounded-xl shadow-md flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium text-gray-600">Sent Emails</p>
                    <p class="text-3xl font-semibold text-green-600" id="sent-emails">0</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l7 7-7 7" />
                </svg>
            </div>
            <div class="bg-red-100 p-6 rounded-xl shadow-md flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium text-gray-600">Failed Emails</p>
                    <p class="text-3xl font-semibold text-red-600" id="failed-emails">0</p>
                </div>
            </div>
        </div>

        <!-- Emails Table Section -->
        <div class="mt-10">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="flex justify-between mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Email Delivery Status</h2>

                    <!-- Records per Page Dropdown -->
                    <div class="flex items-center space-x-4">
                        <label for="records-per-page" class="text-lg text-gray-700">Show:</label>
                        <select id="records-per-page" class="px-4 py-2 bg-gray-100 rounded-lg shadow-sm" onchange="changeRowsPerPage(event)">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <!-- Emails Table -->
                <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                    <table class="min-w-full table-auto border-collapse">
                        <thead class="bg-gray-200 text-sm text-gray-600">
                            <tr>
                                <th class="py-3 px-4">
                                    <input type="checkbox" id="select-all" class="form-checkbox text-blue-600" onchange="toggleSelectAll()" />
                                </th>
<<<<<<< HEAD
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Email</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Subject</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Status</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 cursor-pointer" onclick="sortTable('date')">
                                    Date <span id="sort-date" class="inline-block ml-2">▲</span>
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Notification</th>
=======
                                <th class="py-3 px-4 cursor-pointer" onclick="sortTable('id')">ID <span id="sort-id">▲</span></th>
                                <th class="py-3 px-4">Email</th>
                                <th class="py-3 px-4">Subject</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 cursor-pointer" onclick="sortTable('date')">Date <span id="sort-date">▲</span></th>
                                <th class="py-3 px-4">Action</th>
>>>>>>> 716e5f61ed08a7f3b8c1112f6968cc831b0e4470
                            </tr>
                        </thead>
                        <tbody id="email-table-body">
                            <!-- Dynamically populated table rows -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-end items-center space-x-4">
                    <button id="prev-btn" class="btn-pagination" onclick="changePage('prev')" disabled>Previous</button>
                    <span class="text-sm font-medium text-gray-600">Page <span id="current-page">1</span> of <span id="total-pages">1</span></span>
                    <button id="next-btn" class="btn-pagination" onclick="changePage('next')" disabled>Next</button>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            let emailData = [];
            let currentPage = 1;
            let rowsPerPage = 10; // Default number of rows per page
            let selectedEmails = [];

            // Fetch email data from the backend using AJAX
            function fetchEmailData() {
                $.ajax({
                    url: 'getEmails.php', // Your backend endpoint
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        page: currentPage,
                        recordsPerPage: rowsPerPage,
                        sortColumn: 'date_sent', // Default sorting by date
                        sortOrder: 'DESC' // Default to descending order
                    },
                    success: function(data) {
                        console.log('Fetched Data:', data); // Log the fetched data
                        emailData = data.emails; // Assuming 'emails' is the array from the backend
                        updateEmailMetrics(data.totalEmails, data.sentEmails, data.failedEmails);
                        renderTable(getPaginatedData());
                        updatePagination(data.totalPages, data.currentPage);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching email data:', error);
                    }
                });
<<<<<<< HEAD
        }

        // Get paginated data for the current page
        function getPaginatedData() {
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;
            return emailData.slice(startIndex, endIndex);
        }

        // Render the table
        function renderTable(data) {
            const tableBody = document.getElementById('email-table-body');
            tableBody.innerHTML = '';

            let totalEmails = emailData.length;
            let sentEmails = emailData.filter(email => email.status === 'Sent').length;
            let failedEmails = emailData.filter(email => email.status === 'Failed').length;

            data.forEach(email => {
                const row = document.createElement('tr');
                row.classList.add('border-b', 'border-gray-200', 'hover:bg-gray-50', 'cursor-pointer');

                let truncatedSubject = truncateToChars(email.subject, 15);

                row.innerHTML = `
                <td class="py-3 px-4 text-sm text-gray-700">${email.id}</td>
                <td class="py-3 px-4 text-sm text-gray-700">${email.email}</td>
                <td class="py-3 px-4 text-sm text-gray-700">${truncatedSubject}</td>
                <td class="py-3 px-4 text-sm text-${email.status.toLowerCase() === 'sent' ? 'green' : email.status.toLowerCase() === 'failed' ? 'red' : 'orange'}-600 font-semibold">${email.status}</td>
                <td class="py-3 px-4 text-sm text-gray-700">${email.date}</td>
                <td class="py-3 px-4 text-sm">
                    <button class="py-4 px-1 relative border-2 border-transparent text-gray-800 rounded-full hover:text-gray-400 focus:outline-none focus:text-gray-500 transition duration-150 ease-in-out" aria-label="Mail" onclick="toggleMessageRow(${email.id},'${email.email}')">
                        <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M3 5h18l-9 7-9-7z"></path>
                            <path d="M3 5v14h18V5"></path>
                        </svg>
                        <span class="absolute inset-0 object-right-top -mr-6">
                            <div class="inline-flex items-center px-1.5 py-0.5 border-2 border-white rounded-full text-xs font-semibold leading-4 bg-red-500 text-white">
                            6
                            </div>
                        </span>
                    </button>

                </td>
            `;

                const messageRow = document.createElement('tr');
                messageRow.classList.add('hidden');
                messageRow.id = `message-row-${email.id}`;
                messageRow.innerHTML = `
                <td colspan="6" class="py-3 px-4 text-sm text-gray-700">
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800">Subject: ${email.subject}</h3>
                        <p class="text-gray-800 mt-2">${email.message}</p>
                        ${email.images ? `<div class="mt-4"><img src="${email.images}" alt="Email image" class="rounded-lg shadow-md w-full max-w-xs"/></div>` : ''}
                    </div>
                </td>
            `;

                tableBody.appendChild(row);
                tableBody.appendChild(messageRow);
            });

            document.getElementById('total-emails').textContent = totalEmails;
            document.getElementById('sent-emails').textContent = sentEmails;
            document.getElementById('failed-emails').textContent = failedEmails;
        }

        // Truncate the subject to a given number of characters
        function truncateToChars(text, charLimit) {
            if (text.length <= charLimit) {
                return text;
=======
>>>>>>> 716e5f61ed08a7f3b8c1112f6968cc831b0e4470
            }

            // Update email metrics (Total, Sent, Failed Emails)
            function updateEmailMetrics(totalEmails, sentEmails, failedEmails) {
                document.getElementById('total-emails').textContent = totalEmails;
                document.getElementById('sent-emails').textContent = sentEmails;
                document.getElementById('failed-emails').textContent = failedEmails;
            }

            // Get paginated data for the current page
            function getPaginatedData() {
                const startIndex = (currentPage - 1) * rowsPerPage;
                return emailData.slice(startIndex, startIndex + rowsPerPage);
            }

            // Render the table rows
            function renderTable(data) {
                const tableBody = document.getElementById('email-table-body');
                tableBody.innerHTML = ''; // Clear the table

                data.forEach(email => {
                    const row = document.createElement('tr');
                    row.classList.add('border-b', 'hover:bg-gray-50');
                    row.innerHTML = `
                        <td class="py-3 px-4">
                            <input type="checkbox" id="email-${email.id}" class="form-checkbox text-blue-600" onchange="toggleSelectEmail(${email.id})" />
                        </td>
                        <td class="py-3 px-4">${email.id}</td>
                        <td class="py-3 px-4">${email.email}</td>
                        <td class="py-3 px-4">${email.subject}</td>
                        <td class="py-3 px-4 text-${email.status === 'Sent' ? 'green' : 'red'}-600 font-semibold">${email.status}</td>
                        <td class="py-3 px-4">${email.date}</td>
                        <td class="py-3 px-4 text-blue-600 cursor-pointer" onclick="toggleMessageRow(${email.id})">View Message</td>
                    `;
                    tableBody.appendChild(row);
                });
            }

            // Update pagination controls
            function updatePagination(totalPages, currentPage) {
                document.getElementById('total-pages').textContent = totalPages;
                document.getElementById('current-page').textContent = currentPage;

                document.getElementById('prev-btn').disabled = currentPage === 1;
                document.getElementById('next-btn').disabled = currentPage === totalPages;
            }

            // Handle page changes (Previous/Next)
            function changePage(direction) {
                if (direction === 'prev' && currentPage > 1) {
                    currentPage--;
                } else if (direction === 'next' && currentPage < Math.ceil(emailData.length / rowsPerPage)) {
                    currentPage++;
                }
                fetchEmailData(); // Re-fetch data with updated page
            }

            // Sort the table by ID or Date
            function sortTable(column) {
                const sortOrder = document.getElementById(`sort-${column}`).textContent === '▲' ? 'ASC' : 'DESC';
                // Update the sort order indicator
                document.getElementById(`sort-${column}`).textContent = sortOrder === 'ASC' ? '▼' : '▲';

                $.ajax({
                    url: 'getEmails.php',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        page: currentPage,
                        recordsPerPage: rowsPerPage,
                        sortColumn: column,
                        sortOrder: sortOrder
                    },
                    success: function(data) {
                        emailData = data.emails;
                        renderTable(getPaginatedData());
                        updatePagination(data.totalPages, data.currentPage);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error sorting email data:', error);
                    }
                });
            }

            // Toggle the selection of individual emails
            function toggleSelectEmail(emailId) {
                const checkbox = document.querySelector(`#email-${emailId}`);
                if (checkbox.checked) {
                    selectedEmails.push(emailId);
                } else {
                    selectedEmails = selectedEmails.filter(id => id !== emailId);
                }
            }

            // Toggle the selection of all emails
            function toggleSelectAll() {
                const selectAllCheckbox = document.getElementById('select-all');
                selectedEmails = selectAllCheckbox.checked ? emailData.map(email => email.id) : [];
                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            }

            // Handle records per page change
            function changeRowsPerPage(event) {
                rowsPerPage = parseInt(event.target.value);
                currentPage = 1; // Reset to first page when filter is changed
                fetchEmailData(); // Re-fetch data with updated rows per page
            }

            // Initialize the data on page load
            document.addEventListener('DOMContentLoaded', fetchEmailData);

            // Style for pagination buttons
            document.querySelectorAll('.btn-pagination').forEach(button => {
                button.classList.add('px-4', 'py-2', 'bg-blue-500', 'text-white', 'rounded-lg', 'shadow-sm', 'hover:bg-blue-600', 'disabled:opacity-50');
            });
<<<<<<< HEAD

            document.getElementById(`sort-${column}`).textContent = sortOrder[column] === 'asc' ? '▲' : '▼';
            renderTable(getPaginatedData());
        }

        // Toggle the visibility of the message row
        function toggleMessageRow(emailId, email) {
            const messageRow = document.getElementById(`message-row-${emailId}`);
            console.log(email);
            messageRow.classList.toggle('hidden');
        }

        // Handle rows per page change
        function changeRowsPerPage(event) {
            rowsPerPage = parseInt(event.target.value, 10);
            currentPage = 1; // Reset to the first page
            renderTable(getPaginatedData());
            updatePagination();
        }

        function sendNotification(emailid) {
            console.log(emailid);
        }

        // Fetch data on page load
        document.addEventListener('DOMContentLoaded', fetchEmailData);
    </script>
=======
        </script>
    </div>
>>>>>>> 716e5f61ed08a7f3b8c1112f6968cc831b0e4470
</section>

<?php
include 'inclu/footer.php';
?>