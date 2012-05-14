<?php if (aMediaTools::userHasUploadPrivilege() && ($uploadAllowed || $embedAllowed)): ?>
  <?php if ($uploadAllowed): ?>
    <?php include_partial('aEnhancedMedia/uploadWidget'); ?>
  <?php endif ?>
  <?php if ($embedAllowed): ?>
      <?php include_partial('aEnhancedMedia/embed') ?>
  <?php endif ?>
<?php endif ?>