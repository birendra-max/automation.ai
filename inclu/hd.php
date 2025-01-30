<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automation | AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="public/js/jQuery.js"></script>
    <!-- <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css" crossorigin>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5-premium-features/44.1.0/ckeditor5-premium-features.css" crossorigin> -->
    <!-- include libraries(jQuery, bootstrap) -->
    <style>
        #editor {
            height: 400px;
            width: 100%;
        }
    </style>

    <script>
        // Function to toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        }
    </script>

    <!-- <script>
        $(document).ready(function() {
            let userData = JSON.parse(localStorage.getItem('userDetails'));
            if (userData.role == 'user') {
                $("#dashboard").hide();
            } else {
                $("#dashboard").show();
            }
        })
    </script> -->
</head>

<body>
    <div class="flex h-screen antialiased bg-gray-800">
        <!-- Sidebar -->
        <div id="sidebar" class="hidden md:flex flex-col w-64 bg-gray-800">
            <div class="flex items-center justify-center h-16 bg-gray-800 border-b border-gray-200">
                <span class="text-white text-xl font-bold uppercase">AutomationAI.com </span>
            </div>
            <div class="flex flex-col flex-1 overflow-y-auto">
                <nav class="flex-1 px-2 py-4 bg-gray-800">
                    <?php

                    if (isset($_SESSION['user_details'])) {
                        if ($_SESSION['user_details']['role'] == 'admin') {
                            // Admin menu options
                    ?>
                            <a href="dashboard.php" class="flex items-center text-white px-6 py-3 hover:bg-gray-700" id="dashboard">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10M7 17h10" />
                                </svg>
                                Dashboard
                            </a>
                            <a href="mailai.php" class="flex items-center text-white px-6 py-3 hover:bg-gray-700" id="mailai">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.5 5L18 8M21 12V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6" />
                                </svg>
                                Mail Automation | AI
                            </a>
<<<<<<< HEAD

                            <a href="inbox.php" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700" id="mailai">
=======
                            <a href="inbox.php" class="flex items-center text-white px-6 py-3 hover:bg-gray-700" id="inbox">
>>>>>>> 716e5f61ed08a7f3b8c1112f6968cc831b0e4470
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.5 5L18 8M21 12V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6" />
                                </svg>
                                Inbox | AI
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
            <div class="flex items-center justify-between h-16 bg-gray-100 border-b border-gray-200 p-4">
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