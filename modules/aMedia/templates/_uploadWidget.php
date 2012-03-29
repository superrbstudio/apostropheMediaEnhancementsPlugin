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
    .a-file-upload-thumbnail.error
    {
        opacity: 0;
    }
    .a-file-upload-preview
    {
        height: 100px;
        position: relative;
        top: 0;
        left: 0;
        z-index: 199;
    }

</style>

<h1>Drag and Drop Upload!  -- REMIX</h1>


<?php // include thumbnail template ?>
<?php include_partial('aMedia/jsTemplates') ?>
<form enctype="multipart/form-data" method="POST" action="/admin/media/html5Upload">
    <?php /*<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo ini_get('upload_max_filesize') ?>" /> */ ?>
    <input id="input-uploader" class="a-file-uploader" type="file" name="aFile[]" multiple />
    <input type="submit" />
</form>

<div id="div-uploader" class="a-file-uploader">
</div>

<?php $options = array(url_for('aMedia/getAllTags'), url_for('aMedia/getPopularTags')); ?>
<?php a_js_call('apostrophe.setTypeaheadUrl(?)', url_for(a_url('taggableComplete', 'complete'))) ?>
<?php a_js_call('apostrophe.setAllTagsUrl(?)', url_for('aMedia/getAllTags')) ?>
<?php a_js_call('apostrophe.setPopularTagsUrl(?)', url_for('aMedia/getPopularTags')) ?>
<?php a_js_call('apostrophe.setAllCategoriesUrl(?)', url_for('aMedia/getAllCategories')) ?>
<?php a_js_call('$.when(apostrophe.getAllTags()).then($.when(apostrophe.getPopularTags().then($.when(apostrophe.getAllCategories().then(apostrophe.fileUploader())))));') ?>

