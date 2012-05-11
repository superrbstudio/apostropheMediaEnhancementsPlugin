<?php if (aMediaTools::userHasUploadPrivilege() && ($uploadAllowed || $embedAllowed)): ?>
  <?php if ($uploadAllowed): ?>
    <?php include_partial('aEnhancedMedia/uploadWidget'); ?>
  <?php endif ?>
<?php if ($embedAllowed): ?>
    <?php include_partial('aEnhancedMedia/embed') ?>
<?php endif ?>
  <?php // TODO: Refactor this ?>
  <?php if ($sf_params->get('add') || $sf_user->getFlash('aMedia.postMaxSizeExceeded')): ?>
    <?php a_js_call("$('#a-media-add').show()") // This is a validation error pass  ?>
  <?php endif ?>
  <?php a_js_call("
      $('#a-media-add-button').click(function() {
        $('#a-media-add').show();
        return false;
      });
      $('#a-media-add .a-cancel').click(function() {
        $('#a-media-add').hide();
        return false;
      })"
  ) ?>

<?php endif ?>