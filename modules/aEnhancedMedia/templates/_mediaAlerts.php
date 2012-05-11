<?php
$current = isset($current) ? $sf_data->getRaw('current') : null;
$search = isset($search) ? $sf_data->getRaw('search') : null;
$type = isset($type) ? $sf_data->getRaw('type') : null;
$selectedCategory = isset($selectedCategory) ? $sf_data->getRaw('selectedCategory') : null;
$selectedTag = isset($selectedTag) ? $sf_data->getRaw('selectedTag') : null;
?>

<?php if($search): ?>
<div class="a-media-alert a-media-alert-search clearfix">
  <h4>You are searching for <strong><?php echo $search ?></strong></h4>
  <?php echo link_to('<i class="icon"></i>Clear this Search', url_for(aUrl::addParams($current, array('search' => ''))), array('class' => 'a-media-alert-clear', )) ?>
</div>
<?php endif ?>

<?php if($type): ?>
<div class="a-media-alert a-media-alert-type clearfix">
  <h4>You are filtering by the type: <strong><?php echo($type) ?></strong></h4>
  <?php echo link_to('<i class="icon"></i>Clear this Filter', url_for(aUrl::addParams($current, array('type' => ''))), array('class' => 'a-media-alert-clear', )) ?>
</div>
<?php endif ?>

<?php if($selectedCategory): ?>
<div class="a-media-alert a-media-alert-type clearfix">
  <h4>You are filtering by the category: <strong><?php echo($selectedCategory) ?></strong></h4>
  <?php echo link_to('<i class="icon"></i>Clear this Filter', url_for(aUrl::addParams($current, array('category' => ''))), array('class' => 'a-media-alert-clear', )) ?>
</div>
<?php endif ?>

<?php if($selectedTag): ?>
<div class="a-media-alert a-media-alert-type clearfix">
  <h4>You are filtering by the tag: <strong><?php echo($selectedTag) ?></strong></h4>
  <?php echo link_to('<i class="icon"></i>Clear this Filter', url_for(aUrl::addParams($current, array('tag' => ''))), array('class' => 'a-media-alert-clear', )) ?>
</div>
<?php endif ?>

<?php if($search || $type || $selectedCategory || $selectedTag): ?>
<hr class="a-hr">
<?php endif ?>