<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<script src="public/js/jQuery.js"></script>
<?php session_start(); ?>
<section>
    <div class="font-[sans-serif] bg-white flex items-center justify-center md:h-screen p-4">
        <div class="shadow-[0_2px_16px_-3px_rgba(6,81,237,0.3)] max-w-6xl max-md:max-w-lg rounded-md p-6">
            <a href="javascript:void(0)" class="flex items-center space-x-4">
                <!-- Logo Image -->
                <img
                    src="https://sevaa.com/app/uploads/2019/04/ft-img-email.png"
                    alt="Mail Automation Logo"
                    class="w-36 md:w-40 lg:w-48 rounded-lg shadow-lg" />
                <!-- Text Container -->
                <div>
                    <h3 class="text-3xl md:text-3xl font-semibold text-blue-600 mt-2 mb-2">
                        Mail Automation.Ai
                    </h3>
                    <p class="text-lg text-gray-600 max-w-xs">Streamline your email marketing and automation with ease.</p>
                </div>
            </a>


            <div class="grid md:grid-cols-2 items-center gap-8">
                <div class="max-md:order-1">
                    <img src="https://readymadeui.com/signin-image.webp" class="w-full aspect-[12/11] object-contain" alt="login-image" />
                </div>

                <form action="validate_login.php" method="post" class="md:max-w-md w-full mx-auto" id="loginform">
                    <div class="text-xl text-red-500 font-bold text-center" id="errormsg">
                        <p><?php if (isset($_SESSION['error'])) {
                                echo $_SESSION['error'];
                            } ?></p>
                    </div>
                    <div class="mb-12">
                        <h3 class="text-4xl font-bold text-blue-600">Sign in</h3>
                    </div>

                    <div>
                        <div class="relative flex items-center">
                            <input name="email" type="text" required class="w-full text-sm border-b border-gray-300 focus:border-blue-600 px-2 py-3 outline-none" placeholder="Enter email" />
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb" class="w-[18px] h-[18px] absolute right-2" viewBox="0 0 682.667 682.667">
                                <defs>
                                    <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                        <path d="M0 512h512V0H0Z" data-original="#000000"></path>
                                    </clipPath>
                                </defs>
                                <g clip-path="url(#a)" transform="matrix(1.33 0 0 -1.33 0 682.667)">
                                    <path fill="none" stroke-miterlimit="10" stroke-width="40" d="M452 444H60c-22.091 0-40-17.909-40-40v-39.446l212.127-157.782c14.17-10.54 33.576-10.54 47.746 0L492 364.554V404c0 22.091-17.909 40-40 40Z" data-original="#000000"></path>
                                    <path d="M472 274.9V107.999c0-11.027-8.972-20-20-20H60c-11.028 0-20 8.973-20 20V274.9L0 304.652V107.999c0-33.084 26.916-60 60-60h392c33.084 0 60 26.916 60 60v196.653Z" data-original="#000000"></path>
                                </g>
                            </svg>
                        </div>
                    </div>

                    <div class="mt-8">
                        <div class="relative flex items-center">
                            <input name="password" type="password" required class="w-full text-sm border-b border-gray-300 focus:border-blue-600 px-2 py-3 outline-none" placeholder="Enter password" id="password" />
                            <!-- Eye open (default) icon -->
                            <i id="eye-open-icon" class="fas fa-eye absolute right-2 cursor-pointer text-gray-500"></i>

                            <!-- Eye slash icon (hidden initially) -->
                            <i id="eye-slash-icon" class="fas fa-eye-slash absolute right-2 cursor-pointer text-gray-500 hidden"></i>
                        </div>
                    </div>

                    <!-- <div class="flex flex-wrap items-center justify-between gap-4 mt-6">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 shrink-0 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                            <label for="remember-me" class="text-gray-800 ml-3 block text-sm">
                                Remember me
                            </label>
                        </div>
                        <div>
                            <a href="javascript:void(0);" class="text-blue-600 font-semibold text-sm hover:underline">
                                Forgot Password?
                            </a>
                        </div>
                    </div> -->

                    <div class="mt-12">
                        <?php include 'inclu/spinner.php' ?>
                        <button type="submit" id="login" class="w-full shadow-xl py-2.5 px-4 text-sm font-semibold tracking-wide rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            Sign in
                        </button>
                    </div>
                </form>

                <!-- JavaScript to toggle eye open/eye slash icon -->
                <script>
                    // Get the password field and eye icons
                    const passwordField = document.getElementById("password");
                    const eyeOpenIcon = document.getElementById("eye-open-icon");
                    const eyeSlashIcon = document.getElementById("eye-slash-icon");

                    // Add click event listener to toggle password visibility
                    eyeOpenIcon.addEventListener("click", function() {
                        // Toggle the password field visibility
                        if (passwordField.type === "password") {
                            passwordField.type = "text"; // Show password
                            eyeOpenIcon.classList.add("hidden"); // Hide open eye
                            eyeSlashIcon.classList.remove("hidden"); // Show eye slash
                        } else {
                            passwordField.type = "password"; // Hide password
                            eyeOpenIcon.classList.remove("hidden"); // Show open eye
                            eyeSlashIcon.classList.add("hidden"); // Hide eye slash
                        }
                    });

                    // Add click event listener for eye slash icon to toggle visibility
                    eyeSlashIcon.addEventListener("click", function() {
                        // Toggle the password field visibility
                        if (passwordField.type === "password") {
                            passwordField.type = "text"; // Show password
                            eyeOpenIcon.classList.add("hidden"); // Hide open eye
                            eyeSlashIcon.classList.remove("hidden"); // Show eye slash
                        } else {
                            passwordField.type = "password"; // Hide password
                            eyeOpenIcon.classList.remove("hidden"); // Show open eye
                            eyeSlashIcon.classList.add("hidden"); // Hide eye slash
                        }
                    });
                </script>

            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        setInterval(function() {
            $('#errormsg').hide();
        }, 5000)
    })
</script>