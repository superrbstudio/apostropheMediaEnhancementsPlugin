<?php use_helper('a') ?>

<div class="a-media-search clearfix">
  <form action="<?php echo url_for(aUrl::addParams($current, array("search" => false))) ?>" method="get">
    <div class="a-form-row"> <?php // div is for page validation ?>
      <label for="a-search-media-field" style="display:none;">Search</label><?php // label for accessibility ?>
      <?php // Second parameter as escaping method is hopelessly broken when escaping is turned off, ?>
      <?php // we're stuck relying on the double escape guard in htmlspecialchars ?>
      <input type="search" name="search" placeholder="Search Media" value="<?php echo htmlspecialchars($sf_params->get('search')) ?>" class="a-html5-search-field" id="a-search-media-field"/>
      <?php if (isset($search)): ?>
        <?php echo link_to(__('<span class="icon"></span>Clear Search', null, 'apostrophe'), aUrl::addParams($current, array('search' => '')), array('class' => 'a-ui a-btn icon alt no-bg a-close no-label a-clear-search', 'id' => 'a-media-search-remove', 'title' => __('Clear Search', null, 'apostrophe'), )) ?>
      <?php else: ?>
        <input type="image" src="<?php echo image_path('/apostrophePlugin/images/a-special-blank.gif') ?>" class="submit a-search-submit" value="Search Pages" alt="Search" title="Search"/>
      <?php endif ?>
    </div>
  </form>
</div>