<script>
    document.title = 'Mail Automation | AI'
</script>

<?php
include 'inclu/hd.php';
?>

<section class="mt-12">
    <div class="max-w-7xl mx-auto bg-white border border-gray-300 shadow-lg rounded-lg p-8">
        <!-- File Upload Section -->
        <div class="w-full mb-6">
            <h3 class="text-lg font-semibold mb-4">Upload Your File</h3>
            <div class="p-6 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:shadow-md transition-all">
                <!-- File Input (hidden by Tailwind class) -->
                <input type="file" id="fileInput" name="emailfile" class="hidden" accept=".xls,.xlsx" required />

                <!-- Styled Label (visible to user) -->
                <label for="fileInput" class="flex flex-col items-center cursor-pointer">
                    <i class="fas fa-cloud-upload-alt text-indigo-600 text-5xl"></i>
                    <p class="text-gray-600 mt-2">Drag & Drop files here or <span class="text-indigo-600 font-semibold">Browse</span></p>
                </label>

                <div id="fileList" class="mt-4"></div>
            </div>
            <!-- Error Message -->
            <div id="error-message" class="text-red-500 mt-2 hidden">
                Please upload a valid Excel file (.xls, .xlsx).
            </div>
        </div>

        <!-- Dropdown and Details Section -->
        <section id="faq" class="container mx-auto py-8 px-4">

            <form action="">
                <div id="faq-container" class="space-y-6">
                    <!-- Dynamic FAQ Items will be inserted here -->
                </div>
            </form>
        </section>
    </div>

    <!-- Alpine.js and FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#fileInput').change(function(e) {
                e.preventDefault();

                // Check if the uploaded file is an Excel file (xls or xlsx)
                const file = this.files[0];
                const allowedExtensions = ['xls', 'xlsx'];
                const fileExtension = file.name.split('.').pop().toLowerCase();

                // If the file is not an Excel file, show an error and stop processing
                if (!allowedExtensions.includes(fileExtension)) {
                    $("#error-message").removeClass('hidden'); // Show error message
                    return;
                } else {
                    $("#error-message").addClass('hidden'); // Hide error message
                }

                // Show loading spinner, hide submit button
                $("#submitbut").addClass('hidden');
                $("#spinner").removeClass("hidden");

                let formData = new FormData();
                formData.append('emailfile', file); // Add the file to FormData

                // Send AJAX request
                $.ajax({
                    url: "mailEdit.php",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        console.log('Response:', resp);

                        try {
                            const faqData = JSON.parse(resp);
                            console.log('Parsed Data:', faqData);
                            $("#faq-container").empty();

                            // Add the "Email Editor" heading only once before the loop
                            $("#faq-container").append(`
                                    <h3 class="mb-8 text-teal-900 text-center text-3xl font-semibold underline decoration-teal-300/80 lg:text-left xl:text-4xl">
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
                                                            <!-- Name Field -->
                                                            <div class="mb-4">
                                                                <label for="name-${index}" class="block text-teal-600 font-medium">Name</label>
                                                                <input type="text" id="name-${index}" value="${item.name}" class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter recipient's name">
                                                            </div>

                                                            <!-- Email Field -->
                                                            <div class="mb-4">
                                                                <label for="email-${index}" class="block text-teal-600 font-medium">Email</label>
                                                                <input type="email" id="email-${index}" value="${item.email}" class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter recipient email">
                                                            </div>

                                                            <!-- Subject Field -->
                                                            <div class="mb-4">
                                                                <label for="subject-${index}" class="block text-teal-600 font-medium">Subject</label>
                                                                <input type="text" id="subject-${index}" value="${item.subject}" class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter email subject">
                                                            </div>

                                                            <!-- Mail Prompt Field (Message Body) -->
                                                            <div class="mb-4">
                                                                <label for="message-${index}" class="block text-teal-600 font-medium">Mail Prompt</label>
                                                                <textarea id="message-${index}" class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" rows="6" placeholder="Write your message here">${item.prompt}</textarea>
                                                            </div>

                                                            <!-- Save Button -->
                                                            <div class="flex justify-end">
                                                                <button class="save-email-btn bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 transition duration-300" data-index="${index}">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `;
                                $("#faq-container").append(faqItem);
                            });

                            // Add the "Send Mail" button only once at the bottom
                            $("#faq-container").append(`
                                <div class="flex gap-4 mt-6">
                                    <a class="px-6 py-2 min-w-[120px] text-center text-violet-600 border border-violet-600 rounded hover:bg-violet-600 hover:text-white active:bg-indigo-500 focus:outline-none focus:ring"
                                        href="/download">
                                        Send Mail
                                    </a>
                                </div>
                            `);


                            // Add click event to toggle the dropdown visibility
                            $(".faq-item > div").click(function() {
                                const answer = $(this).siblings(".faq-answer");
                                answer.toggleClass("hidden");
                                const icon = $(this).find("i");
                                icon.toggleClass("fa-caret-down fa-caret-up");
                            });

                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                        } finally {
                            $("#submitbut").removeClass('hidden');
                            $("#spinner").addClass("hidden");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error: ' + error);
                    }
                });
            });
        });
    </script>

    <style>
        #faq-container .faq-answer.hidden {
            display: none;
        }

        #faq-container .faq-answer {
            display: block;
        }

        .faq-item>div {
            cursor: pointer;
        }
    </style>

</section>


<?php
include 'inclu/footer.php';
?>