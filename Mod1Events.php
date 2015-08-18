<?php

class Mod1Events{
    public static function onTopMenuInit($event){
        $event->sender->addItem(array(
            'label' => 'My First Module',
            'url' => Yii::app()->createUrl('/mod1/main/index', array()),
            'icon' => '<i class="fa fa-sun-o"></i>',
            'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'mod1'),
        ));
    }
}