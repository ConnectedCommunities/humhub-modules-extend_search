<?php

Yii::app()->moduleManager->register(array(
    'id' => 'extend_search',
    'class' => 'application.modules.extend_search.ExtendSearchModule',
    'import' => array(
        'application.modules.extend_search.*',
        'application.modules.extend_search.models.*',
        'application.modules.extend_search.forms.*',
    ),
    'events' => array(
    	array('class' => 'AdminMenuWidget', 'event' => 'onInit', 'callback' => array('ExtendSearchEvents', 'onAdminMenuInit')),

    ),
));
?>