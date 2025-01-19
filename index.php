<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Autimation | AI</title>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="font-sans antialiased bg-gray-900">

    <?php
        include 'inclu/hd.php';
    ?>

    <section>
        <div class="max-w-3xl mx-auto text-center mt-16">
            <h1 class="text-4xl font-bold text-gray-900 leading-tight mb-2 border-t-4 border-b-4 border-purple-600 py-4">
                Fancy Heading
            </h1>
            <p class="text-lg text-gray-800 mb-8">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </div>
        <br>
        <br>
        <br>

        <div id="signUpform" class="fixed inset-0 z-50 grid place-items-center max-h-screen backdrop-blur-sm w-full h-full justify-center items-center text-white bg-black bg-cover" style="background-image: url('https://pagedone.io/asset/uploads/1691055810.png');">
            <div class="bg-gray-100 text-black rounded-3xl shadow-xl w-full max-w-9xl overflow-hidden">
                <div class="md:flex w-full">
                    <div class="hidden md:block w-1/2 bg-indigo-500 py-10 px-10">
                        <!-- You can replace the SVG below with your own custom SVG -->
                        <img src="https://media.licdn.com/dms/image/v2/D4E12AQHr8PiBfk7Cww/article-cover_image-shrink_600_2000/article-cover_image-shrink_600_2000/0/1699752616757?e=2147483647&v=beta&t=fg_3LUbLoL3qMHP1lFYOLveBVdYNvqV_ejWqXa0Cpk0" alt="" class="w-full h-96">
                    </div>
                    <div class="w-full md:w-1/2 py-10 px-5 md:px-10">
                        <div class="text-center mb-10">
                            <h1 class="font-bold text-3xl text-gray-900">Email Automation</h1>
                            <p>Upload your file to send mail automatically</p>
                        </div>
                        <form id="signupform">

                            <div class="flex flex-col flex-grow mb-3 mx-4 max-w-md">
                                <div x-data="{ files: null }" id="FileUpload"
                                    class="block w-full py-2 px-3 relative bg-white appearance-none border-2 border-gray-300 border-solid rounded-md hover:shadow-outline-gray">
                                    <input type="file" multiple
                                        class="absolute inset-0 z-50 m-0 p-0 w-full h-full outline-none opacity-0"
                                        x-on:change="files = $event.target.files; console.log($event.target.files);"
                                        x-on:dragover="$el.classList.add('active')" x-on:dragleave="$el.classList.remove('active')" x-on:drop="$el.classList.remove('active')">
                                    <template x-if="files !== null">
                                        <div class="flex flex-col space-y-1">
                                            <template x-for="(_,index) in Array.from({ length: files.length })">
                                                <div class="flex flex-row items-center space-x-2">
                                                    <template
                                                        x-if="files[index].type.includes('audio/')"><i class="far fa-file-audio fa-fw"></i></template>
                                                    <template
                                                        x-if="files[index].type.includes('application/')"><i class="far fa-file-alt fa-fw"></i></template>
                                                    <template
                                                        x-if="files[index].type.includes('image/')"><i class="far fa-file-image fa-fw"></i></template>
                                                    <template
                                                        x-if="files[index].type.includes('video/')"><i class="far fa-file-video fa-fw"></i></template>
                                                    <span class="font-medium text-gray-900" x-text="files[index].name">Uploading</span>
                                                    <span class="text-xs self-end text-gray-500" x-text="filesize(files[index].size)">...</span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="files === null">
                                        <div class="flex flex-col space-y-2 items-center justify-center">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-currentColor"></i>
                                            <p class="text-gray-700">Drag your files here or click in this area.</p>
                                            <a href="javascript:void(0)"
                                                class="flex items-center mx-auto py-2 px-4 text-white text-center font-medium border border-transparent rounded-md outline-none bg-red-700">Select
                                                a file</a>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
                            <script src="https://cdn.filesizejs.com/filesize.min.js"></script>
                            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" rel="stylesheet" />

                            <div class="flex -mx-3">
                                <div class="w-full px-3 mb-5">
                                    <button type="submit" class="block w-full max-w-xs mx-auto bg-indigo-500 hover:bg-indigo-700 focus:bg-indigo-700 text-white rounded-lg px-3 py-2 font-semibold">Send</button>
                                    <button type="reset" class="mt-2 block w-full max-w-xs mx-auto bg-red-500 hover:bg-red-700 focus:bg-red-700 text-white rounded-lg px-3 py-2 font-semibold">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>