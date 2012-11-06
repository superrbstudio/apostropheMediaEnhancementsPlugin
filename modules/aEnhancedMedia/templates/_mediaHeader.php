<?php use_helper('a') ?>
<?php $page = aTools::getCurrentPage() ?>
<?php $search = strlen(aMediaTools::getSearchParameter('search')) ?>
<?php $batchEdit = sfConfig::get('app_aMedia_batch_edit') ? sfConfig::get('app_aMedia_batch_edit') : false; ?>
<?php $pageNum = $pager ? $pager->getPage() : 1; ?>

<?php if ($page->admin): ?>

  <div class="a-ui a-admin-header">
    <ul class="a-ui a-controls a-admin-controls">
      <li><h3 class="a-admin-title"><?php echo link_to('<span class="icon"></span>'.__('Media Library', null, 'apostrophe'), '@a_media_index', array('class' => 'a-btn big lite'))?></h3></li>
    </ul>

    <ul class="a-ui a-controls a-admin-controls a-media-service-controls a-align-right">
      <?php if ($page->admin): ?>
          <li>
            <?php if ($batchEdit): ?>
                <?php echo link_to('<span class="icon"></span> Batch Edit',
                  'aEnhancedMedia/select',
                  array(
                   'query_string' =>
                     http_build_query(
                       array_merge(
                         array(
                         "page" => $pageNum,
                         "multiple" => true,
                         "editMultiple" => true,
                         "label" => 'Select images you would like to edit',
                         "after" => a_url('aMedia', 'index') . "?" . 
                                    http_build_query(
                                      array(
                                        "page" => $pageNum, 
                                        ))))),
                   'class' => 'a-ui a-btn icon alt lite a-edit a-inject-actual-url a-js-choose-button')) ?>
              <?php endif ?>
          </li>
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
