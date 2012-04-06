<?php

class PluginaEnhancedMediaEditForm extends aMediaItemForm
{
    public function getUseFields()
    {
        $useFields = array(
            'title',
            'description',
            'credit',
            'categories_list',
            'tags',
            'view_is_secure'
        );

        if ($this->isAdmin())
        {
            $useFields[] = 'categories_list_add';
        }

        return $useFields;
    }

    public function configure()
    {
        parent::configure();
        $this->disableCSRFProtection();
        $this->useFields($this->getUseFields());
    }
}
