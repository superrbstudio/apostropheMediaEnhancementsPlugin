<?php // Compatible with sf_escaping_strategy: true
  $items = isset($items) ? $sf_data->getRaw('items') : null;
?>

<?php use_helper('a') ?>

<?php $n=1; foreach ($items as $item): ?>
  <?php $id = $item->getId() ?>
  <?php $embeddable = $item->getEmbeddable(); ?>
  <?php $domId = "a-media-selection-list-item-$id" ?>
  <li id="<?php echo $domId ?>" class="a-media-selection-list-item">
    <ul class="a-ui a-controls a-over a-media-selection-controls">
      <li>
        <?php echo a_button(a_('Edit'), aUrl::addParams(url_for('a_media_edit'), array("slug" => $item->getSlug(), 'withPreview' => true, )), array('icon', 'a-edit', 'lite', 'no-label', 'alt')) ?>
      </li>
      <li>
        <?php echo a_js_button(a_('Delete'), array('icon','a-delete', 'lite', 'no-label', 'alt')) ?>
      </li>
    </ul>
    <?php //var_dump($item); exit; ?>

    <?php if ($item->getType() == 'image'): ?>
    <div class="a-thumbnail-container" style="background-image: url('<?php echo url_for($item->getCropThumbnailUrl()) ?>'); overflow: hidden;">
      <img src="<?php echo url_for($item->getCropThumbnailUrl()) ?>" class="a-thumbnail" style="visibility:hidden;" />
    </div>
    <?php else: ?>
    <div class="a-thumbnail-container">
      <?php if ($embeddable): ?>
          <span class="a-media-type embed" ><b><?php echo a_('VIDEO') ?></b></span>
      <?php else: ?>
        <?php // Files (Word Docs, Powerpoints, Spreadsheets) and embedded items with no preview available ?>
        <?php // We can't render this format on this server but we need a placeholder thumbnail ?>
        <span class="a-media-type <?php echo $item->getType() ?> <?php echo $item->getFormat() ?>" ><b><?php echo strlen($item->getFormat()) ? $item->getFormat() : a_($item->getType()) ?></b></span>
      </div>
      <?php endif ?>
      <span class="a-media-title"><?php echo $item->getTitle() ?></span>
    <?php endif ?>
  </li>
  <?php a_js_call('apostrophe.setObjectId(?, ?)', $domId, $id) ?>
<?php $n++; endforeach ?>

<?php a_js_call('apostrophe.batchEditMedia()') ?>

<?php a_js_call('apostrophe.mediaEnableSelect(?)', array(
  'setCropUrl' => a_url('aMedia', 'crop'),
  'removeUrl' => a_url('aEnhancedMedia', 'batchEditRemove'),
  'updateMultiplePreviewUrl' => a_url('aMedia', 'updateMultiplePreview'),
  'multipleAddUrl' => a_url('aEnhancedMedia', 'batchEditAdd'),
  'ids' => aMediaTools::getSelection(),
  'aspectRatio' => aMediaTools::getAspectRatio(),
  'minimumSize' => array(aMediaTools::getAttribute('minimum-width'), aMediaTools::getAttribute('minimum-height')),
  'maximumSize' => array(aMediaTools::getAttribute('maximum-width'), aMediaTools::getAttribute('maximum-height')),
  // width height cropLeft cropTop cropWidth cropHeight hashed by image id
  'imageInfo' => aMediaTools::getAttribute('imageInfo'))) ?>
