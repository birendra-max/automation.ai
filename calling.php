<script>
    document.title = 'Auto Calling';
</script>

<?php
include 'inclu/hd.php';
?>

<!-- Table to display call statuses -->
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
                required>
            <button
                type="submit"
                class="save-btn px-4 py-2 mt-4 bg-teal-600 text-white rounded hover:bg-teal-700"
                id="sendmail">
                Start Calling
            </button>
        </form>

        <!-- Table Section (initially hidden) -->
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

<script>
    // Show mobile UI and overlay when form is submitted
    document.getElementById('callForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const input = document.getElementById('number').value.trim();
        if (!input) return;

        // Show the table wrapper
        const tableWrapper = document.getElementById('callTableWrapper');
        tableWrapper.classList.remove('hidden');

        const numbers = input.split(',').map(num => num.trim());
        const tableBody = document.getElementById('callTableBody');

        numbers.forEach(number => {
            // Extract the phone number (remove country code)
            const localNumber = removeCountryCode(number); // Remove country code

            // Create and insert row with loading placeholders
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-4 py-2">${localNumber}</td>  <!-- Display local number -->
                <td class="px-4 py-2 status">Calling...</td>
                <td class="px-4 py-2 duration">-</td>
                <td class="px-4 py-2 recording">-</td>
                <td class="px-4 py-2 download">-</td>
                <td class="px-4 py-2 call-again">-</td>
            `;
            tableBody.appendChild(row);

            // Simulate calling and updating the status
            setTimeout(() => {
                fetch('make_call.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `number=${encodeURIComponent(number)}`
                    })
                    .then(res => res.json())
                    .then(data => {
                        row.querySelector('.status').innerText = data.status || 'Unknown';
                        row.querySelector('.duration').innerText = data.duration_seconds ? `${data.duration_seconds}s` : '-';
                        row.querySelector('.recording').innerHTML = data.recording_url ?
                            `<a href="${data.recording_url}" target="_blank" class="text-blue-600 underline">Listen</a>` :
                            'N/A';
                        row.querySelector('.download').innerHTML = data.recording_url ?
                            `<a href="${data.recording_url}" download class="text-green-600 underline">Download</a>` :
                            'N/A';

                        row.querySelector('.call-again').innerHTML = `<button class="bg-teal-500 text-white px-2 py-1 rounded hover:bg-teal-600" onclick="callAgain('${number}')">Call Again</button>`;
                    })
                    .catch(err => {
                        console.error('Call failed', err);
                        row.querySelector('.status').innerText = 'Error';
                    });
            }, 2000); // Simulate delay in calling
        });

        // Show the mobile UI interface and overlay
        document.getElementById('mobileCallUI').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    });

    // Close the mobile UI
    document.getElementById('closeCallUI').addEventListener('click', function() {
        document.getElementById('mobileCallUI').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    });

    function callAgain(number) {
        alert(`Re-calling ${number}`);
    }

    // Function to remove the country code from the phone number
    function removeCountryCode(phoneNumber) {
        // Remove the country code (starts with + followed by digits)
        const regex = /^\+(\d+)(\d+)$/;
        const match = phoneNumber.match(regex);

        if (match) {
            return phoneNumber.replace(match[0], '').trim(); // Remove country code part
        }

        // If no country code is found, return the original phone number
        return phoneNumber;
    }
</script>
