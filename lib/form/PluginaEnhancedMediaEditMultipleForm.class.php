<?php

class PluginaEnhancedMediaEditMultipleForm extends aMediaItemForm
{
  /**
   * DOCUMENT ME
   */
  public function setup()
  {
    parent::setup();

    $this->setWidget('tags', new sfWidgetFormInput(array("default" => implode(", ", $this->getObject()->getTags())), array("class" => "tag-input", "autocomplete" => "off")));
    $this->setValidator('tags', new sfValidatorPass());
    $this->setWidget('view_is_secure', new sfWidgetFormSelect(array('choices' => array('1' => 'Hidden', '' => 'Public'))));
    $this->setValidator('view_is_secure', new sfValidatorChoice(array('required' => false, 'choices' => array('1', ''))));
    $this->setWidget('description', new aWidgetFormRichTextarea(array('tool' => 'Media', 'height' => 182 ))); // FCK doesn't like to be smaller than 182px in Chrome
    
    // widget to hold the media ids
    $this->setWidget('item_ids', new sfWidgetFormInputHidden());
    $this->setValidator('item_ids', new sfValidatorPass());
    
    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('apostrophe');
  }

  public function getUseFields()
  {
      $useFields = array(
          // 'title',
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
