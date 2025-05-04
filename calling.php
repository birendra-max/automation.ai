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
            <button class="bg-blue-500 text-white p-4 rounded-full hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-300">
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
                            <p class="font-semibold text-lg text-gray-800">+1234567890</p>
                            <p class="text-sm text-gray-600">Duration: 2 mins</p>
                            <p class="text-sm text-gray-600">Status: Completed</p>
                        </div>
                        <button class="text-green-500 hover:text-green-600 focus:outline-none">
                            <i class="fas fa-phone-alt text-2xl"></i> <!-- Font Awesome Phone Icon -->
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
                        <button class="text-green-500 hover:text-green-600 focus:outline-none">
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

<script>
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