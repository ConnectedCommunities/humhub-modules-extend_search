<?php

class ExtendSearchEvents {

    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param type $event
     */
    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem(array(
            'label' => Yii::t('ExtendSearchModule.base', 'Extend Search'),
            'url' => Yii::app()->createUrl('//extend_search/admin/index'),
            'group' => 'manage',
            'icon' => '<i class="fa fa-search"></i>',
            'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'extend_search' && Yii::app()->controller->id == 'admin'),
            'sortOrder' => 550,
        ));

    }


    /**
     * A user has been created
     * @param type $event
     */
    public static function onUserAfterSave($event) 
    {

        Yii::import('application.modules.extend_search.models.*');

        $new = new UserExtended;
        $new->attributes = $event->sender->getAttributes();


        // Remove old index
        HSearch::getInstance()->deleteModel($event->sender);


        // Reindex the user model
        HSearch::getInstance()->addModel($new);

    }

}