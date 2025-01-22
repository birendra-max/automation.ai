<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css" crossorigin>
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5-premium-features/44.1.0/ckeditor5-premium-features.css" crossorigin>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap');

  @media print {
    body {
      margin: 0 !important;
    }
  }

  .main-container {
    font-family: 'Lato';
    width: fit-content;
    margin-left: auto;
    margin-right: auto;
  }

  .ck-content {
    font-family: 'Lato';
    line-height: 1.6;
    word-break: break-word;
  }

  .editor-container_classic-editor .editor-container__editor {
    min-width: 795px;
    max-width: 795px;
  }
</style>

<div class="main-container">
  <div class="editor-container editor-container_classic-editor" id="editor-container">
    <div class="editor-container__editor">
      <!-- <div id="editor"></div> -->
      <label for="message-${index}" class="block text-teal-600 font-medium">Mail Prompt</label>
      <textarea id="editor">
      ${item.prompt || ''}</textarea>
    </div>
  </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js" crossorigin></script>
<script src="https://cdn.ckeditor.com/ckeditor5-premium-features/44.1.0/ckeditor5-premium-features.umd.js"
  crossorigin></script>
<script src="https://cdn.ckbox.io/ckbox/2.6.1/ckbox.js" crossorigin></script>
<script src="public/js/main.js"></script>