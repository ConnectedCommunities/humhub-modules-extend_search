<?php

use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\user\models\User;

return [
    'id' => 'extend_search',
    'class' => 'humhub\modules\extend_search\Module',
    'namespace' => 'humhub\modules\extend_search',
    'events' => array(
    	array('class' => AdminMenu::className(), 'event' => AdminMenu::EVENT_INIT, 'callback' => array('humhub\modules\extend_search\Events', 'onAdminMenuInit')),

    	// Override the User model and what gets indexed
        array('class' => User::className(), 'event' => User::EVENT_AFTER_VALIDATE, 'callback' => array('humhub\modules\extend_search\Events', 'onUserAfterSave')),

    ),
];
?>