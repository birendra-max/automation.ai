<script>
    document.title = 'Inbox | AI'
</script>

<?php
include 'inclu/hd.php';
?>

<section class="bg-gray-100 min-h-screen flex">
    <div class="flex-1 p-6">
        <!-- Toolbar -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <button class="text-gray-700 font-semibold bg-white border border-gray-300 rounded-lg px-4 py-2 hover:bg-gray-100">
                    Select All
                </button>
                <button class="text-gray-700 font-semibold bg-white border border-gray-300 rounded-lg px-4 py-2 hover:bg-gray-100">
                    Delete
                </button>
                <button class="text-gray-700 font-semibold bg-white border border-gray-300 rounded-lg px-4 py-2 hover:bg-gray-100">
                    Archive
                </button>
            </div>
            <div class="text-sm text-gray-500">
                <span id="email-count">1-50 of 1500</span>
            </div>
        </div>

        <!-- Emails Table -->
        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">
                            <input type="checkbox" class="form-checkbox text-blue-600" />
                        </th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Sender</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Subject</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Date</th>
                    </tr>
                </thead>
                <tbody id="email-table-body">
                    <!-- Email rows will be dynamically inserted here -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-6">
            <div class="text-sm text-gray-600">
                <span id="pagination-info">1-50 of 1500</span>
            </div>
            <div class="space-x-4">
                <button class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300" id="prev-btn">
                    Previous
                </button>
                <button class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300" id="next-btn">
                    Next
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    let currentPage = 1;
    let emailsPerPage = 50; // Set the number of emails per page
    let totalEmails = 1500; // Example total emails from the backend

    // Fetch emails from the backend API
    function fetchEmails() {
        const apiUrl = `/api/emails?page=${currentPage}&limit=${emailsPerPage}`;

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                renderEmailTable(data.emails);
                updatePagination(data.totalEmails);
            })
            .catch(error => console.error('Error fetching email data:', error));
    }

    // Render the email data into the table
    function renderEmailTable(emails) {
        const tableBody = document.getElementById('email-table-body');
        tableBody.innerHTML = ''; // Clear previous rows

        emails.forEach(email => {
            const row = document.createElement('tr');
            row.classList.add('border-b', 'hover:bg-gray-50', 'cursor-pointer');

            row.innerHTML = `
                <td class="py-3 px-4">
                    <input type="checkbox" class="form-checkbox text-blue-600" />
                </td>
                <td class="py-3 px-4 text-gray-700">${email.sender}</td>
                <td class="py-3 px-4 text-gray-700">${email.subject}</td>
                <td class="py-3 px-4 text-gray-500 text-sm">${new Date(email.date).toLocaleDateString()}</td>
            `;

            tableBody.appendChild(row);
        });
    }

    // Update pagination display
    function updatePagination(totalEmails) {
        const emailCountText = `Showing ${emailsPerPage * (currentPage - 1) + 1}-${Math.min(emailsPerPage * currentPage, totalEmails)} of ${totalEmails}`;
        document.getElementById('email-count').textContent = emailCountText;
        document.getElementById('pagination-info').textContent = emailCountText;

        document.getElementById('prev-btn').disabled = currentPage === 1;
        document.getElementById('next-btn').disabled = emailsPerPage * currentPage >= totalEmails;
    }

    // Handle page navigation
    document.getElementById('prev-btn').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            fetchEmails();
        }
    });

    document.getElementById('next-btn').addEventListener('click', () => {
        if (emailsPerPage * currentPage < totalEmails) {
            currentPage++;
            fetchEmails();
        }
    });

    // Initial fetch of emails on page load
    fetchEmails();
</script>



<?php
include 'inclu/footer.php';
?>