<script>
    document.title = 'Call Schudle';
</script>

<?php
include 'inclu/hd.php';
?>

<section class="max-w-8xl mx-auto bg-white border border-gray-300 shadow-lg rounded-lg p-8 mt-2">
    <!-- File Upload Section -->
    <div class="w-full">
        <h3 class="text-lg font-semibold mb-4">Upload Your Numbers Excel File</h3>
        <form class="p-6 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:shadow-md transition-all" method="post" enctype="multipart/form-data">
            <input type="file" id="fileInput" name="phonefile" class="hidden" accept=".xls,.xlsx" required />
            <label for="fileInput" class="flex flex-col items-center cursor-pointer">
                <i class="fas fa-cloud-upload-alt text-indigo-600 text-5xl"></i>
                <p class="text-gray-600 mt-2">Drag & Drop files here or <span class="text-indigo-600 font-semibold">Browse</span></p>
                <div id="error-message" class="text-red-500 hidden">
                    Please upload a valid Excel file (.xls, .xlsx).
                </div>

                <div id="success-message" class="text-green-500 font-bold hidden">

                </div>
            </label>
            <div id="fileList" class="mt-4"></div>
        </form>
    </div>
</section>


<script>
    document.getElementById('fileInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const filename = file.name;
        const filetype = filename.split('.');
        if (filetype[1] == 'xlsx' || filetype[1] == 'xls') {
            document.getElementById('success-message').classList.remove('hidden');
            document.getElementById('success-message').innerText = `File Name is : ${filename}`;

            let frmData=new FormData();
            frmData.append('fileInput',file)
            console.log(frmData)

            $.ajax({
                url:'read_excl_date.php',
                type:"POST",
                data:frmData,
                contentType:false,
                processData:false,
                success:function(response){
                    console.log(response);
                },
                error:function(xhr,status,error){
                    console.log(error);
                }

            })

        } else {
            document.getElementById('fileInput').value='';
            document.getElementById('error-message').classList.remove('hidden');
            console.log(document.getElementById('fileInput').value);
        }
    })
</script>




<?php
include 'inclu/footer.php';
?>