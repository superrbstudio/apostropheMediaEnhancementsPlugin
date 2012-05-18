<?php use_helper('a') ?>
<?php $page = aTools::getCurrentPage() ?>
<?php $search = strlen(aMediaTools::getSearchParameter('search')) ?>
<?php if ($page->admin): ?>

  <div class="a-ui a-admin-header">
    <ul class="a-ui a-controls a-admin-controls">
      <li><h3 class="a-admin-title"><?php echo link_to('<span class="icon"></span>'.__('Media Library', null, 'apostrophe'), '@a_media_index', array('class' => 'a-btn big lite'))?></h3></li>
    </ul>

    <ul class="a-ui a-controls a-admin-controls a-media-service-controls a-align-right">
      <?php if ($page->admin): ?>
        <?php if (aMediaTools::getOption('linked_accounts') && aMediaTools::userHasAdminPrivilege()): ?>
          <li><a href="<?php echo a_url('aMedia', 'link') ?>" class="a-btn icon alt a-users lite a-media-link-accounts"><span class="icon"></span><?php echo a_('Manage Linked Accounts') ?></a></li>
        <?php endif ?>
        <?php if (aMediaTools::userHasUploadPrivilege() && ($uploadAllowed || $embedAllowed)): ?>
          <li><a href="<?php echo a_url('aMedia', 'searchServices') ?>" class="a-btn icon alt lite a-search"><span class="icon"></span><?php echo a_('Search Services') ?></a></li>
        <?php endif ?>
      <?php endif ?>
    </ul>
  </div>

  <?php a_js_call('apostrophe.clickOnce(?)', '#a-save-media-selection,.a-media-select-video,.a-select-cancel') ?>

  <?php if (aMediaTools::isSelecting()): ?>
    <?php a_js_call('apostrophe.mediaClearSelectingOnNavAway(?)', a_url('aMedia', 'clearSelecting')) ?>
  <?php endif ?>

<?php endif ?>