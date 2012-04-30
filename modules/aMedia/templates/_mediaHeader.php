<?php use_helper('a') ?>
<?php $page = aTools::getCurrentPage() ?>
<?php $search = strlen(aMediaTools::getSearchParameter('search')) ?>
<?php if ($page->admin): ?>

  <div class="a-ui a-admin-header">
    <ul class="a-ui a-controls a-admin-controls">
      <li><h3 class="a-admin-title"><?php echo link_to('<span class="icon"></span>'.__('Media Library', null, 'apostrophe'), '@a_media_index', array('class' => 'a-btn big lite'))?></h3></li>
    </ul>
    <?php include_component('aMedia', 'mediaSearch') ?>
  </div>

  <?php a_js_call('apostrophe.clickOnce(?)', '#a-save-media-selection,.a-media-select-video,.a-select-cancel') ?>

  <?php if (aMediaTools::isSelecting()): ?>
    <?php a_js_call('apostrophe.mediaClearSelectingOnNavAway(?)', a_url('aMedia', 'clearSelecting')) ?>
  <?php endif ?>

<?php endif ?>