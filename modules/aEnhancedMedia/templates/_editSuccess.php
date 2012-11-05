<?php
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $item = isset($item) ? $sf_data->getRaw('item') : null;
  $pager = isset($pager) ? $sf_data->getRaw('pager') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : array();
  $allTags = isset($allTags) ? $sf_data->getRaw('allTags') : array();
?>
<?php use_helper('a') ?>

<?php $body_class = 'a-media a-media-edit'?>
<?php $body_class .= ($page->admin) ? ' aMediaAdmin':'' ?>
<?php slot('body_class', $body_class) ?>

<?php slot('a-page-header') ?>
  <?php include_partial('aEnhancedMedia/mediaHeader', array('uploadAllowed' => $uploadAllowed, 'embedAllowed' => $embedAllowed, 'pager' => $pager )) ?>
<?php end_slot() ?>

<div class="a-media-library">

  <div class="a-media-toolbar">
    <h3><?php echo __('You are editing: %title%', array('%title%' => $item->getTitle()), 'apostrophe') ?></h3>
  </div>

  <?php if ($postMaxSizeExceeded): ?>
  <h3><?php echo __('File too large. Limit is %POSTMAXSIZE%', array('%POSTMAXSIZE%' => ini_get('post_max_size')), 'apostrophe') ?></h3>
  <?php endif ?>

  <div class="a-media-items">
    <?php include_partial('aMedia/edit', array('item' => $item, 'form' => $form, 'popularTags' => $popularTags, 'allTags' => $allTags, 'formAction' => url_for(aUrl::addParams("aMedia/edit", array("slug" => $item->getSlug()))))) ?>
  </div>

  <?php include_component('aEnhancedMedia', 'browser') ?>

</div>
