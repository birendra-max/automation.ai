
class RichTextEditor {
    constructor({ editor, toolbar, attachments }) {
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