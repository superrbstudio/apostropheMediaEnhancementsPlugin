<?php
  // Compatible with sf_escaping_strategy: true
  $allTags = isset($allTags) ? $sf_data->getRaw('allTags') : null;
  $current = isset($current) ? $sf_data->getRaw('current') : null;
  $params = isset($params) ? $sf_data->getRaw('params') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : null;
  $search = isset($search) ? $sf_data->getRaw('search') : null;
  $searchForm = isset($searchForm) ? $sf_data->getRaw('searchForm') : null;
  $selectedCategory = isset($selectedCategory) ? $sf_data->getRaw('selectedCategory') : null;
  $selectedTag = isset($selectedTag) ? $sf_data->getRaw('selectedTag') : null;
  $selected = 'a-selected'; // Class names for selected filters
?>
<?php use_helper('a') ?>
<?php // Media is now an engine, so there's a page ?>
<?php $page = aTools::getCurrentPage() ?>

<?php // Entire media browser goes into what would otherwise be the regular apostrophe subnav ?>
<?php slot('a-subnav') ?>

<?php // For backwards compatibility reasons it is best to implement these as before and after partials ?>
<?php // rather than a wrapper partial. If we use a wrapper that passes on each variable individually to an inner partial, ?>
<?php // it will break as new variables are added. If we had used a single $params array as the only variable ?>
<?php // in the first place, we could have avoided this, but we didn't, so let's be backwards compatible with all ?>
<?php // of the existing overrides of _browser in our sites and those of others. ?>

<?php include_partial('aMedia/browserBefore') ?>
<div class="a-ui a-subnav-wrapper media clearfix">
  <div class="a-subnav-inner">
    <div class="a-subnav-section clearfix search">
      <?php include_component('aEnhancedMedia', 'mediaSearch') ?>
    </div>
    <?php if ((!aMediaTools::getType()) || (substr(aMediaTools::getType(), 0, 1) === '_')): ?>
      <hr class="a-hr" />
      <div class='a-subnav-section types'>
        <h4><?php echo a_('Browse by') ?></h4>
        <div class="a-filter-options type clearfix">
          <?php $type = isset($type) ? $type : '' ?>
          <?php $typesInfo = aMediaTools::getOption('types') ?>
          <?php foreach ($typesInfo as $typeName => $typeInfo): ?>
            <?php // If a metatype such as _downloadable or _embeddable is in force show only types that support it ?>
            <?php $metatype = aMediaTools::getMetatype() ?>
            <?php if ($metatype): ?>
              <?php if (!a_get_option($typeInfo, substr($metatype, 1), false)): ?>
                <?php continue ?>
              <?php endif ?>
            <?php endif ?>
            <div class="a-filter-option">
              <?php $selected_type = ($typeName == $type) ? $selected : 'a-deselected' ?>
              <?php echo link_to(a_($typeInfo['label']), url_for(aUrl::addParams($current, array('type' => ($typeName == $type) ? '' : $typeName))), array('class' => $selected_type)) ?>
            </div>
          <?php endforeach ?>
        </div>
      </div>
    <?php endif ?>

    <?php $categoriesInfo = $page->getCategoriesInfo('aMediaItem') ?>
    <?php $categoriesInfo = $categoriesInfo['counts'] ?>
    <?php // If an engine page is locked down to one category, don't show a category browser. ?>
    <?php // Also don't bother if all categories are empty ?>
    <?php if (count($categoriesInfo) > 1): ?>
      <hr class="a-hr" />
      <div class="a-subnav-section categories">
        <h4><?php echo a_('Categories') ?></h4>
        <div class="a-filter-options blog clearfix">
          <?php $n=1; foreach ($categoriesInfo as $categoryInfo): ?>
            <div class="a-filter-option<?php echo ($n == count($categoriesInfo) ? ' last':'') ?>">
              <?php $selected_category = (isset($selectedCategory) && ($categoryInfo['name'] == $selectedCategory->name)) ? $selected : 'a-deselected' ?>
              <?php echo link_to(a_($categoryInfo['name']), url_for(aUrl::addParams($current, array("category" => (isset($selectedCategory) ? false : $categoryInfo['slug'])))), array('class' => $selected_category)) ?>
            </div>
          <?php $n++; endforeach ?>
        </div>
      </div>
    <?php endif ?>


    <?php if (count($allTags)): ?>
    <hr class="a-hr" />
    <div class='a-subnav-section section tags'>
      <h4 class="a-tag-sidebar-title popular"><?php echo __('Popular Tags', null, 'apostrophe') ?></h4>
      <ul class="a-ui a-tag-sidebar-list popular">
        <?php $n=1; foreach ($popularTags as $tag => $count): ?>
          <li <?php echo ($n == count($popularTags) ? 'class="last"':'') ?>>
            <?php echo link_to($tag.'<span class="a-tag-count">'.$count.'</span>', url_for(aUrl::addParams($current, array("tag" => $tag)))) ?>
          </li>
        <?php $n++; endforeach ?>
      </ul>

      <h4 class="a-tag-sidebar-title all-tags"><?php echo __('All Tags', null, 'apostrophe') ?></h4>
      <ul class="a-ui a-tag-sidebar-list all-tags">
        <?php $n=1; foreach ($allTags as $tag => $count): ?>
          <li <?php echo ($n == count($allTags) ? 'class="last"':'') ?>>
            <?php echo link_to($tag.'<span class="a-tag-count">'.$count.'</span>', url_for(aUrl::addParams($current, array("tag" => $tag)))) ?>
          </li>
        <?php $n++; endforeach ?>
      </ul>
    </div>
    <?php endif ?>
  </div>
</div>
<?php include_partial('aMedia/browserAfter') ?>

<?php a_js_call('apostrophe.allTagsToggle(?)', array('selector' => '.a-tag-sidebar-title.all-tags')) ?>
<?php a_js_call('apostrophe.selfLabel(?)', array('selector' => '#a-search-media-field', 'title' => a_('Search Media'), 'focus' => false)) ?>
<?php if (isset($search)): ?>
<?php a_js_call('apostrophe.searchCancel(?)', array('search' => $search, )) ?>
<?php endif ?>

<?php end_slot() //a-subnav ?>

