<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automation | AI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="public/js/jQuery.js"></script>

    <!-- Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


    <!-- Summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

    <script src="https://media.twiliocdn.com/sdk/js/client/v1.13/twilio.min.js"></script>


    <style>
        #editor {
            height: 400px;
            width: 100%;
        }
    </style>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        }
    </script>
</head>

<body>
    <div class="flex h-screen antialiased">
        <!-- Sidebar -->
        <div id="sidebar" class="hidden md:flex flex-col w-64 bg-slate-950">
            <div class="flex items-center justify-center h-16 bg-slate-950">
                <span class="text-white font-bold uppercase">Automation </span>
            </div>
            <div class="flex flex-col flex-1 overflow-y-auto border-t-4 border-white">
                <nav class="flex-1 px-2 py-4 bg-slate-950">
                    <?php

                    if (isset($_SESSION['user_details'])) {
                        if ($_SESSION['user_details']['role'] == 'admin') {
                            // Admin menu options
                    ?>
                            <a href="dashboard.php" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700" id="dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10M7 17h10" />
                                </svg>
                                Dashboard
                            </a>
                            <a href="mailai.php" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700" id="mailai">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.5 5L18 8M21 12V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6" />
                                </svg>
                                Mail Automation | AI
                            </a>

                            <a href="inbox.php" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700" id="inbox">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.5 5L18 8M21 12V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6" />
                                </svg>
                                Inbox | AI
                            </a>


                            <a href="calling.php" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700" id="calling">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="h-4 w-4 mr-2" fill="white">
                                    <path d="M280 0C408.1 0 512 103.9 512 232c0 13.3-10.7 24-24 24s-24-10.7-24-24c0-101.6-82.4-184-184-184c-13.3 0-24-10.7-24-24s10.7-24 24-24zm8 192a32 32 0 1 1 0 64 32 32 0 1 1 0-64zm-32-72c0-13.3 10.7-24 24-24c75.1 0 136 60.9 136 136c0 13.3-10.7 24-24 24s-24-10.7-24-24c0-48.6-39.4-88-88-88c-13.3 0-24-10.7-24-24zM117.5 1.4c19.4-5.3 39.7 4.6 47.4 23.2l40 96c6.8 16.3 2.1 35.2-11.6 46.3L144 207.3c33.3 70.4 90.3 127.4 160.7 160.7L345 318.7c11.2-13.7 30-18.4 46.3-11.6l96 40c18.6 7.7 28.5 28 23.2 47.4l-24 88C481.8 499.9 466 512 448 512C200.6 512 0 311.4 0 64C0 46 12.1 30.2 29.5 25.4l88-24z" />
                                </svg>
                                Auto Calling
                            </a>

                            <a href="call_schedul.php" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700" id="call_schudle">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="h-4 w-4 mr-2" fill="white">
                                    <path d="M280 0C408.1 0 512 103.9 512 232c0 13.3-10.7 24-24 24s-24-10.7-24-24c0-101.6-82.4-184-184-184c-13.3 0-24-10.7-24-24s10.7-24 24-24zm8 192a32 32 0 1 1 0 64 32 32 0 1 1 0-64zm-32-72c0-13.3 10.7-24 24-24c75.1 0 136 60.9 136 136c0 13.3-10.7 24-24 24s-24-10.7-24-24c0-48.6-39.4-88-88-88c-13.3 0-24-10.7-24-24zM117.5 1.4c19.4-5.3 39.7 4.6 47.4 23.2l40 96c6.8 16.3 2.1 35.2-11.6 46.3L144 207.3c33.3 70.4 90.3 127.4 160.7 160.7L345 318.7c11.2-13.7 30-18.4 46.3-11.6l96 40c18.6 7.7 28.5 28 23.2 47.4l-24 88C481.8 499.9 466 512 448 512C200.6 512 0 311.4 0 64C0 46 12.1 30.2 29.5 25.4l88-24z" />
                                </svg>
                                Call Schedule
                            </a>

                        <?php
                        } elseif ($_SESSION['user_details']['role'] == 'user') {
                            // User menu options
                        ?>
                            <a href="mailai.php" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700" id="mailai">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.5 5L18 8M21 12V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6" />
                                </svg>
                                Mail Automation | AI
                            </a>
                    <?php
                        }
                    } else {
                        // Redirect to login page if the user is not logged in
                        header('Location: index.php');
                        exit; // Make sure to call exit() after header redirect to stop further execution
                    }
                    ?>
                </nav>
            </div>
        </div>


        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-y-auto w-full bg-gradient-to-r from-blue-50 via-indigo-100 to-blue-50">
            <!-- Top Bar -->
            <div class="flex items-center justify-between h-16 bg-white border-b border-gray-200 p-4 px-4">
                <div class="flex items-center px-4">
                    <!-- Hamburger Menu for Sidebar Toggle -->
                    <button onclick="toggleSidebar()" class="text-gray-500 focus:outline-none focus:text-gray-700 md:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Search Input -->
                    <form action="" class="flex justify-center items-center space-x-2 w-full max-w-4xl mx-auto mt-4">
                        <input
                            class="w-full md:w-80 border rounded-md px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            type="text"
                            placeholder="Search"
                            aria-label="Search">
                        <button
                            type="submit"
                            class="w-full md:w-auto flex items-center justify-center rounded-md bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 py-2 px-4"
                            aria-label="Search button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M18 10a8 8 0 1 1-8-8 8 8 0 0 1 8 8z" />
                            </svg>
                        </button>
                    </form>
                </div>
                <a href="logout.php" class="flex items-center text-white bg-red-600 border border-red-600 py-2 px-6 gap-2 rounded-lg inline-flex items-center transition duration-300 ease-in-out hover:bg-red-700 hover:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                    <span>
                        Logout
                    </span>
                    <svg class="w-4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        viewBox="0 0 24 24" class="w-6 h-6 ml-2">
                        <path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>

            </div>

            <!-- Main Section -->
            <main class="p-4">
                <!-- Content goes here -->