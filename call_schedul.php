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
        <h3 class="text-lg font-semibold mb-4">Uploaded Records</h3>
        <table class="w-full text-left border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Number</th>
                    <th class="px-4 py-2 border">Lab Name</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Call</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
        <div id="pagination" class="flex justify-center space-x-2 mt-4"></div>
    </div>
</section>

<script>
    let device;
    let currentConnection = null;
    
    // Ensure these elements exist in your HTML
    const fileInput = document.getElementById('fileInput'); // Define fileInput element
    const successMessage = document.getElementById('success-message'); // Define successMessage element
    const errorMessage = document.getElementById('error-message'); // Define errorMessage element

    window.onload = () => {
        setupTwilioClient();
        loadRecords(1);
    };

    fileInput.addEventListener('change', () => {
        const formData = new FormData();
        formData.append('fileInput', fileInput.files[0]);

        $.ajax({
            url: 'read_excl_date.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                console.log('File uploaded successfully: ', data);

                successMessage.textContent = data;
                successMessage.classList.remove('hidden');

                const fileName = fileInput.files[0].name;

                $('#success-message').text(`File "${fileName}" uploaded successfully`);

                loadRecords(1);
            },
            error: function(xhr, status, error) {
                console.error('Upload error:', error);
                errorMessage.classList.remove('hidden');
            }
        });

    });

    function loadRecords(page = 1) {
        $.ajax({
            url: 'fetch_call_records.php',
            type: 'GET',
            data: {
                page: page
            },
            dataType: 'json',
            success: function(data) {
                console.log('Fetched records:', data); // Debugging the fetched data

                const tbody = $('#tableBody');
                tbody.html('');

                if (!data.records || data.records.length === 0) {
                    tbody.html('<tr><td colspan="4" class="text-center p-4">No records found.</td></tr>');
                    return;
                }

                data.records.forEach(row => {
                    tbody.append(`
                    <tr>
                        <td class="border px-4 py-2">${row.phno}</td>
                        <td class="border px-4 py-2">${row.lab_name}</td>
                        <td class="border px-4 py-2">${row.status || 'Pending'}</td>
                        <td class="border px-4 py-2 text-center">
                            <button class="text-indigo-600 hover:text-indigo-900" onclick="callNow('${row.phno}')">
                                <i class="fas fa-phone"></i>
                            </button>
                        </td>
                    </tr>`);
                });

                $('#recordsTable').removeClass('hidden');

                const pagination = $('#pagination');
                pagination.html('');
                for (let i = 1; i <= data.totalPages; i++) {
                    pagination.append(`<button class="px-3 py-1 border ${i === data.currentPage ? 'bg-indigo-500 text-white' : 'bg-white'}" onclick="loadRecords(${i})">${i}</button>`);
                }
            },
            error: function(xhr, status, error) {
                console.error('Fetch error:', error);
                $('#tableBody').html(`<tr><td colspan="4" class="text-center p-4 text-red-500">Failed to load data.</td></tr>`);
            }
        });
    }

    function callNow(number) {
        const finalNumber = formatNumber(number);
        makeTwilioCall(finalNumber);
    }

    function makeTwilioCall(number) {
        if (!device || device.status() !== 'ready') {
            return alert('Twilio Client not ready');
        }
        device.connect({
            To: number
        });
        document.getElementById('mobileNumberDisplay').textContent = number;
        document.getElementById('mobileCallUI').classList.remove('hidden');
    }

    function formatNumber(num) {
        let formatted = num.replace(/\s+/g, '');
        if (!formatted.startsWith('+')) {
            formatted = '+' + formatted;
        }
        return formatted;
    }

    function setupTwilioClient() {
        $.ajax({
            url: 'token.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                device = new Twilio.Device(data.token, {
                    debug: true
                });

                device.on('ready', () => console.log('Twilio Device Ready'));
                device.on('error', error => alert('Twilio error: ' + error.message));
                device.on('connect', conn => {
                    currentConnection = conn;
                    $('#mobileCallUI').removeClass('hidden');
                });
                device.on('disconnect', () => {
                    currentConnection = null;
                    $('#mobileCallUI').addClass('hidden');
                });
                device.on('incoming', connection => {
                    currentConnection = connection;
                    $('#mobileNumberDisplay').text(connection.parameters.From);
                    $('#mobileCallUI').removeClass('hidden');
                });
            },
            error: function(xhr, status, error) {
                console.error('Twilio token error:', error);
                alert('Could not connect to Twilio');
            }
        });
    }

    function endCurrentCall() {
        if (currentConnection) currentConnection.disconnect();
        $('#mobileCallUI').addClass('hidden');
    }
</script>




<?php
include 'inclu/footer.php';
?>