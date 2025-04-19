<script>
    document.title = 'Call Schudle';
</script>

<?php
include 'inclu/hd.php';
?>

<script src="public/js/uidraggable.js"></script>


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
                <button id="selectAllBtn" class="text-gray-700 font-semibold bg-white border border-gray-300 rounded-lg px-4 py-2 hover:bg-gray-100">
                    Select All
                </button>
                <button id="startBulkCallBtn" class="text-green-700 font-semibold bg-white border border-gray-300 rounded-lg px-4 py-2 hover:bg-green-100">
                    Call Start
                </button>
            </div>
            <div class="text-sm text-gray-500">
                <div class="text-sm text-gray-600 flex items-center space-x-4">
                    <span>Show:</span>
                    <select id="recordsPerPage" class="bg-white border border-gray-300 rounded-lg p-2 text-sm" onchange="loadRecords(1)">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                    </select>
                    <span>records per page</span>
                </div>
            </div>
        </div>

        <!-- Emails Table (same as before) -->
        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">
                            <input type="checkbox" id="select-all-checkbox" class="form-checkbox text-blue-600" />
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
    let device;
    let currentConnection = null;
    let currentPage = 1;
    let bulkNumbers = [];
    let bulkCallIndex = 0;

    let recordsPerPage = parseInt(document.getElementById('recordsPerPage').value);

    window.onload = () => {
        setupTwilioClient();
        loadRecords(currentPage);
    };

    function loadRecords(page = 1) {
        const limit = document.getElementById('recordsPerPage').value;

        $.ajax({
            url: 'fetch_call_records.php',
            type: 'GET',
            data: { page, limit },
            dataType: 'json',
            success: function(data) {
                const tbody = $('#tableBody');
                tbody.html('');

                if (!data.records || data.records.length === 0) {
                    tbody.html('<tr><td colspan="5" class="text-center p-4">No records found.</td></tr>');
                    return;
                }

                data.records.forEach(row => {
                    tbody.append(`
                        <tr data-id="${row.id}">
                            <td class="border px-4 py-2">
                                <input type="checkbox" class="form-checkbox text-blue-600" />
                            </td>
                            <td class="border px-4 py-2">${row.phno}</td>
                            <td class="border px-4 py-2">${row.lab_name}</td>
                            <td class="border px-4 py-2">${row.status || 'Pending'}</td>
                            <td class="border px-4 py-2 text-center">
                                <button class="text-indigo-600 hover:text-indigo-900" onclick="callNow('${row.phno}', ${row.id})">
                                    <i class="fas fa-phone"></i>
                                </button>
                            </td>
                        </tr>`);
                });

                $('#recordsTable').removeClass('hidden');
                updatePagination(data, limit);
            },
            error: function() {
                $('#tableBody').html(`<tr><td colspan="5" class="text-center p-4 text-red-500">Failed to load data.</td></tr>`);
            }
        });
    }

    function updatePagination(data, limit) {
        const paginationInfo = $('#pagination-info');
        paginationInfo.text(`Page ${data.currentPage} of ${data.totalPages}`);

        const prevBtn = $('#prev-btn');
        const nextBtn = $('#next-btn');

        const isPrevDisabled = data.currentPage === 1;
        const isNextDisabled = data.currentPage === data.totalPages;

        prevBtn.prop('disabled', isPrevDisabled)
            .toggleClass('cursor-not-allowed opacity-50', isPrevDisabled)
            .toggleClass('cursor-pointer opacity-100', !isPrevDisabled);

        nextBtn.prop('disabled', isNextDisabled)
            .toggleClass('cursor-not-allowed opacity-50', isNextDisabled)
            .toggleClass('cursor-pointer opacity-100', !isNextDisabled);

        prevBtn.off('click').on('click', () => loadRecords(data.currentPage - 1));
        nextBtn.off('click').on('click', () => loadRecords(data.currentPage + 1));
    }

    function formatNumber(num) {
        let formatted = num.replace(/\s+/g, '');
        if (!formatted.startsWith('+')) formatted = '+' + formatted;
        return formatted;
    }

    function callNow(number, id) {
        const finalNumber = formatNumber(number);
        makeTwilioCall(finalNumber, id);
    }

    function makeTwilioCall(number, recordId) {
        if (!device || device.status() !== 'ready') {
            alert('Twilio Client not ready');
            return;
        }

        $('#mobileNumberDisplay').text(number);
        $('#mobileCallUI').removeClass('hidden');

        const connection = device.connect({ To: number });
        currentConnection = connection;

        connection.on('disconnect', () => {
            currentConnection = null;
            $('#mobileCallUI').addClass('hidden');

            $.ajax({
                url: 'update_call_status.php',
                type: 'POST',
                data: { id: recordId },
                success: function(response) {
                    console.log('Status updated:', response);
                },
                error: function(xhr, status, err) {
                    console.error('Failed to update call status');
                    console.log('Response:', xhr.responseText);
                }
            });

            if (bulkNumbers.length > 0 && bulkCallIndex < bulkNumbers.length - 1) {
                bulkCallIndex++;
                setTimeout(() => {
                    startNextCall();
                }, 2500);
            } else {
                bulkNumbers = [];
                bulkCallIndex = 0;
            }
        });
    }

    function setupTwilioClient() {
        $.ajax({
            url: 'token.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                device = new Twilio.Device(data.token, { debug: true });

                device.on('ready', () => console.log('Twilio Device Ready'));
                device.on('error', error => alert('Twilio error: ' + error.message));
                device.on('disconnect', () => $('#mobileCallUI').addClass('hidden'));
            },
            error: function() {
                alert('Could not connect to Twilio');
            }
        });
    }

    function endCurrentCall() {
        if (currentConnection) {
            currentConnection.disconnect();
            currentConnection = null;
        }
        $('#mobileCallUI').addClass('hidden');
    }

    // Bulk call
    document.getElementById('startBulkCallBtn').addEventListener('click', () => {
        const selectedCheckboxes = $('#tableBody input[type="checkbox"]:checked');

        if (selectedCheckboxes.length === 0) {
            alert("Please select at least one number to call.");
            return;
        }

        bulkNumbers = [];
        selectedCheckboxes.each(function() {
            const row = $(this).closest('tr');
            const phoneNumber = row.find('td:nth-child(2)').text().trim();
            const id = row.data('id');
            bulkNumbers.push({ phone: phoneNumber, id });
        });

        bulkCallIndex = 0;
        startNextCall();
    });

    function startNextCall() {
        if (bulkCallIndex >= bulkNumbers.length) {
            alert("All calls completed.");
            return;
        }

        const { phone, id } = bulkNumbers[bulkCallIndex];
        const finalNumber = formatNumber(phone);
        makeTwilioCall(finalNumber, id);
    }
</script>


<?php
include 'inclu/footer.php';
?>