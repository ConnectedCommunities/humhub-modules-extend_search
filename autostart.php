<?php

Yii::app()->moduleManager->register(array(
    'id' => 'extend_search',
    'class' => 'application.modules.extend_search.Mod1Module',
    'import' => array(
        'application.modules.extend_search.*',
    ),
    'events' => array(
    	// array('class' => 'TopMenuWidget', 'event' => 'onInit', 'callback' => array('Mod1Events', 'onTopMenuInit')),
    ),
));
?>