<?php
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;
?>
<?php use_helper('a') ?>
<?php slot('body_class') ?>a-media a-media-upload<?php end_slot() ?>

<div class="a-media-library">

<?php slot('a-page-header') ?>
  <?php include_partial('aEnhancedMedia/mediaHeader', array('uploadAllowed' => $uploadAllowed, 'embedAllowed' => $embedAllowed, 'pager' => $pager )) ?>
<?php end_slot() ?>

<div class="a-media-toolbar">
    <?php include_partial('aMedia/uploadMultipleWrapper', array('form' => $form)) ?>
</div>

<?php include_component('aEnhancedMedia', 'browser') ?>

</div>
