<script>
    document.title = 'Mail Automation | AI'
</script>

<?php
include 'inclu/hd.php';
?>

<section class="mt-4">
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
        <section id="faq" class="container mx-auto py-8">
            <form id="faq-container" class="space-y-6"></form>
        </section>
    </div>

    <!-- Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


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
        }

        .editor-container_classic-editor .editor-container__editor {
            min-width: 100%;
            max-width: 100%;
        }
    </style>

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
                            console.log(faqData);
                            $("#faq-container").empty();

                            $("#faq-container").append(`
                            <h3 class="mb-8 text-teal-900 text-left text-3xl font-semibold underline decoration-teal-300/80">
                                Email Editor
                            </h3>
                        `);

                            faqData.forEach((e, i) => {
                                let t = `
                                    <div class="faq-item border rounded-lg shadow-md mb-4">
                                        <div class="flex items-center justify-between bg-teal-600 w-full cursor-pointer px-6 py-4 text-white font-medium hover:bg-teal-700 transition duration-300" data-index="${i}">
                                            <h4 class="text-lg">Subject: ${e.subject}</h4>
                                            <i class="fas fa-caret-down text-white"></i>
                                        </div>
                                        <div class="px-6 py-4 faq-answer hidden">
                                            <div class="mb-4">
                                                <label for="name-${i}" class="block text-teal-600 font-medium">Name</label>
                                                <input type="text" id="name-${i}" value="${e.name}" class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter recipient's name" name="username">
                                            </div>
                                            <div class="mb-4">
                                                <label for="email-${i}" class="block text-teal-600 font-medium">Email</label>
                                                <input type="email" id="email-${i}" value="${e.email}" class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter recipient email" name="emailid">
                                            </div>
                                            <div class="mb-4">
                                                <label for="subject-${i}" class="block text-teal-600 font-medium">Subject</label>
                                                <input type="text" id="subject-${i}" value="${e.subject}" class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Enter email subject" name="subject">
                                            </div>
                                            <div class="main-container" class="mb-4">
                                                <div class="editor-container editor-container_classic-editor" id="editor-container">
                                                    <div class="editor-container__editor">
                                                        <label for="message-${i}" class="block text-teal-600 font-medium">Mail Prompt</label>
                                                        <textarea id="editor-${i}" name='emailprompt'>
                                                            ${e.prompt || ""}
                                                        </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="save-btn px-4 py-2 mt-4 bg-teal-600 text-white rounded hover:bg-teal-700" data-index="${i}">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                `;

                                $("#faq-container").append(t);

                                let {
                                    ClassicEditor,
                                    Autoformat,
                                    AutoImage,
                                    Autosave,
                                    BlockQuote,
                                    Bold,
                                    CloudServices,
                                    Essentials,
                                    FindAndReplace,
                                    FullPage,
                                    GeneralHtmlSupport,
                                    Heading,
                                    HtmlComment,
                                    HtmlEmbed,
                                    ImageBlock,
                                    ImageCaption,
                                    ImageInline,
                                    ImageInsert,
                                    ImageInsertViaUrl,
                                    ImageResize,
                                    ImageStyle,
                                    ImageTextAlternative,
                                    ImageToolbar,
                                    ImageUpload,
                                    Indent,
                                    IndentBlock,
                                    Italic,
                                    Link,
                                    LinkImage,
                                    List,
                                    ListProperties,
                                    MediaEmbed,
                                    Mention,
                                    Paragraph,
                                    PasteFromOffice,
                                    PictureEditing,
                                    ShowBlocks,
                                    SourceEditing,
                                    SpecialCharacters,
                                    SpecialCharactersArrows,
                                    SpecialCharactersCurrency,
                                    SpecialCharactersEssentials,
                                    SpecialCharactersLatin,
                                    SpecialCharactersMathematical,
                                    SpecialCharactersText,
                                    Table,
                                    TableCaption,
                                    TableCellProperties,
                                    TableColumnResize,
                                    TableProperties,
                                    TableToolbar,
                                    TextTransformation,
                                    TodoList,
                                    Underline
                                } = window.CKEDITOR;

                                let config = {
                                    toolbar: [
                                        "insertMergeField", "previewMergeFields", "|", "sourceEditing", "showBlocks", "formatPainter", "caseChange", "findAndReplace", "|", "heading", "|", "bold", "italic", "underline", "|",
                                        "specialCharacters", "link", "insertImage", "ckbox", "mediaEmbed", "insertTable", "insertTemplate", "blockQuote", "htmlEmbed", "|",
                                        "bulletedList", "numberedList", "todoList", "outdent", "indent"
                                    ],
                                    plugins: [
                                        Autoformat, AutoImage, Autosave, BlockQuote, Bold, CloudServices, Essentials, FindAndReplace, FullPage, GeneralHtmlSupport, Heading, HtmlComment, HtmlEmbed, ImageBlock,
                                        ImageCaption, ImageInline, ImageInsert, ImageInsertViaUrl, ImageResize, ImageStyle, ImageTextAlternative, ImageToolbar, ImageUpload, Indent, IndentBlock, Italic, Link,
                                        LinkImage, List, ListProperties, MediaEmbed, Mention, Paragraph, PasteFromOffice, PictureEditing, ShowBlocks, SourceEditing, SpecialCharacters, SpecialCharactersArrows,
                                        SpecialCharactersCurrency, SpecialCharactersEssentials, SpecialCharactersLatin, SpecialCharactersMathematical, SpecialCharactersText, Table, TableCaption, TableCellProperties,
                                        TableColumnResize, TableProperties, TableToolbar, TextTransformation, TodoList, Underline
                                    ],
                                    cloudServices: {
                                        tokenUrl: "https://yuhsh6ka_c70.cke-cs.com/token/dev/27d69f91e581c879c0c29039c419177c9fcdddbdc2fd291b361654f88663?limit=10"
                                    },
                                    initialData: e.prompt,
                                    licenseKey: "eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3Mzg3MTM1OTksImp0aSI6Ijk0MTVjNTE0LWMxNzQtNDNmYi05NzdlLWM3MzA2ZmJkNGI2OSIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiLCJzaCJdLCJ3aGl0ZUxhYmVsIjp0cnVlLCJsaWNlbnNlVHlwZSI6InRyaWFsIiwiZmVhdHVyZXMiOlsiKiJdLCJ2YyI6ImY5YzBhMTVmIn0.f0XJV7zr5iodscOa1BYCITOV0nLHgodjR-k65uL6M-5KPmISCc69xw_N9VpfMPoeC1vyWuCeqY0pM06Vt7obRQ",
                                    link: {
                                        addTargetToExternalLinks: true,
                                        defaultProtocol: "https://",
                                        decorators: {
                                            toggleDownloadable: {
                                                mode: "manual",
                                                label: "Downloadable",
                                                attributes: {
                                                    download: "file"
                                                }
                                            }
                                        }
                                    },
                                    mergeFields: {},
                                    placeholder: "Type or paste your content here!",
                                    table: {
                                        contentToolbar: ["tableColumn", "tableRow", "mergeTableCells", "tableProperties", "tableCellProperties"]
                                    }
                                };

                                // Initialize CKEditor for each editor element dynamically
                                ClassicEditor.create(document.querySelector(`#editor-${i}`), config)
                                    .then(editor => {
                                        // Ensure the editor content is updated immediately on data change
                                        editor.model.document.on('change:data', function() {
                                            faqData[i].prompt = editor.getData(); // Update faqData when content changes
                                        });

                                        // Bind other form fields to the faqData
                                        $(`#faq-container #name-${i}`).on("input", function() {
                                            faqData[i].name = $(this).val();
                                        });

                                        $(`#faq-container #email-${i}`).on("input", function() {
                                            faqData[i].email = $(this).val();
                                        });

                                        $(`#faq-container #subject-${i}`).on("input", function() {
                                            faqData[i].subject = $(this).val();
                                        });

                                        // Save button logic
                                        $(`#faq-container .save-btn[data-index=${i}]`).on("click", function() {
                                            const name = $(`#name-${i}`).val();
                                            const email = $(`#email-${i}`).val();
                                            const subject = $(`#subject-${i}`).val();
                                            const prompt = editor.getData(); // Get the prompt from CKEditor

                                            // Update the faqData with new information
                                            faqData[i] = {
                                                ...faqData[i],
                                                name,
                                                email,
                                                subject,
                                                prompt
                                            };

                                            // Send the updated data to the server via AJAX
                                            $.ajax({
                                                url: "UpdateMail.php", // Your server script for updating data
                                                type: "POST",
                                                data: {
                                                    action: 'update', // Action to identify the request
                                                    id: faqData[i].id, // Make sure you have a unique ID for each record
                                                    name: name,
                                                    email: email,
                                                    subject: subject,
                                                    prompt: prompt
                                                },
                                                success: function(response) {
                                                    const result = JSON.parse(response);
                                                    if (result.status === 'success') {
                                                        $(this).text('Saved').prop('disabled', true); // Disable the save button after saving
                                                        console.log(`Data saved successfully for item ${i}:`, faqData[i]);
                                                    } else {
                                                        console.error('Error updating data:', result.message);
                                                    }
                                                },
                                                error: function(xhr, status, error) {
                                                    console.error('Error saving data:', error);
                                                }
                                            });
                                        });
                                    })
                                    .catch(error => {
                                        console.error('Error initializing CKEditor:', error);
                                    });

                            });

                            // Send Mail button
                            $("#faq-container").append(`
                                <button type='submit' class="px-6 py-2 min-w-[120px] text-center text-violet-600 border border-violet-600 rounded hover:bg-violet-600 hover:text-white active:bg-indigo-500 focus:outline-none focus:ring">
                                    Send Mail
                                </button>
                            `);

                            // Toggle FAQ items on click
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
    <script src="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js" crossorigin></script>
    <script src="https://cdn.ckeditor.com/ckeditor5-premium-features/44.1.0/ckeditor5-premium-features.umd.js"
        crossorigin></script>
    <script src="https://cdn.ckbox.io/ckbox/2.6.1/ckbox.js" crossorigin></script>

    <style>
        #faq-container .faq-answer.hidden {
            display: none;
        }
    </style>
</section>


<script>
    $(document).ready(function() {
        $('#faq-container').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            let decodeData = decodeURIComponent(formData);
            console.log(decodeData);
            console.log('\n')

            // $.ajax({
            //     url: 'anotherPage.php',
            //     type: 'POST',
            //     data: formData,
            //     success: function(response) {
            //         console.log('Data sent successfully');
            //     },
            //     error: function(xhr, status, error) {
            //         console.error('Error:', error);
            //     }
            // });
        });
    });
</script>

<?php
require 'Third-party/vendor/autoload.php';
require 'inclu/config.php';
require 'inclu/Mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the updated data is provided
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        // Get the updated data from the request
        $id = $_POST['id']; // Item ID to identify which record to update
        $email = $_POST['email'];
        $name = $_POST['name'];
        $subject = $_POST['subject'];
        $prompt = $_POST['prompt'];

        // Prepare the SQL UPDATE query
        $stmt = $conn->prepare("UPDATE mailautomationai SET email = ?, name = ?, subject = ?, prompt = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $email, $name, $subject, $prompt, $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Data updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update data']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
<?php
include 'inclu/footer.php';
?>