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
                                    licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3Mzg3MTM1OTksImp0aSI6Ijk0MTVjNTE0LWMxNzQtNDNmYi05NzdlLWM3MzA2ZmJkNGI2OSIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6ImY5YzBhMTVmIn0.f0XJV7zr5iodscOa1BYCITOV0nLHgodjR-k65uL6M-5KPmISCc69xw_N9VpfMPoeC1vyWuCeqY0pM06Vt7obRQ',
                                    collaboration: {
                                        channelId: 'YOUR_CHANNEL_ID', // Replace with your unique channel ID
                                        webSocketUrl: 'wss://YOUR_WEBSOCKET_URL' // Replace with your WebSocket URL
                                    },
                                    toolbar: [
                                        "previousPage",
                                        "nextPage",
                                        "|",
                                        "insertMergeField",
                                        "previewMergeFields",
                                        "|",
                                        "formatPainter",
                                        "|",
                                        "heading",
                                        "|",
                                        "fontSize",
                                        "fontFamily",
                                        "fontColor",
                                        "fontBackgroundColor",
                                        "|",
                                        "bold",
                                        "italic",
                                        "underline",
                                        "|",
                                        "link",
                                        "insertImage",
                                        "insertTable",
                                        "|",
                                        "alignment",
                                        "|",
                                        "bulletedList",
                                        "numberedList",
                                        "multiLevelList",
                                        "todoList",
                                        "outdent",
                                        "indent",
                                    ],
                                    image: {
                                        toolbar: [
                                            "imageTextAlternative",
                                            "imageStyle:full",
                                            "imageStyle:side",
                                            "|",
                                            "linkImage"
                                        ],
                                        upload: {
                                            // Example: URL to the server endpoint for image upload
                                            url: '/upload-image-endpoint', // Replace with your server URL for image uploads
                                            method: 'POST',
                                            headers: {
                                                'Authorization': 'Bearer YOUR_ACCESS_TOKEN', // If you need authorization
                                                'X-CSRF-TOKEN': 'your-csrf-token' // Include CSRF token if necessary
                                            },
                                            withCredentials: true,
                                            success: (response) => {
                                                console.log("Image upload successful", response);
                                            },
                                            error: (error) => {
                                                console.error("Image upload error", error);
                                            }
                                        }
                                    }
                                }).catch(error => console.error('Error initializing CKEditor with collaboration:', error));


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