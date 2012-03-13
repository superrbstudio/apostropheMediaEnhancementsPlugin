<?php
/**
 * See lib/base in this plugin for the actual code. You can extend that
 * class in your own application level override of this file
 * @package    Apostrophe
 * @author     P'unk Avenue <apostrophe@punkave.com>
 */
class BaseaEnhancedMediaActions extends BaseaMediaActions
{
    public function executeHtml5Upload(sfWebRequest $request)
    {
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
                    $results = array(
                        'status' => 'success',
                        'id' => $item->getId(),
                        'item' => $item->toArray(),
                        'viewUrl' => url_for('a_media_image_show', array('slug' => $item->getSlug())),
                        'editUrl' => url_for('a_media_edit', array('slug' => $item->getSlug())),
                        'deleteUrl' => url_for('aMedia/delete', array('slug' => $item->getSlug()))
                    );
                    
                    // remove the temp upload
                    unlink($file);
                }
                else if ($status['status'] == 'failed')
                {
                    $results = array('status' => 'failed');
                }
            }

            if ($request->isXmlHttpRequest())
            {
                return $this->renderText(json_encode($results));
            }

            return $this->forward('aMedia', 'index');
        }
    }
}

