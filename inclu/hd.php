<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automation | AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Function to toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        }
    </script>
</head>

<body>
    <div class="flex h-screen antialiased bg-gray-200">
        <!-- Sidebar -->
        <div id="sidebar" class="hidden md:flex flex-col w-64 bg-gray-800">
            <div class="flex items-center justify-center h-16 bg-gradient-to-tr from-indigo-600 to-violet-600">
                <span class="text-white font-bold uppercase">AutomationAI.com</span>
            </div>
            <div class="flex flex-col flex-1 overflow-y-auto">
                <nav class="flex-1 px-2 py-4 bg-gradient-to-tr from-indigo-600 to-violet-600">
                    <a href="index.php" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v18H3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 12h10M7 17h10" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="mailai.php" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.5 5L18 8M21 12V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6" />
                        </svg>
                        Mail | AI
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Messages
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Settings
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <div class="flex items-center justify-between h-16 bg-white border-b border-gray-200 p-4">
                <div class="flex items-center px-4">
                    <!-- Hamburger Menu for Sidebar Toggle -->
                    <button onclick="toggleSidebar()" class="text-gray-500 focus:outline-none focus:text-gray-700 md:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Search Input -->
                    <form action="" class="flex justify-center items-center space-x-2 w-full max-w-4xl mx-auto ">
                        <input
                            class="w-full md:w-80 border rounded-md px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            type="text"
                            placeholder="Search"
                            aria-label="Search">
                        <button
                            type="submit"
                            class="w-full md:w-auto flex items-center justify-center rounded-md bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 py-2 px-4"
                            aria-label="Search button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M18 10a8 8 0 1 1-8-8 8 8 0 0 1 8 8z" />
                            </svg>
                        </button>
                    </form>

                </div>
            </div>

            <!-- Main Section -->
            <main class="p-4">
                <!-- Content goes here -->