<script>
    document.title = 'Auto Calling';
</script>

<?php
include 'inclu/hd.php';
include 'inclu/config.php'
?>

<script src="public/js/uidraggable.js"></script>


<section class="flex">

    <!-- Left Side: Mobile Dialer Interface -->
    <div class="w-full md:w-1/3 p-6 bg-white flex flex-col items-center shadow-xl rounded-r-2xl">
        <!-- Dialer Input -->
        <input type="text" id="dialer-input" class="w-full p-3 text-2xl text-center border-2 border-gray-300 rounded-lg mb-6 font-semibold focus:outline-none focus:border-blue-500" placeholder="Enter number" inputmode="tel" oninput="this.value = this.value.replace(/[^0-9*#+]/g, '')" />

        <!-- Dial Pad -->
        <div class="grid grid-cols-3 gap-4 w-4/5 mb-4">
            <button class="dial-btn p-4 bg-gray-200 text-xl rounded-full hover:bg-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500">1</button>
            <button class="dial-btn p-4 bg-gray-200 text-xl rounded-full hover:bg-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500">2</button>
            <button class="dial-btn p-4 bg-gray-200 text-xl rounded-full hover:bg-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500">3</button>
            <button class="dial-btn p-4 bg-gray-200 text-xl rounded-full hover:bg-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500">4</button>
            <button class="dial-btn p-4 bg-gray-200 text-xl rounded-full hover:bg-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500">5</button>
            <button class="dial-btn p-4 bg-gray-200 text-xl rounded-full hover:bg-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500">6</button>
            <button class="dial-btn p-4 bg-gray-200 text-xl rounded-full hover:bg-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500">7</button>
            <button class="dial-btn p-4 bg-gray-200 text-xl rounded-full hover:bg-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500">8</button>
            <button class="dial-btn p-4 bg-gray-200 text-xl rounded-full hover:bg-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500">9</button>
            <button class="dial-btn p-4 bg-gray-200 text-xl rounded-full hover:bg-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500">*</button>
            <button class="dial-btn p-4 bg-gray-200 text-xl rounded-full hover:bg-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500">0</button>
            <button class="dial-btn p-4 bg-gray-200 text-xl rounded-full hover:bg-gray-300 focus:outline-none focus:ring-4 focus:ring-blue-500">#</button>
        </div>

        <!-- Call Action Buttons -->
        <div class="flex space-x-6 w-4/5 justify-center">

            <!-- Save Number Button -->
            <button onclick="saveNumber()" class="bg-green-500 text-white p-4 rounded-full hover:bg-green-600 focus:outline-none focus:ring-4 focus:ring-green-300">
                <i class="fas fa-bookmark fa-lg"></i>
            </button>

            <!-- Call Button -->
            <button id="makeCallBtn" class="bg-blue-500 text-white p-4 rounded-full hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-300">
                <i class="fas fa-phone-alt text-2xl"></i>
            </button>

            <!-- Clear Button -->
            <button id="clear-btn" class="bg-yellow-400 text-white p-4 rounded-full hover:bg-yellow-500 focus:outline-none focus:ring-4 focus:ring-yellow-300">
                <i class="fas fa-trash fa-lg"></i>
            </button>
        </div>

    </div>

    <!-- Script to handle input and clear -->
    <script>
        // Append digits to input
        document.querySelectorAll('.dial-btn').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById('dialer-input');
                input.value += button.textContent;
            });
        });

        // Clear input
        document.getElementById('clear-btn').addEventListener('click', () => {
            document.getElementById('dialer-input').value = '';
        });
    </script>


    <!-- Right Side: Call History -->
    <div class="w-full md:w-1/3 p-6 bg-gray-50 shadow-xl rounded-r-2xl ml-4">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Call History</h2>
        <div class="max-h-[450px] bg-gray-50 overflow-y-auto overflow-x-hidden">
            <?php
            $que = "select * from call_history";
            $res = mysqli_query($conn, $que);
            if (mysqli_num_rows($res) > 0) {
                while ($r = mysqli_fetch_assoc($res)) {
            ?>
                    <!-- History Item 1 -->
                    <div class="bg-white p-4 rounded-xl shadow-lg mb-6 transition-all hover:scale-105 hover:shadow-2xl flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-lg text-gray-800"><?php echo $r['phone']; ?></p>
                            <p class="text-sm text-gray-600">Duration: <?php echo $r['call_duration']; ?></p>
                            <p class="text-sm text-gray-600">Status: <?php echo $r['call_status']; ?></p>
                        </div>
                        <button onclick="makeCall('<?php echo $r['phone']; ?>')" class="text-green-500 hover:text-green-600 focus:outline-none">
                            <i class="fas fa-phone-alt text-2xl"></i>
                        </button>

                    </div>
            <?php  }
            } else {
                echo '<p class="text-gray-600">No history found.</p>';
            }  ?>
        </div>
    </div>

    <!-- Right Side: Saved Numbers -->
    <div class="w-full md:w-1/3 p-6 bg-gray-50 shadow-xl rounded-r-2xl ml-4">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Saved Numbers</h2>
        <div class="max-h-[450px] bg-gray-50 overflow-y-auto overflow-x-hidden">
            <?php
            $que = "select * from saved_numbers";
            $res = mysqli_query($conn, $que);
            if (mysqli_num_rows($res) > 0) {
                while ($r = mysqli_fetch_assoc($res)) {
            ?>
                    <!-- Saved Number Item 1 -->
                    <div class="bg-white p-4 rounded-xl shadow-lg mb-6 transition-all hover:scale-105 hover:shadow-2xl flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-lg text-gray-800"><?php echo $r['phone']; ?></p>
                            <p class="text-sm text-gray-600">Saved as: <?php echo $r['name']; ?></p>
                        </div>
                        <button onclick="makeCall('<?php echo $r['phone']; ?>')" class="text-green-500 hover:text-green-600 focus:outline-none">
                            <i class="fas fa-phone-alt text-2xl"></i>
                        </button>
                    </div>
            <?php  }
            } else {
                echo '<p class="text-gray-600">No saved numbers found.</p>';
            }  ?>
        </div>
    </div>
</section>


<section>
    <!-- Mobile Call UI -->
    <div id="mobileCallUI" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div id="draggableCallUI"
            class="w-[20%] h-[50%] bg-[#1a1a1a] text-white rounded-2xl shadow-2xl flex flex-col items-center justify-between py-10 px-8 cursor-move absolute">
            <div class="flex flex-col items-center">
                <div class="w-28 h-28 rounded-full bg-teal-600 flex items-center justify-center mb-6 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 10a4 4 0 100-8 4 4 0 000 8zm-6 8a6 6 0 1112 0H4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-3xl font-bold mb-2">Calling...</p>
                <p id="mobileNumberDisplay" class="text-lg text-teal-400">+0000000000</p>
            </div>

            <div class="grid grid-cols-4 gap-4 mt-8">
                <button id="pauseCall" class="w-14 h-14 rounded-full bg-yellow-400 flex items-center justify-center hover:bg-yellow-500 transition duration-300 shadow-md" title="Pause Call">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6 4h2v12H6V4zm6 0h2v12h-2V4z" />
                    </svg>
                </button>

                <button id="startRecording" class="w-14 h-14 rounded-full bg-blue-500 flex items-center justify-center hover:bg-blue-600 transition duration-300 shadow-md" title="Start Recording">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <circle cx="10" cy="10" r="5" />
                    </svg>
                </button>

                <button id="stopRecording" class="w-14 h-14 rounded-full bg-gray-600 flex items-center justify-center hover:bg-gray-700 transition duration-300 shadow-md" title="Stop Recording">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <rect x="6" y="6" width="8" height="8" />
                    </svg>
                </button>

                <!-- Dialer Button -->
                <button id="openDialer"
                    class="w-14 h-14 rounded-full bg-purple-600 flex items-center justify-center hover:bg-purple-700 transition duration-300 shadow-md"
                    title="Open Dial Pad">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M4 3h2v2H4V3zm5 0h2v2H9V3zm5 0h2v2h-2V3zM4 8h2v2H4V8zm5 0h2v2H9V8zm5 0h2v2h-2V8zM4 13h2v2H4v-2zm5 0h2v2H9v-2zm5 0h2v2h-2v-2z" />
                    </svg>
                </button>
            </div>

            <div class="mt-6">
                <button id="closeCallUI" class="w-14 h-14 rounded-full bg-red-600 flex items-center justify-center hover:bg-red-700 transition duration-300 shadow-md" title="End Call">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Dial Pad Modal -->
            <div id="dialPad"
                class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-80 flex items-center justify-center hidden z-50">
                <div class="bg-[#2a2a2a] text-white p-6 rounded-xl shadow-xl w-64">
                    <div id="dialedNumber" class="text-2xl text-center mb-4 border-b border-gray-500 pb-2"></div>
                    <div class="grid grid-cols-3 gap-4 text-center text-lg">
                        <button class="dial-btn py-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">1</button>
                        <button class="dial-btn py-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">2</button>
                        <button class="dial-btn py-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">3</button>
                        <button class="dial-btn py-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">4</button>
                        <button class="dial-btn py-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">5</button>
                        <button class="dial-btn py-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">6</button>
                        <button class="dial-btn py-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">7</button>
                        <button class="dial-btn py-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">8</button>
                        <button class="dial-btn py-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">9</button>
                        <button class="dial-btn py-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">*</button>
                        <button class="dial-btn py-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">0</button>
                        <button class="dial-btn py-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition">#</button>
                    </div>
                    <div class="mt-4 flex justify-between">
                        <button id="dialClear" class="bg-red-600 px-4 py-2 rounded hover:bg-red-700">Clear</button>
                        <button id="dialClose" class="bg-teal-600 px-4 py-2 rounded hover:bg-teal-700">Done</button>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        const openDialerBtn = document.getElementById("openDialer");
        const dialPad = document.getElementById("dialPad");
        const dialClose = document.getElementById("dialClose");
        const dialClear = document.getElementById("dialClear");
        const dialedNumberDisplay = document.getElementById("dialedNumber");
        const mobileNumberDisplay = document.getElementById("mobileNumberDisplay");

        openDialerBtn.addEventListener("click", () => {
            dialPad.classList.remove("hidden");
        });

        dialClose.addEventListener("click", () => {
            dialPad.classList.add("hidden");

            const dialed = dialedNumberDisplay.textContent.trim();
            if (dialed.length > 0) {
                mobileNumberDisplay.textContent = dialed;
            }
        });

        dialClear.addEventListener("click", () => {
            dialedNumberDisplay.textContent = "";
        });

        document.querySelectorAll(".dial-btn").forEach(button => {
            button.addEventListener("click", () => {
                const digit = button.textContent.trim();

                // Update the display with the clicked digit
                dialedNumberDisplay.textContent += digit;

                // Send DTMF digit
                sendDTMF(digit);
            });
        });

        // Function to send DTMF digits during a call
        function sendDTMF(digit) {
            if (currentConnection && currentConnection.sendDigits) {
                currentConnection.sendDigits(digit); // Sends the DTMF digit to the active call
                console.log('Sent DTMF digit:', digit);
            } else {
                console.warn('No active connection or sendDigits method not available.');
            }
        }
    </script>
</section>


<script>
    let device;
    let currentConnection = null;

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

            device.on('connect', conn => {
                console.log('Call connected:', conn.parameters);
                document.getElementById('mobileCallUI').classList.remove('hidden');
                currentConnection = conn;
            });

            // Handle incoming calls
            device.on('incoming', (connection) => {
                console.log('Incoming call from: ', connection.parameters.From);
                document.getElementById('mobileCallUI').classList.remove('hidden');
                document.getElementById('mobileNumberDisplay').textContent = connection.parameters.From;
                currentConnection = connection;
            });

        } catch (err) {
            console.error('Token fetch failed:', err);
            alert('Could not connect to Twilio');
        }
    }

    setupTwilioClient();


    document.getElementById("makeCallBtn").addEventListener("click", function() {
        const number = document.getElementById("dialer-input").value.trim();
        if (!number) return alert("Enter a phone number");

        const finalNumber = formatNumber(number);

        if (!device || device.status() !== 'ready') {
            return alert("Twilio Client not ready");
        }

        device.connect({
            To: finalNumber
        });
        document.getElementById('mobileNumberDisplay').textContent = finalNumber;
    });


    function makeCall(number) {
        const finalNumber = formatNumber(number);

        if (!finalNumber) {
            alert("Enter a valid phone number");
            return;
        }

        if (!device || device.status() !== 'ready') {
            alert("Twilio Client not ready");
            return;
        }

        device.connect({
            To: finalNumber
        });
        document.getElementById('mobileNumberDisplay').textContent = finalNumber;
        document.getElementById('mobileCallUI').classList.remove('hidden');
    }



    // End call
    document.getElementById("closeCallUI").addEventListener("click", () => {
        if (currentConnection) currentConnection.disconnect();
        if (device) device.disconnectAll();
    });

    // End call (answering the call)
    document.getElementById("closeCallUI").addEventListener("click", () => {
        if (device) device.disconnectAll();
    });

    function formatNumber(num) {
        let formatted = num.replace(/\s+/g, '');
        if (!formatted.startsWith('+')) {
            formatted = '+' + formatted;
        }
        return formatted;
    }

    // Dragging functionality
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

    window.addEventListener('DOMContentLoaded', () => {
        const draggableBox = document.getElementById("draggableCallUI");
        makeElementDraggable(draggableBox);
    });


    function saveNumber() {
        const number = document.getElementById('dialer-input').value.trim();

        if (number === '') {
            alert('Please enter a number to save.');
        } else {
            const name = prompt("Enter a name for this number:");

            if (name && name.trim() !== '') {
                $.ajax({
                    url: "save_n.php",
                    type: "POST",
                    data: {
                        phone: number,
                        name: name.trim()
                    },
                    success: function(response) {
                        alert("Number saved successfully!"); // Show alert first
                        window.location.reload(); // Then reload the page
                        document.getElementById('dialer-input').value = ''; // Optionally clear the input after reload
                    },

                    error: function(xhr, status, error) {
                        console.error("Error saving number:", error);
                        alert("Failed to save the number. Please try again.");
                    }
                });
            } else {
                alert("Name is required to save the number.");
            }
        }
    }
</script>