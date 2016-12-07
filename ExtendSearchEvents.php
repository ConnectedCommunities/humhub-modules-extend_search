<?php

/**
 * Connected Communities Initiative
 * Copyright (C) 2016 Queensland University of Technology
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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