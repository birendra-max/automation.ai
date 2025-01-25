<script>
    document.title = 'Dashboard | AI'
</script>

<?php
include 'inclu/hd.php';
?>

<section>
    <div class="container mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
        <!-- Dashboard Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-semibold text-gray-800">Email Dashboard</h1>
            <p class="text-lg text-gray-600">Monitor email sending statistics and track email status</p>
        </div>

        <!-- Dashboard Metrics Section -->
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-blue-100 p-6 rounded-lg shadow-md">
                <div class="text-lg font-medium text-gray-700">Total Emails</div>
                <div class="text-4xl font-bold text-gray-900" id="total-emails">0</div>
            </div>
            <div class="bg-green-100 p-6 rounded-lg shadow-md">
                <div class="text-lg font-medium text-gray-700">Sent Emails</div>
                <div class="text-4xl font-bold text-green-600" id="sent-emails">0</div>
            </div>
            <div class="bg-red-100 p-6 rounded-lg shadow-md">
                <div class="text-lg font-medium text-gray-700">Failed Emails</div>
                <div class="text-4xl font-bold text-red-600" id="failed-emails">0</div>
            </div>
        </div>

        <!-- Emails Table Section -->
        <div class="mt-10">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-semibold text-gray-800">Email Send History</h2>
                </div>
                <!-- Records per Page Dropdown -->
                <div class="mt-4 mb-6">
                    <label for="records-per-page" class="text-lg text-gray-700">Filter : </label>
                    <select id="records-per-page" class="ml-2 px-3 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg" onchange="changeRowsPerPage(event)">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto bg-white border border-gray-200 rounded-lg shadow-sm">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 cursor-pointer" onclick="sortTable('id')">
                                    ID <span id="sort-id" class="inline-block ml-2">▲</span>
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Email</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Subject</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Status</th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600 cursor-pointer" onclick="sortTable('date')">
                                    Date <span id="sort-date" class="inline-block ml-2">▲</span>
                                </th>
                                <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Action</th>
                            </tr>
                        </thead>
                        <tbody id="email-table-body">
                            <!-- Dynamically populated table rows -->
                        </tbody>
                    </table>
                </div>

                <div class="w-full h-[10vh] flex justify-end items-center mt-6">
                    <div class="flex items-center space-x-4 bg-white border border-gray-200 shadow-sm px-4 py-2 rounded-lg">
                        <button
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 disabled:opacity-50"
                            id="prev-btn"
                            onclick="changePage('prev')"
                            disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </button>
                        <div class="text-sm font-medium text-gray-600">
                            Page <span id="current-page" class="font-bold text-gray-800">1</span> of <span id="total-pages" class="font-bold text-gray-800">1</span>
                        </div>
                        <button
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 disabled:opacity-50"
                            id="next-btn"
                            onclick="changePage('next')"
                            disabled>
                            Next
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        let emailData = []; // Store fetched data here
        let currentPage = 1;
        let rowsPerPage = 10; // Default number of rows per page
        let sortOrder = {
            id: 'asc',
            date: 'asc'
        }; // Sort order tracking

        // Fetch email data from the PHP endpoint
        function fetchEmailData() {
            fetch('getEmalis.php') // Replace with your actual API endpoint
                .then(response => response.json())
                .then(data => {
                    console.log("Fetched data:", data); // Debugging log
                    emailData = data; // Store data globally
                    // Apply default sorting before rendering
                    emailData.sort((a, b) => {
                        return sortOrder.id === 'asc' ? a.id - b.id : b.id - a.id;
                    });
                    renderTable(getPaginatedData());
                    updatePagination();
                })
                .catch(error => {
                    console.error('Error fetching email data:', error);
                });
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
            tableBody.innerHTML = ''; // Clear existing rows

            let totalEmails = emailData.length;
            let sentEmails = emailData.filter(email => email.status === 'Sent').length;
            let failedEmails = emailData.filter(email => email.status === 'Failed').length;

            data.forEach(email => {
                const row = document.createElement('tr');
                row.classList.add('border-b', 'border-gray-200', 'hover:bg-gray-50', 'cursor-pointer');

                // Truncate subject to 20 characters
                let truncatedSubject = truncateToChars(email.subject, 15);

                row.innerHTML = `
                <td class="py-3 px-4 text-sm text-gray-700">${email.id}</td>
                <td class="py-3 px-4 text-sm text-gray-700">${email.email}</td>
                <td class="py-3 px-4 text-sm text-gray-700">${truncatedSubject}</td>
                <td class="py-3 px-4 text-sm text-${email.status.toLowerCase() === 'sent' ? 'green' : email.status.toLowerCase() === 'failed' ? 'red' : 'orange'}-600 font-semibold">${email.status}</td>
                <td class="py-3 px-4 text-sm text-gray-700">${email.date}</td>
                <td class="py-3 px-4 text-sm text-blue-600 cursor-pointer" onclick="toggleMessageRow(${email.id})">View Message</td>
            `;

                const messageRow = document.createElement('tr');
                messageRow.classList.add('hidden');
                messageRow.id = `message-row-${email.id}`;
                messageRow.innerHTML = `
                <td colspan="6" class="py-3 px-4 text-sm text-gray-700">
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p class="text-gray-800">${email.message}</p>
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

        // Function to truncate subject to a given number of characters (20 characters for the subject field)
        function truncateToChars(text, charLimit) {
            if (text.length <= charLimit) {
                return text;
            }
            return text.slice(0, charLimit) + '...';
        }

        // Update pagination controls
        function updatePagination() {
            const totalPages = Math.ceil(emailData.length / rowsPerPage);
            document.getElementById('current-page').textContent = currentPage;
            document.getElementById('total-pages').textContent = totalPages;

            document.getElementById('prev-btn').disabled = currentPage === 1;
            document.getElementById('next-btn').disabled = currentPage === totalPages;
        }

        // Handle page changes
        function changePage(direction) {
            if (direction === 'prev' && currentPage > 1) {
                currentPage--;
            } else if (direction === 'next' && currentPage < Math.ceil(emailData.length / rowsPerPage)) {
                currentPage++;
            }
            renderTable(getPaginatedData());
            updatePagination();
        }

        // Sort the table
        function sortTable(column) {
            sortOrder[column] = sortOrder[column] === 'asc' ? 'desc' : 'asc';

            emailData.sort((a, b) => {
                if (column === 'id') {
                    return sortOrder[column] === 'asc' ? a.id - b.id : b.id - a.id;
                } else if (column === 'date') {
                    return sortOrder[column] === 'asc' ? new Date(a.date) - new Date(b.date) : new Date(b.date) - new Date(a.date);
                }
            });

            document.getElementById(`sort-${column}`).textContent = sortOrder[column] === 'asc' ? '▲' : '▼';
            renderTable(getPaginatedData());
        }

        // Toggle message row visibility
        function toggleMessageRow(emailId) {
            const messageRow = document.getElementById(`message-row-${emailId}`);
            messageRow.classList.toggle('hidden');
        }

        // Handle rows per page change
        function changeRowsPerPage(event) {
            rowsPerPage = parseInt(event.target.value, 10);
            currentPage = 1; // Reset to the first page
            renderTable(getPaginatedData());
            updatePagination();
        }

        // Fetch data on page load
        document.addEventListener('DOMContentLoaded', fetchEmailData);
    </script>

</section>


<?php
include 'inclu/footer.php';
?>