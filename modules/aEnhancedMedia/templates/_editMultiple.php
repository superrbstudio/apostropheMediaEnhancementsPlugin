<?php
  $items = isset($items) ? $sf_data->getRaw('items') : array();
?>

<?php use_helper('a') ?>
<?php use_javascript("/apostrophePlugin/js/aCrop.js") ?>

<div class="a-ui a-media-select clearfix">
  <h3><?php echo $label ?></h3>

  <div class="a-ui">

    <div id="a-media-selection-wrapper" class="a-media-selection-wrapper">

      <div class="a-media-selection-help">
          <h4><?php echo __('Select the images you would like to edit', null, 'apostrophe') ?></h4>
      </div>

      <ul id="a-media-selection-list" style="min-height:<?php echo ($thumbHeight = aMediaTools::getSelectedThumbnailHeight()) ? $thumbHeight + 10 : 0 ?>px;">
        <?php // Always include this, it brings in some of the relevant JS too ?>
        <?php include_partial("aEnhancedMedia/editMultipleList", array("items" => $items)) ?>
      </ul>
    </div>
  </div>

  <ul class="a-ui a-controls">
    <li><?php echo a_button(a_('Save Selection'), url_for("aMedia/selected"), array('save','big','a-select-save','a-show-busy'), 'a-save-media-selection') ?></li>
    <li><?php echo a_button(a_('Cancel'), a_url('aMedia', 'selectCancel'), array('icon','a-cancel','big','alt','a-select-cancel')) ?></li>
  </ul>

</div>
