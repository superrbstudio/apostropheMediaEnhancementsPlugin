<?php $typeLabel = aMediaTools::getBestTypeLabel() ?>

<div class="a-ui a-media-library-upload">
  <?php // include thumbnail template ?>
  <?php include_partial('aEnhancedMedia/jsTemplates') ?>
  <form class="a-form a-media-library-upload-form" enctype="multipart/form-data" method="POST" action="/admin/aEnhancedMedia/html5Upload">

      <?php /*<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo ini_get('upload_max_filesize') ?>" /> */ ?>
      <div id="div-uploader" class="a-file-uploader a-file-uploader-dragbox">
        <div class="a-file-uploader-input-wrapper">
          <div class="a-btn a-submit big icon a-add a-file-upload-btn">
            <span class="icon"></span> Upload
            <input id="input-uploader" class="a-file-uploader a-file-uploader-input" type="file" name="aFile[]" multiple />
          </div>
          or drag <?php echo strtolower($typeLabel) ?> here
        </div>
        <div class="a-file-uploader-embed clearfix">
          <a href="#" id="a-media-embed-link" class="a-btn a-code alt a-media-embed-link lite"><?php echo a_('Paste an embed code') ?></a>
        </div>
      </div>

      <ul id="a-file-upload-list" class="a-file-upload-list"></ul>

      <div class="a-form-controls ">
        <hr class="a-hr" />
        <div class="a-form-submit">
          <input class="a-btn big a-submit" type="submit" value="I'm done!" />
        </div>
      </div>

  </form>

<?php $options = array(url_for('aEnhancedMedia/getAllTags'), url_for('aEnhancedMedia/getPopularTags')); ?>
<?php a_js_call('apostrophe.setTypeaheadUrl(?)', url_for(a_url('taggableComplete', 'complete'))) ?>
<?php a_js_call('apostrophe.setAllTagsUrl(?)', url_for('aEnhancedMedia/getAllTags')) ?>
<?php a_js_call('apostrophe.setPopularTagsUrl(?)', url_for('aEnhancedMedia/getPopularTags')) ?>
<?php a_js_call('apostrophe.setAllCategoriesUrl(?)', url_for('aEnhancedMedia/getAllCategories')) ?>
<?php a_js_call('$.when(apostrophe.getAllTags()).then($.when(apostrophe.getPopularTags().then($.when(apostrophe.getAllCategories().then(apostrophe.fileUploader())))));') ?>
</div>