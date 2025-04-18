<script>
    document.title = 'Inbox | AI'
</script>

<?php
include 'inclu/hd.php';
?>

<section class="max-w-8xl mx-auto bg-white border border-gray-300 shadow-lg rounded-lg p-8 mt-2">
    <!-- Mobile Call UI -->
    <div id="mobileCallUI" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div id="draggableCallUI"
            class="w-[20%] h-[50%] bg-[#1a1a1a] text-white rounded-2xl shadow-2xl flex flex-col items-center justify-between py-10 px-8 cursor-move absolute">
            <div class="flex flex-col items-center">
                <div class="w-28 h-28 rounded-full bg-teal-600 flex items-center justify-center mb-6 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 10a4 4 0 100-8 4 4 0 000 8zm-6 8a6 6 0 1112 0H4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-3xl font-bold mb-2">Calling...</p>
                <p id="mobileNumberDisplay" class="text-lg text-teal-400">+0000000000</p>
            </div>

            <div class="grid grid-cols-4 gap-4 mt-8">
                <button id="pauseCall" class="w-14 h-14 rounded-full bg-yellow-400 flex items-center justify-center hover:bg-yellow-500 transition duration-300 shadow-md" title="Pause Call">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6 4h2v12H6V4zm6 0h2v12h-2V4z" />
                    </svg>
                </button>

                <button id="startRecording" class="w-14 h-14 rounded-full bg-blue-500 flex items-center justify-center hover:bg-blue-600 transition duration-300 shadow-md" title="Start Recording">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <circle cx="10" cy="10" r="5" />
                    </svg>
                </button>

                <button id="stopRecording" class="w-14 h-14 rounded-full bg-gray-600 flex items-center justify-center hover:bg-gray-700 transition duration-300 shadow-md" title="Stop Recording">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <rect x="6" y="6" width="8" height="8" />
                    </svg>
                </button>

                <button id="messageBox" class="w-14 h-14 rounded-full bg-green-600 flex items-center justify-center hover:bg-green-700 transition duration-300 shadow-md" title="Open Message Box">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v7a2 2 0 01-2 2H6l-4 4V5z" />
                    </svg>
                </button>
            </div>

            <div class="mt-6">
                <button id="closeCallUI" onclick="endCurrentCall()" class="w-14 h-14 rounded-full bg-red-600 flex items-center justify-center hover:bg-red-700 transition duration-300 shadow-md" title="End Call">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- File Upload Section -->
    <div class="w-full">
        <h3 class="text-lg font-semibold mb-4">Upload Your Numbers Excel File</h3>
        <form class="p-6 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:shadow-md transition-all" method="post" enctype="multipart/form-data">
            <input type="file" id="fileInput" name="phonefile" class="hidden" accept=".xls,.xlsx" required />
            <label for="fileInput" class="flex flex-col items-center cursor-pointer">
                <i class="fas fa-cloud-upload-alt text-indigo-600 text-5xl"></i>
                <p class="text-gray-600 mt-2">Drag & Drop files here or <span class="text-indigo-600 font-semibold">Browse</span></p>
                <div id="error-message" class="text-red-500 hidden">Please upload a valid Excel file (.xls, .xlsx).</div>
                <div id="success-message" class="text-green-500 font-bold hidden"></div>
            </label>
            <div id="fileList" class="mt-4"></div>
        </form>
    </div>

    <!-- Table Section -->
    <div id="recordsTable" class="mt-8 hidden">
        <div class="flex justify-between items-center mb-4">
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
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Number</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Lab Name</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Status</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">Call</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
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