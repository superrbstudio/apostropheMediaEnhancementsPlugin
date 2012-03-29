<?php
/**
 * @package    apostrophePlugin
 * @subpackage    toolkit
 * @author     P'unk Avenue <apostrophe@punkave.com>
 */
class BaseaEnhancedMediaTools
{
    /**
     * Get an instance to work with.
     *
     * @return \aEnhancedMediaTools
     */
    static public function getInstance()
    {
        return new aEnhancedMediaTools();
    }

    /**
     * This function will handle moving files from the
     * apostrophe html5 uploader.  It must be able to
     * handle a POST with files or a straight XHR upload.
     * The post may include multiple files.
     *
     * Return an array of file paths that have been moved.
     *
     * @param sfWebRequest $request
     * @return array
     */
    public function handleHtml5Upload(sfWebRequest $request)
    {
        $result = array();

        if ($request->getGetParameter('aFile')) // XHR upload
        {
            $result[] = $this->handleXhrUpload($request);
        }
        else if ($request->getMethod() == 'POST') // POST upload
        {
            $files = $request->getFiles();

            if (!empty($files['aFile']))
            {
                if ($this->isSingleFile($files['aFile']))
                {
                    $result[] = $this->handlePostUpload($files['aFile']);
                }
                else
                {
                    foreach ($files['aFile'] as $file)
                    {
                        $result[] = $this->handlePostUpload($file);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Determines if the passed array is an array of uploaded
     * files or a single uploaded file.
     *
     * @param array $ar
     * @return boolean
     */
    public function isSingleFile($ar)
    {
        return (count($ar) == 5) && (!empty($ar['name']));
    }

    /**
     * Copies an uploaded file from standard input
     * to a tmp upload directory.
     *
     * @param sfWebRequest $request
     * @return boolean|string
     */
    protected function handleXhrUpload(sfWebRequest $request)
    {
        $uploadsDirectory = aFiles::getUploadFolder(array('batch_upload'));

        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != ((int)$_SERVER['CONTENT_LENGTH']))
        {
            return false;
        }

        $path = $uploadsDirectory . '/' . aTools::slugify($request->getGetParameter('aFile'));
        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return $path;
    }

    /**
     *
     * Copies a POSTed uploaded file to a temporary upload
     * directory.
     *
     * @param array $file
     * @return string|boolean
     */
    protected function handlePostUpload($file)
    {
        $uploadsDirectory = aFiles::getUploadFolder(array('batch_upload'));

        $path = $uploadsDirectory . '/' . aTools::slugify($file['name']);

        if (move_uploaded_file($file['tmp_name'], $path))
        {
            return $path;
        }
        
        return false;
    }

    /**
     * This takes an aMediaItem as input and returns an array suitable
     * for usage with our backbone model
     *
     *
     * @param MediaItem $item
     * @return array
     */
    public function toBackboneArray(aMediaItem $item, $ar = array())
    {
        $ar['id'] = $item->getId();
        $ar['viewUrl'] = url_for('a_media_image_show', array('slug' => $item->getSlug()));

        // this is a bad way to construct a URL. Update the routing to make this better.
        $ar['editUrl'] = url_for("aMedia/html5Edit?" . http_build_query(array("slug" => $item->getSlug())));
        $ar['deleteUrl'] = url_for("aMedia/delete?" . http_build_query(array("slug" => $item->getSlug())));
        $ar['tags'] = implode(',', $item->getTags());

        if ($item->type == 'image')
        {
            $ar['srcUrl'] = $item->getCropThumbnailUrl();
        } else {
            $ar['srcUrl'] = '';
        }

        return array_merge($ar, $item->toArray());
    }
}
