<script>
    document.title = 'Mail Automation | AI'
</script>

<?php
include 'inclu/hd.php';
?>

<section class="mt-4" id="mailai">
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
                        <!-- Main Container for the editor -->
                        <div id="editorContainer" class="w-full mx-auto p-6 bg-white rounded-lg shadow-lg">
                            <!-- Toolbar -->
                            <div id="toolbar" class="flex flex-wrap gap-3 items-center mb-4 bg-gray-50 p-4 rounded-lg shadow-sm"></div>

                            <!-- Editor Area (contenteditable div for rich text including images) -->
                            <div id="editor" contenteditable="true"
                                class="w-full min-h-[300px] border border-gray-300 rounded-lg p-4 bg-gray-50 shadow-sm focus:outline-none"
                                placeholder="Start typing your email here..."></div>

                            <!-- Attachments -->
                            <div id="attachments" class="mt-4 flex flex-wrap gap-4 border-t border-gray-200 pt-4"></div>
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

    <script>
        class RichTextEditor {
            constructor({
                editor,
                toolbar,
                attachments
            }) {
                this.editor = document.querySelector(editor);
                this.toolbar = document.querySelector(toolbar);
                this.attachments = document.querySelector(attachments);
                this.init();
            }

            init() {
                this.createToolbar();
                this.addEventListeners();
            }

            createToolbar() {
                const toolbarHTML = `
            <a href="#" data-command="bold" class="btn px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">B</a>
            <a href="#" data-command="italic" class="btn px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">I</a>
            <a href="#" data-command="underline" class="btn px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">U</a>
            <a href="#" data-command="strikethrough" class="btn px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">S</a>
            <select data-command="fontName" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                <option value="Arial">Arial</option>
                <option value="Times New Roman">Times New Roman</option>
                <option value="Courier New">Courier New</option>
            </select>
            <select data-command="fontSize" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                <option value="1">Small</option>
                <option value="3" selected>Normal</option>
                <option value="5">Large</option>
            </select>
            <a href="#" data-command="justifyLeft" class="btn px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">⬅️</a>
            <a href="#" data-command="justifyCenter" class="btn px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">⬜</a>
            <a href="#" data-command="justifyRight" class="btn px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">➡️</a>
            <a href="#" class="btn-color px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">🎨</a>
            <a href="#" class="btn-bgcolor px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">🖌️</a>
            <a href="#" class="btn-link px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">🔗</a>
            <a href="#" class="btn-image px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">📷</a>
            <a href="#" class="btn-undo px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">↩️</a>
            <a href="#" class="btn-redo px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">↪️</a>
            <label for="attachment-input" class="btn px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">📎</label>
            <input type="file" id="attachment-input" class="hidden" multiple />
            <a href="#" class="btn-ul px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">•</a>
            <a href="#" class="btn-ol px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">1.</a>
            <a href="#" class="btn-table px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">🛠️</a>
        `;
                this.toolbar.innerHTML = toolbarHTML;
            }

            execCommand(command, value = null) {
                if (command === "fontSize") {
                    value = parseInt(value); // Convert the font size to a number (1, 3, 5)
                }
                document.execCommand(command, false, value);
            }

            addEventListeners() {
                this.toolbar.addEventListener("click", (e) => {
                    const command = e.target.dataset.command;
                    if (command) {
                        e.preventDefault(); // Prevent default anchor behavior
                        this.execCommand(command);
                    }
                });

                this.toolbar.addEventListener("change", (e) => {
                    const command = e.target.dataset.command;
                    if (command) {
                        if (command === "fontName") {
                            this.execCommand(command, e.target.value); // Pass the selected font family
                        } else if (command === "fontSize") {
                            this.execCommand(command, e.target.value); // Pass the selected font size
                        }
                    }
                });

                this.toolbar.querySelector(".btn-color").addEventListener("click", () => {
                    const color = prompt("Enter Text Color (e.g., #ff0000 or 'red'): ");
                    if (color) this.execCommand("foreColor", color);
                });

                this.toolbar.querySelector(".btn-bgcolor").addEventListener("click", () => {
                    const color = prompt("Enter Background Color (e.g., #ff0000 or 'red'): ");
                    if (color) this.execCommand("backColor", color);
                });

                this.toolbar.querySelector(".btn-link").addEventListener("click", () => {
                    const url = prompt("Enter URL: ");
                    if (url) this.execCommand("createLink", url);
                });

                this.toolbar.querySelector(".btn-image").addEventListener("click", () => {
                    const imgUrl = prompt("Enter Image URL: ");
                    if (imgUrl) this.insertImage(imgUrl);
                });

                this.toolbar.querySelector(".btn-undo").addEventListener("click", () => {
                    this.execCommand("undo");
                });

                this.toolbar.querySelector(".btn-redo").addEventListener("click", () => {
                    this.execCommand("redo");
                });

                this.toolbar.querySelector(".btn-ul").addEventListener("click", () => {
                    this.execCommand("insertUnorderedList");
                });

                this.toolbar.querySelector(".btn-ol").addEventListener("click", () => {
                    this.execCommand("insertOrderedList");
                });

                this.toolbar.querySelector(".btn-table").addEventListener("click", () => {
                    const rows = prompt("Enter number of rows: ");
                    const cols = prompt("Enter number of columns: ");
                    if (rows && cols) this.insertTable(rows, cols);
                });

                this.editor.addEventListener("dragover", (e) => e.preventDefault());
                this.editor.addEventListener("drop", (e) => this.handleDrop(e));

                this.toolbar
                    .querySelector("#attachment-input")
                    .addEventListener("change", (e) => {
                        this.handleFileAttachment(e.target.files);
                    });
            }

            handleDrop(event) {
                event.preventDefault();
                const files = event.dataTransfer.files;
                this.handleFileAttachment(files);
            }

            handleFileAttachment(files) {
                for (let file of files) {
                    if (file.type.startsWith("image/")) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.insertImage(e.target.result);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        const attachment = document.createElement("div");
                        attachment.classList.add(
                            "attachment",
                            "flex",
                            "items-center",
                            "gap-2",
                            "bg-gray-100",
                            "p-2",
                            "rounded-lg",
                            "shadow"
                        );
                        const icon = document.createElement("span");
                        icon.textContent = "📎";
                        attachment.appendChild(icon);
                        const fileName = document.createElement("span");
                        fileName.textContent = file.name;
                        attachment.appendChild(fileName);
                        const removeBtn = document.createElement("button");
                        removeBtn.textContent = "❌";
                        removeBtn.classList.add("text-red-500", "hover:text-red-700");
                        removeBtn.addEventListener("click", () => {
                            attachment.remove();
                        });
                        attachment.appendChild(removeBtn);
                        this.attachments.appendChild(attachment);
                    }
                }
            }

            insertImage(src) {
                const imgContainer = document.createElement("div");
                imgContainer.classList.add("relative", "inline-block");

                const img = document.createElement("img");
                img.src = src;
                img.classList.add("max-w-full", "h-auto", "border", "rounded-lg");

                const handle = document.createElement("div");
                handle.classList.add(
                    "resize-handle",
                    "absolute",
                    "bg-blue-500",
                    "w-4",
                    "h-4",
                    "bottom-0",
                    "right-0",
                    "rounded-full",
                    "cursor-nwse-resize"
                );

                imgContainer.appendChild(img);
                imgContainer.appendChild(handle);
                this.editor.appendChild(imgContainer);

                handle.addEventListener("mousedown", (e) => {
                    e.preventDefault();
                    const startX = e.clientX;
                    const startY = e.clientY;
                    const startWidth = img.offsetWidth;
                    const startHeight = img.offsetHeight;

                    const onMouseMove = (e) => {
                        const newWidth = startWidth + (e.clientX - startX);
                        const newHeight = startHeight + (e.clientY - startY);
                        img.style.width = `${newWidth}px`;
                        img.style.height = `${newHeight}px`;
                    };

                    const onMouseUp = () => {
                        document.removeEventListener("mousemove", onMouseMove);
                        document.removeEventListener("mouseup", onMouseUp);
                    };

                    document.addEventListener("mousemove", onMouseMove);
                    document.addEventListener("mouseup", onMouseUp);
                });
            }

            insertTable(rows, cols) {
                const table = document.createElement("table");
                table.classList.add("border-collapse", "w-full", "mt-4");
                for (let i = 0; i < rows; i++) {
                    const row = document.createElement("tr");
                    for (let j = 0; j < cols; j++) {
                        const cell = document.createElement("td");
                        cell.classList.add("border", "p-2");
                        row.appendChild(cell);
                    }
                    table.appendChild(row);
                }
                this.editor.appendChild(table);
            }
        }

        // Initialize the editor
        new RichTextEditor({
            editor: "#editor",
            toolbar: "#toolbar",
            attachments: "#attachments",
        });
    </script>

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