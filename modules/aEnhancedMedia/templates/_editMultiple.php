<?php
  $items = isset($items) ? $sf_data->getRaw('items') : array();
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : array();
  $allTags = isset($allTags) ? $sf_data->getRaw('allTags') : array();
  $form = $batchForm;
?>

<?php use_helper('a') ?>
<?php use_javascript("/apostrophePlugin/js/aCrop.js") ?>

<div class="a-ui a-media-edit-multiple-wrapper clearfix">
  <h3><?php echo $label ?></h3>

    <div id="a-media-selection-wrapper" class="a-media-selection-wrapper a-media-edit-multiple clearfix">
      <?php //var_dump($items) ?>
      <div class="a-media-selection-help">
          <h4><?php echo __('Add tags and categories to multiple images at once', null, 'apostrophe') ?></h4>
      </div>

      <ul id="a-media-selection-list" class="a-media-selection-list" style="min-height:<?php echo ($thumbHeight = aMediaTools::getSelectedThumbnailHeight()) ? $thumbHeight + 10 : 0 ?>px;">
        <?php // Always include this, it brings in some of the relevant JS too ?>
        <?php include_partial("aEnhancedMedia/editMultipleList", array("items" => $items)) ?>
      </ul>
      <?php echo form_tag('aEnhancedMedia/batchEdit', array('name' => 'media_batch_edit_form', 'class' => 'a-form a-media-edit-multiple-form', 'id' => 'a-media-edit-multiple-form')) ?>
        <div class="a-form-row description">
          <?php echo $form['description']->renderError() ?>
          <?php echo $form['description']->render() ?>
        </div>
        <div class="a-form-row tags">
          <?php echo $form['tags']->renderLabel() ?>
          <div class="a-form-field">
            <?php echo $form['tags']->render(array('id' => 'a-media-batch-tags-input', )) ?>
            <?php $options = array('popular-tags' => $popularTags, 'tags-label' => ' ', 'commit-selector' => '#a-save-media-selection', 'typeahead-url' => a_url('taggableComplete', 'complete')) ?>
            <?php if (sfConfig::get('app_a_all_tags', true)): ?>
              <?php $options['all-tags'] = $allTags ?>
            <?php endif ?>
            <?php a_js_call('pkInlineTaggableWidget(?, ?)', '#a-media-batch-tags-input', $options) ?>
          </div>
          <?php echo $form['tags']->renderError() ?>
        </div>

        <div class="a-form-row categories">
          <?php echo $form['categories_list']->renderLabel() ?>
          <div class="a-form-field">
            <?php echo $form['categories_list']->render() ?>
          </div>
          <?php echo $form['categories_list']->renderError() ?>
          <?php $options = array('choose-one' => a_('Choose Categories')) ?>
          <?php if (sfContext::getInstance()->getUser()->hasCredential(aMediaTools::getOption('admin_credential'))): ?>
            <?php $options['add'] = a_('+ New Category') ?>
          <?php endif ?>
          <?php a_js_call('aMultipleSelect(?,?)', '#a-media-edit-multiple-form > .categories', $options) ?>
        </div>

        <div class="a-form-row hidden">
          <div class="a-form-field">
            <?php echo $form['item_ids']->render() ?>
          </div>
        </div>

        <div class="a-form-row buttons">
          <?php echo a_submit_button('Save Images') ?>
        </div>
      </form>
  </div>
  <?php echo a_button(a_('Cancel'), url_for("aMedia/selectCancel"), array(), 'a-save-media-selection') ?>
</div>
