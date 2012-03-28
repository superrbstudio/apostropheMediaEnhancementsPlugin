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
        $this->forward404Unless(aMediaTools::userHasUploadPrivilege());
        $this->forward404Unless($request->hasParameter('slug'));
        $item = aMediaTools::getItem($this);
        $this->forward404Unless($item->userHasPrivilege('edit'));

        if ($request->hasParameter('media_item'))
        {
            $params = $request->getParameter('media_item');

            $item->title = $params['title'];
            $item->description = $params['description'];
            $item->credit = $params['credit'];
            $item->view_is_secure = ($params['is_secure'] == 1)? true : false;

            if ($params['categories'])
            {
                $categories = Doctrine::getTable('aCategory')->createQuery('c')
                        ->andWhereIn('id', $params['categories'])
                        ->execute();

                foreach($categories as $c)
                {
                    $item->Categories[] = $c;
                }
            }

            $item->save();
        }

        if ($request->isXmlHttpRequest())
        {
            return $this->renderText(json_encode($this->arrayMediaItemResponse($item, 'success')));
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
    public function arrayMediaItemResponse(aMediaItem $item = null, $status = null)
    {
        $ar = array();
        if ($status)
        {
            $ar['status'] = $status;
        }


        if ($item)
        {
            $ar['id'] = $item->getId();
            $ar['item'] = $item->toArray();
            $ar['viewUrl'] = url_for('a_media_image_show', array('slug' => $item->getSlug()));

            // this is a bad way to construct a URL. Update the routing to make this better.
            $ar['editUrl'] = url_for("aMedia/html5Edit?" . http_build_query(array("slug" => $item->getSlug())));
            $ar['deleteUrl'] = url_for("aMedia/delete?" . http_build_query(array("slug" => $item->getSlug())));
            $ar['tags'] = implode(',', $item->getTags());
        }

        return $ar;
    }
}

