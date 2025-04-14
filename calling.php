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


<script>
    let callCancelled = false;

    document.getElementById('callForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const input = document.getElementById('number').value.trim();
        if (!input) return;

        const tableWrapper = document.getElementById('callTableWrapper');
        tableWrapper.classList.remove('hidden');

        const numbers = input.split(',').map(num => num.trim());
        const tableBody = document.getElementById('callTableBody');
        tableBody.innerHTML = '';

        for (let i = 0; i < numbers.length; i++) {
            const number = numbers[i];
            callCancelled = false;

            const row = document.createElement('tr');
            row.innerHTML = `
        <td class="px-4 py-2">${number}</td>
        <td class="px-4 py-2 status">Calling...</td>
        <td class="px-4 py-2 duration">-</td>
        <td class="px-4 py-2 recording">-</td>
        <td class="px-4 py-2 download">-</td>
        <td class="px-4 py-2 call-again">
            <button class="bg-teal-500 text-white px-2 py-1 rounded hover:bg-teal-600" onclick="callAgain('${number}')">Call Again</button>
        </td>
    `;
            tableBody.appendChild(row);

            showMobileCallUI(number);

            try {
                await new Promise(resolve => setTimeout(resolve, 1000)); // Optional realism delay

                if (callCancelled) {
                    row.querySelector('.status').innerText = 'Cancelled';
                    hideMobileCallUI();
                    continue;
                }

                const response = await fetch('make_call.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `number=${encodeURIComponent(number)}`
                });

                const data = await response.json();

                row.querySelector('.status').innerText = data.status || 'Unknown';
                row.querySelector('.duration').innerText = data.duration_seconds ?
                    `${data.duration_seconds}s` : '-';
                row.querySelector('.recording').innerHTML = data.recording_url ?
                    `<a href="${data.recording_url}" target="_blank" class="text-blue-600 underline">Listen</a>` : 'N/A';
                row.querySelector('.download').innerHTML = data.recording_url ?
                    `<a href="${data.recording_url}" download class="text-green-600 underline">Download</a>` : 'N/A';
            } catch (err) {
                console.error('Call failed', err);
                row.querySelector('.status').innerText = 'Error';
            }

            hideMobileCallUI();

            // ✅ Add 2-second delay before moving to the next number
            await new Promise(resolve => setTimeout(resolve, 2000));
        }

    });

    function callAgain(number) {
        alert(`Re-calling ${number}`);
    }

    function showMobileCallUI(number) {
        document.getElementById('mobileNumberDisplay').textContent = number;
        document.getElementById('mobileCallUI').classList.remove('hidden');
    }

    function hideMobileCallUI() {
        document.getElementById('mobileCallUI').classList.add('hidden');
    }

    // When user clicks "End Call"
    document.getElementById('closeCallUI').addEventListener('click', function() {
        callCancelled = true;
        hideMobileCallUI();
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