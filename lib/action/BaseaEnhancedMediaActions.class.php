<?php
/**
 * See lib/base in this plugin for the actual code. You can extend that
 * class in your own application level override of this file
 * @package    Apostrophe
 * @author     P'unk Avenue <apostrophe@punkave.com>
 */
class BaseaEnhancedMediaActions extends BaseaMediaActions
{
  /**
   *
   * This action serves as a target for our advanced media uploader
   * to send files and get responses. If a user navigates directly
   * to this page, they should be forwarded to the media index. I am
   * leaving the forwarding aspect out for now as I need this action
   * for testing purposes.
   *
   * @param sfWebRequest $request
   */
  public function executeHtml5Upload(sfWebRequest $request)
  {
    $this->forward404Unless(aMediaTools::userHasUploadPrivilege());

    if ($request->getMethod() == "POST")
    {

      $files = aEnhancedMediaTools::getInstance()->handleHtml5Upload($request);

      $mediaTable = Doctrine::getTable('aMediaItem');

      $results = array();
      foreach($files as $file)
      {
        $status = $mediaTable->addFileAsMediaItem($file);

        if ($status['status'] == 'ok')
        {
          $item = $status['item'];
          $results = $this->arrayMediaItemResponse($item, 'success');

          // remove the temp upload
          unlink($file);
        }
        else if ($status['status'] == 'failed')
        {
          $results = $this->arrayMediaItemResponse(null, 'failed');
        }
      }

      if ($request->isXmlHttpRequest())
      {
        return $this->renderText(json_encode($results));
      }

      return $this->forward('aMedia', 'index');
    }
  }

  /**
   *
   * @param sfWebRequest $request
   */
  public function executeHtml5Edit(sfWebRequest $request)
  {
      $this->forward404Unless(aEnhancedMediaTools::userHasUploadPrivilege());
      $this->forward404Unless($request->hasParameter('slug'));
      $item = aEnhancedMediaTools::getItem($this);
      $this->forward404Unless($item->userHasPrivilege('edit'));

      $status = 'success';

      if ($request->hasParameter('media_item'))
      {
          $params = $request->getParameter('media_item');

          if (($item = aEnhancedMediaTools::getInstance()->editItem($item, $params)) !== false)
          {
              $status = 'success';
          }
          else
          {
              $status = 'failed';
          }
      }

      if ($request->isXmlHttpRequest())
      {
          return $this->renderText(json_encode($this->arrayMediaItemResponse($item, $status)));
      }

      return $this->forward('aMedia', 'index');
    }

    public function executeGetAllTags(sfWebRequest $request)
    {
        if ($request->isXmlHttpRequest())
        {
            return $this->renderText(json_encode(PluginTagTable::getPopulars(null, array('sort_by_popularity' => true), false, 10)));
        }

        return $this->forward('aMedia', 'index');
    }

    public function executeGetPopularTags(sfWebRequest $request)
    {
        if ($request->isXmlHttpRequest())
        {
            return $this->renderText(json_encode(PluginTagTable::getAllTagNameWithCount()));
        }

        return $this->forward('aMedia', 'index');
    }

    public function executeGetAllCategories(sfWebRequest $request)
    {
        $categories = Doctrine::getTable('aCategory')->createQuery('c')->fetchArray();
        if ($request->isXmlHttpRequest())
        {
            return $this->renderText(json_encode($categories));
        }

        return $this->forward('aMedia', 'index');
    }

    /**
     * Returns a json string that reasonably represents a
     * MediaItem so that we may use it with some frontend
     * MVC tools.
     *
     * @param MediaItem $item
     * @return string
     */
    public function arrayMediaItemResponse(aMediaItem $item = null, $status = 'success')
    {
      return aEnhancedMediaTools::getInstance()->toBackboneArray($item, array('status' => $status));
    }

  /**
   * Supported for backwards compatibility. See also
   * aMediaSelect::select()
   * @param sfWebRequest $request
   * @return mixed
   */
  public function executeSelect(sfWebRequest $request)
  {
    $this->hasPermissionsForSelect();

    $after = $request->getParameter('after');
    $page  = $request->getParameter('page');
    $editMultiple = !!$request->getParameter('editMultiple');
    // Prevent possible header insertion tricks
    $after = preg_replace("/\s+/", " ", $after);
    $multiple = !!$request->getParameter('multiple');
    if ($multiple)
    {
      $selection = preg_split("/\s*,\s*/", $request->getParameter('aMediaIds'));
    } else
    {
      $selection = array($request->getParameter('aMediaId') + 0);
    }

    $options = array();
    $optional = array('type', 'aspect-width', 'aspect-height',
      'minimum-width', 'minimum-height', 'width', 'height', 'label');

    foreach ($optional as $option)
    {
      if ($request->hasParameter($option))
      {
        $options[$option] = $request->getParameter($option);
      }
    }
    aMediaTools::setSelecting($after, $multiple, $selection, $options);

    if ($editMultiple) {
      aMediaTools::setAttribute('editMultiple', true);
    }

    if ($page) {
      return $this->redirect("aMedia/index?page=".$page);
    }
    else
    {
      return $this->redirect("aMedia/index");
    }
  }

  /**
   * DOCUMENT ME
   * @param sfWebRequest $request
   */
  public function executeBatchEditRemove(sfWebRequest $request)
  {
    $this->hasPermissionsForSelect();

    $id = $request->getParameter('id');
    $item = Doctrine::getTable("aMediaItem")->find($id);
    $this->forward404Unless($item);
    $selection = aMediaTools::getSelection();
    $index = array_search($id, $selection);
    if ($index !== false)
    {
      array_splice($selection, $index, 1);
    }
    aMediaTools::setSelection($selection);
    return $this->renderTemplate();
  }

  /**
   * DOCUMENT ME
   * @param sfWebRequest $request
   * @return mixed
   */
  public function executeBatchEditAdd(sfWebRequest $request)
  {
    $this->hasPermissionsForSelect();

    $id = $request->getParameter('id') + 0;
    $item = Doctrine::getTable("aMediaItem")->find($id);
    $this->forward404Unless($item);
    $selection = aMediaTools::getSelection();
    if (!aMediaTools::isMultiple())
    {
      $selection = array($id);
    } else
    {
      $index = array_search($id, $selection);
      // One occurrence each. If this changes we'll have to rethink
      // the way reordering and deletion work (probably go by index).
      if ($index === false)
      {
        $selection[] = $id;
      }
    }
    aMediaTools::setSelection($selection);
    if (aMediaTools::getAttribute('type') == 'image')
    {
      $imageInfo = aMediaTools::getAttribute('imageInfo');
      // Make no attempt to scrub out a previous crop, which could be handy
      $imageInfo[$id]['width'] = $item->getWidth();
      $imageInfo[$id]['height'] = $item->getHeight();
      aMediaTools::setAttribute('imageInfo', $imageInfo);
      if ($item->getCroppable())
      {
        // If no previous crop info is set, we must set an intial cropping mask
        // so that the cropped media item id gets linked instead of the original
        // media item. This is a little dangerous because JavaScript computes an
        // intial crop mask on the client side.
        aMediaTools::setDefaultCropDimensions($item);
      }
    }
    if ((!aMediaTools::isMultiple()))
    {
      return $this->redirect('aMedia/selected');
    }

    return $this->renderTemplate();
  }

  public function executeBatchEdit(sfWebRequest $request)
  {
    $this->form = new aEnhancedMediaEditMultipleForm();
    $this->form->bind($request->getParameter($this->form->getName()));
    $page  = $request->getParameter('page');

    $description = $this->form->getValue('description');
    $ids = explode(',' , $this->form->getValue('item_ids'));
    $tags = $this->form->getValue('tags');
    $categories = $this->form->getValue('categories_list');

//    var_dump($this->form->getValue('tags'));
//    var_dump($this->form->getValue('item_ids'));
//    var_dump($this->form->getValue('description'));
//    var_dump($this->form->getValue('categories_list'));
//    exit;

    if (count($ids))
    {
      // start transaction

      foreach ($ids as $id)
      {
        $media = Doctrine::getTable('aMediaItem')->findOneBy('id', $id);

        // description
        if ($description)
        {
          $media->setDescription($description);
          $media->setDescription(aHtml::simplify($media->getDescription(), "<p><br><b><i><strong><em><ul><li><ol><a>"));
        }

        // tags
        if ($tags)
        {
          $newtags = str_replace('/', '-', isset($tags) ? $tags : '');
          $media->setTags($newtags);
        }

        // categories
        if ($categories)
        {
          foreach($categories as $c)
          {
            $category = Doctrine::getTable('aCategory')->findOneBy('id', $c);
            $media->Categories[] = $category;
          }
        }

        // save
        $media->save();
      }

      // commit transaction
    }
    aMediaTools::clearSelecting();
    return $this->redirect("aMedia/index");
  }
}

