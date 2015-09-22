<?php

/**
 * @package humhub.modules_core.admin.forms
 * @since 0.5
 */
class ExtendSearchSettingsForm extends CFormModel {

    /** 
     * JSON of Models and Attributes 
     * that should be included in the search
     */
    public $extendSearchJSON = [];

    /** 
     * Default JSON of Models and Attributes
     * that should be included in the search
     */
    public $default_extendSearchJSON = "{\n\t\"Space\": [], \n\t\"User\": [\"title\", \"tags\", \"username\"] \n}";

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('extendSearchJSON', 'safe'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'extendSearchJSON' => 'JSON of Models and Attributes that should be included in the search',
        );
    }

}