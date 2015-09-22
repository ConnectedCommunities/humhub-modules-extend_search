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

        // Yii::import('application.modules.extend_search.models.*');
        // Remove old index
        // HSearch::getInstance()->deleteModel($event->sender);

        /*
         This doesn't work.. ffs.
         The logical process of: 
            Remove old index, add new index
         Doesn't seem to be working... Screw you, slut.
         It seems to be indexing the new "UserExtended" model
         correctly as a "User" model without an email. 

         It just seems like deleting it has a slight delay so
         both indexes are deleted or something shit like that

         Maybe there's a way to specifically drop a single field from the 
         index..

         Or maybe I need to go back to the drawing board
         http://stackoverflow.com/questions/24360269/zend-lucene-search-only-in-specified-fields-with-a-filter
        */

        // $new = new UserExtended;
        // $new->attributes = $event->sender->getAttributes();
        // Exclude attributes from model 
        // $new->excludeAttributes(explode(",", HSetting::GetText('extendSearchExcludeUserAttributes')));
        // Reindex the user model
        // HSearch::getInstance()->addModel($new);
        // exit();

    }

}