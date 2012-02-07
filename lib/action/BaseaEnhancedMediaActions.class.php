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
            if (isset($_GET['aFile'])) {
                $this->file = new qqUploadedFileXhr();
            } elseif (isset($_FILES['aFile'])) {
                $this->file = new qqUploadedFileForm();
            }

            var_dump($this->file);
        }
    }
}

