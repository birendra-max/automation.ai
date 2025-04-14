<script>
    document.title = 'Auto Calling';
</script>

<?php
include 'inclu/hd.php';
?>


<!-- UI + Table Section -->
<section class="max-w-8xl mx-auto bg-white border border-gray-300 shadow-lg rounded-lg p-8 mt-2">
    <div class="px-6 py-4 faq-answer">
        <form id="callForm" class="mb-4">
            <label for="number" class="block text-teal-600 font-bold text-2xl mb-2">Phone Numbers</label>

            <textarea name="" id="number"
                class="w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                placeholder="Enter recipient numbers (comma-separated for multiple)" name="number" required></textarea>

            <!-- Buttons -->
            <div class="mt-4 flex gap-4">
                <button type="submit"
                    class="save-btn px-4 py-2 bg-teal-500 text-white font-bold text-lg rounded hover:bg-teal-700">
                    Start Calling
                </button>

                <button type="button"
                    class="save-btn px-4 py-2 bg-yellow-500 text-black font-bold text-lg rounded hover:bg-yellow-700"
                    id="dialSelf">
                    Dial Your Number
                </button>
            </div>
        </form>

        <div id="callTableWrapper" class="mt-8 overflow-x-auto hidden">
            <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
                <thead class="bg-teal-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Phone Number</th>
                        <th class="px-4 py-2 text-left">Call Status</th>
                        <th class="px-4 py-2 text-left">Call Duration</th>
                        <th class="px-4 py-2 text-left">Recording</th>
                        <th class="px-4 py-2 text-left">Download</th>
                        <th class="px-4 py-2 text-left">Call Again</th>
                    </tr>
                </thead>
                <tbody id="callTableBody" class="bg-white divide-y divide-gray-100">
                    <!-- JS will populate this -->
                </tbody>
            </table>
        </div>
    </div>
</section>


<!-- Mobile Call Popup -->
<div id="mobilePopup" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden flex items-center justify-center">
    <div id="mobilePopupBox" class="bg-white w-96 rounded-2xl shadow-2xl p-6 relative text-center cursor-move">
        <!-- Close button -->
        <button id="closePopup" class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-2xl">&times;</button>

        <!-- Title -->
        <h2 class="text-2xl font-bold text-teal-600 mb-4">Dial Your Number</h2>

        <!-- Input Field -->
        <input type="text" id="userNumberInput"
            class="w-full px-4 py-3 border rounded-md mb-4 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
            placeholder="Enter phone number" />

        <!-- Call Button -->
        <button id="makeCallBtn"
            class="w-16 h-16 rounded-full bg-green-500 hover:bg-green-600 flex items-center justify-center mx-auto shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 20 20" fill="currentColor">
                <path
                    d="M2 3.5A1.5 1.5 0 013.5 2h1A1.5 1.5 0 016 3.5V5a1.5 1.5 0 01-1.5 1.5H3.5A1.5 1.5 0 012 5V3.5zM14 3.5A1.5 1.5 0 0115.5 2h1A1.5 1.5 0 0118 3.5V5a1.5 1.5 0 01-1.5 1.5h-1A1.5 1.5 0 0114 5V3.5zM2 14.5A1.5 1.5 0 013.5 13h1a1.5 1.5 0 011.5 1.5V16a1.5 1.5 0 01-1.5 1.5H3.5A1.5 1.5 0 012 16v-1.5zM14 14.5a1.5 1.5 0 011.5-1.5h1a1.5 1.5 0 011.5 1.5V16a1.5 1.5 0 01-1.5 1.5h-1A1.5 1.5 0 0114 16v-1.5z" />
                <path
                    d="M5.5 4h9a1.5 1.5 0 011.5 1.5v9a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 014 14.5v-9A1.5 1.5 0 015.5 4z" />
            </svg>
        </button>
    </div>
</div>


<!-- Mobile Call UI -->
<div id="mobileCallUI" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div id="draggableCallUI"
        class="w-[30%] h-[50%] bg-[#1a1a1a] text-white rounded-2xl shadow-2xl flex flex-col items-center justify-between py-10 px-8 cursor-move absolute">
        <!-- Caller Info -->
        <div class="flex flex-col items-center">
            <div class="w-28 h-28 rounded-full bg-teal-600 flex items-center justify-center mb-6 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-white" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd" d="M10 10a4 4 0 100-8 4 4 0 000 8zm-6 8a6 6 0 1112 0H4z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-3xl font-bold mb-2">Calling...</p>
            <p id="mobileNumberDisplay" class="text-lg text-teal-400">+0000000000</p>
        </div>

        <!-- Action Buttons Grid -->
        <div class="grid grid-cols-4 gap-4 mt-8">
            <!-- Pause -->
            <button id="pauseCall"
                class="w-14 h-14 rounded-full bg-yellow-400 flex items-center justify-center hover:bg-yellow-500 transition duration-300 shadow-md"
                title="Pause Call">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path d="M6 4h2v12H6V4zm6 0h2v12h-2V4z" />
                </svg>
            </button>

            <!-- Start Recording -->
            <button id="startRecording"
                class="w-14 h-14 rounded-full bg-blue-500 flex items-center justify-center hover:bg-blue-600 transition duration-300 shadow-md"
                title="Start Recording">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20"
                    fill="currentColor">
                    <circle cx="10" cy="10" r="5" />
                </svg>
            </button>

            <!-- Stop Recording -->
            <button id="stopRecording"
                class="w-14 h-14 rounded-full bg-gray-600 flex items-center justify-center hover:bg-gray-700 transition duration-300 shadow-md"
                title="Stop Recording">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20"
                    fill="currentColor">
                    <rect x="6" y="6" width="8" height="8" />
                </svg>
            </button>

            <!-- Message Button -->
            <button id="messageBox"
                class="w-14 h-14 rounded-full bg-green-600 flex items-center justify-center hover:bg-green-700 transition duration-300 shadow-md"
                title="Open Message Box">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v7a2 2 0 01-2 2H6l-4 4V5z" />
                </svg>
            </button>
        </div>

        <!-- End Call -->
        <div class="mt-6">
            <button id="closeCallUI"
                class="w-14 h-14 rounded-full bg-red-600 flex items-center justify-center hover:bg-red-700 transition duration-300 shadow-md"
                title="End Call">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    let device;

    // Load Twilio Client and get token
    async function setupTwilioClient() {
        try {
            const response = await fetch('token.php');
            const data = await response.json();
            device = new Twilio.Device(data.token, {
                debug: true
            });

            device.on('ready', () => console.log('Twilio Device Ready'));
            device.on('error', error => alert('Twilio error: ' + error.message));
            device.on('disconnect', () => {
                document.getElementById('mobileCallUI').classList.add('hidden');
                console.log('Call disconnected');
            });
        } catch (err) {
            console.error('Token fetch failed:', err);
            alert('Could not connect to Twilio');
        }
    }

    // Call on page load
    setupTwilioClient();

    // Show dial popup
    document.getElementById('dialSelf').addEventListener('click', () => {
        document.getElementById('mobilePopup').classList.remove('hidden');
    });

    // Close popup
    document.getElementById('closePopup').addEventListener('click', () => {
        document.getElementById('mobilePopup').classList.add('hidden');
    });

    // Dial from popup
    document.getElementById('makeCallBtn').addEventListener('click', () => {
        const number = document.getElementById('userNumberInput').value.trim();
        if (!number) return alert('Please enter a number');

        if (!device || device.status() !== 'ready') {
            return alert('Twilio Client not ready');
        }

        device.connect({
            To: number
        });

        document.getElementById('mobilePopup').classList.add('hidden');
        document.getElementById('mobileCallUI').classList.remove('hidden');
        document.getElementById('mobileNumberDisplay').textContent = number;
    });

    // End call
    document.getElementById('closeCallUI').addEventListener('click', () => {
        if (device) device.disconnectAll();
    });

    // Optional form for direct call
    document.getElementById('callForm').addEventListener('submit', e => {
        e.preventDefault();
        const number = document.getElementById('number').value.trim();
        if (!number) return alert('Please enter a phone number');

        if (!device || device.status() !== 'ready') {
            return alert('Twilio Client not ready');
        }

        device.connect({
            To: number
        });

        document.getElementById('mobileCallUI').classList.remove('hidden');
        document.getElementById('mobileNumberDisplay').textContent = number;
    });
</script>

<!-- move calling interface in screen  -->
<script>
    function makeElementDraggable(elmnt) {
        let pos1 = 0,
            pos2 = 0,
            pos3 = 0,
            pos4 = 0;

        elmnt.onmousedown = dragMouseDown;

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;

            elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
            elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        }

        function closeDragElement() {
            document.onmouseup = null;
            document.onmousemove = null;
        }
    }

    // Enable dragging for the mobile call UI box
    window.addEventListener('DOMContentLoaded', () => {
        const draggableBox = document.getElementById("draggableCallUI");
        makeElementDraggable(draggableBox);
    });
</script>


<!-- Dile you number  -->
<script>
    // Show popup
    document.getElementById("dialSelf").addEventListener("click", () => {
        document.getElementById("mobilePopup").classList.remove("hidden");
    });

    // Close popup
    document.getElementById("closePopup").addEventListener("click", () => {
        document.getElementById("mobilePopup").classList.add("hidden");
    });

    // Call via AJAX
    document.getElementById("makeCallBtn").addEventListener("click", () => {
        const number = document.getElementById("userNumberInput").value.trim();
        if (!number) {
            alert("Please enter a phone number.");
            return;
        }

        // Disable button while calling
        const callBtn = document.getElementById("makeCallBtn");
        callBtn.disabled = true;
        callBtn.classList.add("opacity-50");

        // AJAX call
        fetch("make_call.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "number=" + encodeURIComponent(number)
            })
            .then(res => res.text())
            .then(data => {
                alert("Call initiated successfully!");
                document.getElementById("mobilePopup").classList.add("hidden");
                document.getElementById("userNumberInput").value = "";
            })
            .catch(err => {
                alert("Call failed. Try again.");
                console.error(err);
            })
            .finally(() => {
                callBtn.disabled = false;
                callBtn.classList.remove("opacity-50");
            });
    });
</script>