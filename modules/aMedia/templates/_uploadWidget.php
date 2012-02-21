<style>
    .a-file-uploader
    {
        border: thin solid #FFF;
    }
    
    div.a-file-uploader
    {
        height: 100px;
    }

    .a-file-uploader.drag-over
    {
        background: #ffffbe;
        border: thin dashed #000;
    }

    .a-file-upload-thumbnail
    {
        height: 100px;
        background: #FFF;
        border: thin black #000;
        display: inline-block;
        margin: 1px;
        opacity: 0.5;
    }
    .a-file-upload-thumbnail.done
    {
        opacity: 1;
    }
    .a-file-upload-image-preview
    {
        height: 100px;
        position: relative;
        top: 0;
        left: 0;
        z-index: 199;
    }

</style>

<h1>Drag and Drop Upload!  -- REMIX</h1>

<form enctype="multipart/form-data" method="POST" action="/admin/media/html5Upload">
    <?php /*<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo ini_get('upload_max_filesize') ?>" /> */ ?>
    <input id="input-uploader" class="a-file-uploader" type="file" name="aFile[]" multiple />
    <input type="submit" />
</form>

<div id="div-uploader" class="a-file-uploader">
</div>

<div class="a-file-upload-list">
</div>

<?php a_js_call('apostrophe.fileUploader()') ?>

