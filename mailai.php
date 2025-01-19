<script>
    document.title = 'Mail Automation | AI'
</script>

<?php
include 'inclu/hd.php';
?>

<section class="mt-12">
    <div class="bg-gray-100 text-black rounded-3xl shadow-xl w-full overflow-hidden mx-auto max-w-5xl">
        <div class="w-full flex justify-center items-center">
            <form class="w-full py-10 px-5 md:px-10" id="signupform">
                <div class="text-center mb-10">
                    <h1 class="font-bold text-3xl text-gray-900">Email Automation</h1>
                    <p class="text-gray-600">Upload your Excel file to send emails. Send a batch of emails with a single click.</p>
                </div>

                <!-- File Upload Section -->
                <div class="flex flex-col mb-6 mx-8">
                    <div x-data="{ files: null }" id="FileUpload"
                        class="block w-full py-6 px-6 relative bg-white border-2 border-gray-300 rounded-md hover:shadow-lg focus:ring-2 focus:ring-indigo-500 transition-all duration-300">
                        <input type="file" accept=".xlsx,.xls"
                            class="absolute inset-0 m-0 p-0 w-full h-full opacity-0 cursor-pointer"
                            x-on:change="files = $event.target.files; console.log($event.target.files);"
                            x-on:dragover="$el.classList.add('active')" x-on:dragleave="$el.classList.remove('active')" x-on:drop="$el.classList.remove('active')" required>

                        <!-- File selected template -->
                        <template x-if="files !== null">
                            <div class="flex flex-col space-y-2">
                                <template x-for="(_,index) in Array.from({ length: files.length })">
                                    <div class="flex flex-row items-center space-x-2">
                                        <template x-if="files[index].type.includes('excel/')">
                                            <i class="fas fa-file-excel text-green-500"></i>
                                        </template>
                                        <span class="font-medium text-gray-900" x-text="files[index].name"></span>
                                        <span class="text-xs text-gray-500" x-text="filesize(files[index].size)">...</span>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- Default upload instructions -->
                        <template x-if="files === null">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-500"></i>
                                <p class="text-gray-700 text-sm">Drag your Excel file here or click to select one.</p>
                            </div>
                        </template>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
                <script src="https://cdn.filesizejs.com/filesize.min.js"></script>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" rel="stylesheet" />

                <!-- Submit and Reset Buttons -->
                <div class="flex flex-col md:flex-row mt-6 gap-4 w-full">
                    <button type="submit" name="signUpForm" value="i_want_to_sign_up"
                        class="w-full md:w-auto bg-indigo-600 text-white rounded-lg px-6 py-3 md:ml-8 font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 transition-all duration-300">
                        Send Mail
                    </button>
                    <div class="md:ml-10">
                        <?php
                        include 'inclu/spinner.php';
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<script>

</script>

<?php
include 'inclu/footer.php';
?>