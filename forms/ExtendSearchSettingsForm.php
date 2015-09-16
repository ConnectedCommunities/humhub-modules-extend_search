<?php

/**
 * @package humhub.modules_core.admin.forms
 * @since 0.5
 */
class ExtendSearchSettingsForm extends CFormModel {

    /** 
     * Models which should be included in the search
     */
    public $extendSearchIncludeModels = [];

    /** 
     * Models which should be excluded from the search
     */
    // public $extendSearchExcludeModels = [];

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('extendSearchIncludeModels', 'safe'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'extendSearchIncludeModels' => 'Models to Include',
        );
    }

}