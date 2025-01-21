<script>
    document.title = 'Mail Automation | AI'
</script>

<?php
include 'inclu/hd.php';
?>

<section class="mt-12">
    <div class="max-w-7xl mx-auto bg-white border border-gray-300 shadow-lg rounded-lg p-8">
        <!-- File Upload Section -->
        <div class="w-full">
            <h3 class="text-lg font-semibold mb-4">Upload Your File</h3>
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
        <section id="faq" class="container mx-auto py-8 px-4">
            <div id="faq-container" class="space-y-6"></div>
        </section>
    </div>

    <!-- Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        $(document).ready(function() {
            $('#fileInput').change(function(e) {
                e.preventDefault();

                const file = this.files[0];
                const allowedExtensions = ['xls', 'xlsx'];
                const fileExtension = file.name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(fileExtension)) {
                    $("#error-message").removeClass('hidden');
                    return;
                } else {
                    $("#error-message").addClass('hidden');
                }

                let formData = new FormData();
                formData.append('emailfile', file);

                $.ajax({
                    url: "mailEdit.php", // Update with your server endpoint
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        try {
                            const faqData = JSON.parse(resp);
                            $("#faq-container").empty();

                            $("#faq-container").append(`
                                <h3 class="mb-8 text-teal-900 text-center text-3xl font-semibold underline decoration-teal-300/80">
                                    Email Editor
                                </h3>
                            `);

                            faqData.forEach((item, index) => {
                                const faqItem = `
                                        <div class="faq-item border rounded-lg shadow-md mb-4">
                                            <div class="flex items-center justify-between bg-teal-600 w-full cursor-pointer px-6 py-4 text-white font-medium hover:bg-teal-700 transition duration-300" data-index="${index}">
                                                <h4 class="text-lg">Subject: ${item.subject}</h4>
                                                <i class="fas fa-caret-down text-white"></i>
                                            </div>
                                            <div class="px-6 py-4 faq-answer hidden">
                                                <div class="mb-4">
                                                    <label for="name-${index}" class="block text-teal-600 font-medium">Name</label>
                                                    <input type="text" id="name-${index}" value="${item.name}" class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter recipient's name">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="email-${index}" class="block text-teal-600 font-medium">Email</label>
                                                    <input type="email" id="email-${index}" value="${item.email}" class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter recipient email">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="subject-${index}" class="block text-teal-600 font-medium">Subject</label>
                                                    <input type="text" id="subject-${index}" value="${item.subject}" class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter email subject">
                                                </div>
                                                <div class="mb-4">
                                                    <label for="message-${index}" class="block text-teal-600 font-medium">Mail Prompt</label>
                                                    <textarea id="message-${index}" class="editor">${item.prompt || ''}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                $("#faq-container").append(faqItem);

                                // Initialize CKEditor for the new textarea
                                ClassicEditor.create(document.querySelector(`#message-${index}`), {
                                        licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NjkwMzk5OTksImp0aSI6ImU4YmQ2ZjU5LWQwOGYtNDQxMy05NmY5LTQwYWFmMWU3YzcxYyIsImxpY2Vuc2VkSG9zdHMiOlsiMTI3LjAuMC4xIiwibG9jYWxob3N0IiwiMTkyLjE2OC4qLioiLCIxMC4qLiouKiIsIjE3Mi4qLiouKiIsIioudGVzdCIsIioubG9jYWxob3N0IiwiKi5sb2NhbCJdLCJ1c2FnZUVuZHBvaW50IjoiaHR0cHM6Ly9wcm94eS1ldmVudC5ja2VkaXRvci5jb20iLCJkaXN0cmlidXRpb25DaGFubmVsIjpbImNsb3VkIiwiZHJ1cGFsIl0sImxpY2Vuc2VUeXBlIjoiZGV2ZWxvcG1lbnQiLCJmZWF0dXJlcyI6WyJEUlVQIl0sInZjIjoiZjRhMWY3ZTMifQ._c3IAAR1ZA9xadANk9GeHmviOsDUzK8977eWmBcidsE6IIa-GF-lJrdynkDir8ohQHzxPfykD9Nsud4mB5Yu0g', // Replace with your actual license key
                                        toolbar: [
                                            'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo', 'imageUpload'
                                        ],
                                        simpleUpload: {
                                            uploadUrl: '/imgUpload.php', // Replace this with your backend endpoint
                                            headers: {
                                                'X-CSRF-TOKEN': 'your-csrf-token', // Replace with your actual CSRF token if required
                                                Authorization: 'Bearer your-auth-token' // Replace with your authentication token if required
                                            }
                                        }
                                    })
                                    .catch(error => console.error('Error initializing CKEditor:', error));

                            });

                            $(".faq-item > div").click(function() {
                                const answer = $(this).siblings(".faq-answer");
                                answer.toggleClass("hidden");
                                const icon = $(this).find("i");
                                icon.toggleClass("fa-caret-down fa-caret-up");
                            });
                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>

    <style>
        #faq-container .faq-answer.hidden {
            display: none;
        }
    </style>
</section>



<?php
include 'inclu/footer.php';
?>