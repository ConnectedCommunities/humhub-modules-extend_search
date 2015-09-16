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



}