<?php
/**
 * See lib/base in this plugin for the actual code. You can extend that
 * class in your own application level override of this file
 * @package    Apostrophe
 * @author     P'unk Avenue <apostrophe@punkave.com>
 */
class BaseaEnhancedMediaComponents extends BaseaMediaComponents
{

  public function executeBrowser($request)
  {
    parent::executeBrowser($request);

    //added links to the browser that check for these
    $this->embedAllowed = aMediaTools::getEmbedAllowed();
    $this->uploadAllowed = aMediaTools::getUploadAllowed();
  }
  public function executeMediaSearch($request)
  {
    $this->current = "aMedia/index";
    $params = array();

    $search = aMediaTools::getSearchParameter('search');
    if (strlen($search))
    {
      $this->search = $search;
      $params['search'] = $search;
    }
    $this->searchForm = new aMediaSearchForm();
    $this->searchForm->bind(array('search' => $request->getParameter('search')));
    $this->current .= "?" . http_build_query($params);
  }

  public function executeMediaAlerts($request)
  {
    $this->current = "aMedia/index";
    $params = array();
    $type = aMediaTools::getSearchParameter('type');
    if (strlen($type))
    {
      $this->type = $type;
      $params['type'] = $type;
    }
    $tag = aMediaTools::getSearchParameter('tag');
    if (strlen($tag))
    {
      $this->selectedTag = $tag;
      $params['tag'] = $tag;
    }
    $categorySlug = aMediaTools::getSearchParameter('category');
    if (strlen($categorySlug))
    {
      $this->selectedCategory = Doctrine::getTable('aCategory')->findOneBySlug($categorySlug);
      $params['category'] = $categorySlug;
    }
    $search = aMediaTools::getSearchParameter('search');
    if (strlen($search))
    {
      $this->search = $search;
      $params['search'] = $search;
    }
    $this->current .= "?" . http_build_query($params);
  }
}