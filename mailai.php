<script>
    document.title = 'Mail Automation | AI'
</script>

<?php
include 'inclu/hd.php';
?>

<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css" crossorigin>
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5-premium-features/44.1.0/ckeditor5-premium-features.css" crossorigin>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap');

    @media print {
        body {
            margin: 0 !important;
        }
    }

    .main-container {
        font-family: 'Lato';
        width: fit-content;
        margin-left: auto;
        margin-right: auto;
        width: 100%;
    }

    .ck-content {
        font-family: 'Lato';
        line-height: 1.6;
        word-break: break-word;
        width: 100%;
        min-height: 30%;
    }

    .editor-container_classic-editor .editor-container__editor {
        min-width: 100%;
        max-width: 100%;
    }
</style>

<section class="mt-4">
    <div class="max-w-7xl mx-auto bg-white border border-gray-300 shadow-lg rounded-lg p-8">
        <!-- Alert Container -->
        <div id="alert-container" class="fixed top-0 left-1/2 transform -translate-x-1/2 mt-4 z-50">
            <div id="alert" class="bg-green-500 text-white text-center p-4 rounded-lg shadow-lg max-w-[100%] ml-36 mx-auto hidden">
                <div class="flex items-center justify-center w-full">
                    <span id="alert-message" class="font-medium w-full"></span>
                    <button onclick="closeAlert()" class="ml-4 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- File Upload Section -->
        <div class="w-full">
            <h3 class="text-lg font-semibold mb-4">Upload Your Email Excel File</h3>
            <div class="p-6 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:shadow-md transition-all">
                <input type="file" id="fileInput" name="emailfile" class="hidden" accept=".xls,.xlsx" required />
                <label for="fileInput" class="flex flex-col items-center cursor-pointer">
                    <i class="fas fa-cloud-upload-alt text-indigo-600 text-5xl"></i>
                    <p class="text-gray-600 mt-2">Drag & Drop files here or <span class="text-indigo-600 font-semibold">Browse</span></p>
                </label>
                <div id="fileList" class="mt-4"></div>
            </div>
            <div id="error-message" class="text-red-500 hidden">
                Please upload a valid Excel file (.xls, .xlsx).
            </div>
        </div>

        <!-- FAQ Section -->
        <section id="faq" class="container mx-auto py-8">
            <form id="faq-container" class="space-y-6">
                <div class="faq-item border rounded-lg shadow-md mb-4">
                    <div class="flex items-center justify-between bg-teal-600 w-full cursor-pointer px-6 py-4 text-white font-medium hover:bg-teal-700 transition duration-300" data-index="${i}">
                        <h4 class="text-lg">Send your Emails</h4>
                        <i class="fas fa-caret-down text-white"></i>
                    </div>
                    <div class="px-6 py-4 faq-answer">
                        <div class="mb-4">
                            <label for="email" class="block text-teal-600 font-medium">Emails</label>
                            <input type="text" id="email" value="" class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter recipient email" name="emailid" required>
                        </div>
                        <div class="mb-4">
                            <label for="subject" class="block text-teal-600 font-medium">Subject</label>
                            <input type="text" id="subject" value="" class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter email subject" name="subject" required>
                        </div>
                        <div class="main-container">
                            <div class="editor-container editor-container_classic-editor" id="editor-container">
                                <div class="editor-container__editor">
                                    <label for="message" class="block text-teal-600 font-medium">Mail Prompt</label>
                                    <textarea id="editor" name="editor"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="w-48">
                            <?php
                            include 'inclu/spinner.php'
                            ?>
                            <button type="submit" class="save-btn px-4 py-2 mt-4 bg-teal-600 text-white rounded hover:bg-teal-700" id="sendmail">
                                Send Mail
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>

    <!-- Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


    <script src="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js" crossorigin></script>
    <script src="https://cdn.ckeditor.com/ckeditor5-premium-features/44.1.0/ckeditor5-premium-features.umd.js"
        crossorigin></script>
    <script src="https://cdn.ckbox.io/ckbox/2.6.1/ckbox.js" crossorigin></script>
    <script src="public/js/main.js"></script>

    <script>
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    try {
                        const data = new Uint8Array(event.target.result);
                        const workbook = XLSX.read(data, {
                            type: 'array'
                        });
                        const firstSheet = workbook.Sheets[workbook.SheetNames[0]];


                        const rows = XLSX.utils.sheet_to_json(firstSheet, {
                            header: 1
                        });
                        if (rows.length > 0) {
                            const emailColumn = rows.slice(1).map(row => row[0]).filter(email => email);
                            const emails = emailColumn.join(', ');
                            document.getElementById('email').value = emails;
                        } else {
                            alert('The Excel file is empty or has an invalid structure.');
                        }
                    } catch (error) {
                        console.error('Error reading Excel file:', error);
                        alert('Failed to read the file. Please ensure it is a valid Excel file.');
                    }
                };
                reader.readAsArrayBuffer(file);
            } else {
                alert('No file selected. Please upload a valid Excel file.');
            }
        });


        $(document).ready(function() {
            const alertMessage = localStorage.getItem('alertMessage');
            const alertType = localStorage.getItem('alertType');

            if (alertMessage && alertType) {
                showAlert(alertMessage, alertType);
                localStorage.removeItem('alertMessage');
                localStorage.removeItem('alertType');
            }

            $('#faq-container').submit(function(e) {
                e.preventDefault();

                $("#spinner").css('display', 'block');
                $("#sendmail").css('display', 'none');

                const formData = $(this).serialize();
                const decodeData = decodeURIComponent(formData);

                $.ajax({
                    url: 'sendMail.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);

                        $("#spinner").css('display', 'none');
                        $("#sendmail").css('display', 'block');

                        if (response.status === 'success') {
                            const message = `Your email was sent successfully! Total Emails: ${response.total_emails} | Success Emails: ${response.sent_emails}`;
                            showAlert(message, "success");
                            localStorage.setItem('alertMessage', message);
                            localStorage.setItem('alertType', 'success');
                        } else {
                            const message = "There was an error: " + response.message;
                            showAlert(message, "error");
                            localStorage.setItem('alertMessage', message);
                            localStorage.setItem('alertType', 'error');
                        }

                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        $("#spinner").css('display', 'none');
                        const message = "An error occurred while sending the email. Please try again.";
                        showAlert(message, "error");
                        localStorage.setItem('alertMessage', message);
                        localStorage.setItem('alertType', 'error');
                        location.reload();
                    }
                });
            });
        });

        function showAlert(message, type = 'success') {
            const alert = document.getElementById('alert');
            const alertMessage = document.getElementById('alert-message');

            alertMessage.textContent = message;

            if (type === 'success') {
                alert.classList.remove('bg-red-500', 'bg-yellow-500');
                alert.classList.add('bg-green-500');
            } else if (type === 'error') {
                alert.classList.remove('bg-green-500', 'bg-yellow-500');
                alert.classList.add('bg-red-500');
            } else if (type === 'warning') {
                alert.classList.remove('bg-green-500', 'bg-red-500');
                alert.classList.add('bg-yellow-500');
            }

            alert.classList.remove('hidden');
            alert.classList.add('opacity-100');

            setTimeout(() => {
                closeAlert();
            }, 5000);
        }

        function closeAlert() {
            const alert = document.getElementById('alert');
            alert.classList.add('hidden');
            alert.classList.remove('opacity-100');
        }
    </script>
</section>

<?php
include 'inclu/footer.php';
?>