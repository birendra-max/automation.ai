<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/vdm88rk1hbkg7kux43ocr5zlfljqs27j8bihqcze2gjb9wkj/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <section class="mt-12">
        <div class="bg-gray-100 text-black rounded-3xl shadow-xl w-full overflow-hidden mx-auto max-w-5xl">
            <div class="w-full flex justify-center items-center">
                <form class="w-full py-10 px-5 md:px-10" id="emailform" enctype="multipart/form-data">
                    <div class="text-center mb-10">
                        <h1 class="font-bold text-3xl text-gray-900">Email Automation</h1>
                        <p class="text-gray-600">Upload your Excel file to send emails. Send a batch of emails with a single click.</p>
                    </div>

                    <!-- File Upload Section -->
                    <div class="flex flex-col mb-6 mx-8">
                        <div id="FileUpload" class="block w-full py-6 px-6 relative bg-white border-2 border-gray-300 rounded-md hover:shadow-lg focus:ring-2 focus:ring-indigo-500 transition-all duration-300">
                            <input type="file" accept=".xlsx,.xls" name="emailfile" class="absolute inset-0 m-0 p-0 w-full h-full opacity-0 cursor-pointer" id="fileInput" required>
                            <div id="fileDetails" class="mt-4"></div>
                        </div>

                        <!-- Dropdown Section -->
                        <div class="mt-6" id="dropdownContainer" style="display: none;">
                            <label for="dropdown" class="block text-sm font-medium text-gray-700">Select a Row:</label>
                            <select id="dropdown" class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-2">
                                <!-- Options will be dynamically inserted here -->
                            </select>
                        </div>

                        <!-- Email Form Section -->
                        <div class="mt-6" id="emailBox" style="display: none;">
                            <h2 class="text-lg font-medium text-gray-800">Compose Email</h2>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="emailId" class="block text-sm font-medium text-gray-700">Email ID</label>
                                    <input type="text" id="emailId" class="w-full border border-gray-300 rounded-lg px-4 py-2" readonly>
                                </div>
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" id="name" class="w-full border border-gray-300 rounded-lg px-4 py-2" readonly>
                                </div>
                                <div>
                                    <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                                    <input type="text" id="subject" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                </div>
                                <div>
                                    <label for="emailPrompt" class="block text-sm font-medium text-gray-700">Email Content</label>
                                    <textarea id="emailPrompt" rows="10" class="w-full border border-gray-300 rounded-lg"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const fileInput = document.getElementById('fileInput');
                            const dropdownContainer = document.getElementById('dropdownContainer');
                            const dropdown = document.getElementById('dropdown');
                            const emailBox = document.getElementById('emailBox');
                            const emailIdInput = document.getElementById('emailId');
                            const nameInput = document.getElementById('name');
                            const subjectInput = document.getElementById('subject');
                            const fileDetails = document.getElementById('fileDetails');
                            let excelData = []; // Store the parsed Excel data

                            // Initialize TinyMCE for the email content textarea
                            tinymce.init({
                                selector: '#emailPrompt',
                                plugins: 'lists link image table paste',
                                toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image',
                                menubar: false,
                                height: 300
                            });

                            // Handle file selection
                            fileInput.addEventListener('change', async (event) => {
                                const file = event.target.files[0];
                                if (!file) return;
                                fileDetails.innerHTML = `<p class="text-sm text-gray-700">File Selected: ${file.name}</p>`;

                                // Read and parse Excel file using SheetJS
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    const data = new Uint8Array(e.target.result);
                                    const workbook = XLSX.read(data, {
                                        type: 'array'
                                    });

                                    // Assuming the first sheet is the one we want
                                    const firstSheetName = workbook.SheetNames[0];
                                    const worksheet = workbook.Sheets[firstSheetName];

                                    // Convert sheet to JSON
                                    excelData = XLSX.utils.sheet_to_json(worksheet, {
                                        header: 1
                                    });

                                    if (excelData.length > 1) {
                                        populateDropdown(excelData);
                                    } else {
                                        alert('The file appears to be empty or invalid.');
                                    }
                                };
                                reader.readAsArrayBuffer(file);
                            });

                            // Populate dropdown with rows from the Excel file
                            function populateDropdown(data) {
                                // Clear any previous options
                                dropdown.innerHTML = '<option value="" disabled selected>Select a row</option>';

                                // Add rows as dropdown options
                                data.slice(1).forEach((row, index) => {
                                    const option = document.createElement('option');
                                    option.value = index;
                                    option.textContent = `Row ${index + 1}: ${row.join(', ')}`;
                                    dropdown.appendChild(option);
                                });

                                // Show the dropdown
                                dropdownContainer.style.display = 'block';
                            }

                            // Handle dropdown selection
                            dropdown.addEventListener('change', (event) => {
                                const selectedIndex = parseInt(event.target.value, 10) + 1; // Adjust for header row
                                const selectedRow = excelData[selectedIndex];

                                if (selectedRow) {
                                    // Prefill the email box fields
                                    emailIdInput.value = selectedRow[0] || ''; // Email ID
                                    nameInput.value = selectedRow[1] || ''; // Name
                                    subjectInput.value = selectedRow[2] || ''; // Subject
                                    tinymce.get('emailPrompt').setContent(selectedRow[3] || ''); // Email Content

                                    // Show the email box
                                    emailBox.style.display = 'block';
                                }
                            });
                        });
                    </script>

                    <!-- Submit and Reset Buttons -->
                    <div class="flex flex-col md:flex-row mt-6 gap-4 w-full">
                        <button type="submit" id="submitbut" class="w-full md:w-auto bg-indigo-600 text-white rounded-lg px-6 py-3 md:ml-8 font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-300">
                            Send Mail
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>

</html>