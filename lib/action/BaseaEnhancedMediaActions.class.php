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

            var_dump($files);
            die;
        }
    }
}

