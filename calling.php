<script>
    document.title = 'Auto Calling';
</script>

<?php
include 'inclu/hd.php';
?>


<!-- UI + Table Section -->
<section class="max-w-7xl mx-auto bg-white border border-gray-300 shadow-lg rounded-lg p-8 mt-8">
    <div class="px-6 py-4 faq-answer">
        <form id="callForm" class="mb-4">
            <label for="number" class="block text-teal-600 font-medium text-2xl mb-2">Phone Numbers</label>
            <input
                type="text"
                id="number"
                class="w-full px-4 py-3 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                placeholder="Enter recipient numbers (comma-separated for multiple)"
                name="number"
                required />
            <button
                type="submit"
                class="save-btn px-4 py-2 mt-4 bg-teal-600 text-white rounded hover:bg-teal-700"
                id="sendmail">
                Start Calling
            </button>
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
<div id="mobileCallUI" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
    <div class="text-center text-white">
        <p class="text-3xl font-semibold mb-4">Calling...</p>
        <p id="mobileNumberDisplay" class="text-xl text-teal-300"></p>
        <div class="mt-6">
            <button id="closeCallUI" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">End Call</button>
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

        for (const number of numbers) {
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

            showMobileCallUI(number); // Show overlay

            try {
                await new Promise(resolve => setTimeout(resolve, 1000)); // Optional delay for realism

                if (callCancelled) {
                    row.querySelector('.status').innerText = 'Cancelled';
                    hideMobileCallUI();
                    continue; // Skip to next number
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