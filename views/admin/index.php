<?php

use yii\widgets\ActiveForm;
use humhub\widgets\DataSaved;
use yii\helpers\Html;

?>
<div class="panel panel-default">
    <div class="panel-heading"><strong>Extend</strong> Search</div>
    <div class="panel-body">

        <?php $form = ActiveForm::begin(array(
            'id'=>'extend-search-settings-index-form',
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // See class documentation of CActiveForm for details on this,
            // you need to use the performAjaxValidation()-method described there.
            'enableAjaxValidation'=>false,
        )); ?>

            <div class="form-group">
                <p>Customise the extended search functionality.</p>
            </div>
            <hr>
            <div class="form-group">
                <!-- show flash message after saving -->
                <?php echo DataSaved::widget(); ?>
                <?php // echo $form->errorSummary($model); ?>
            </div>

            <div class="form-group">
                <?php 
                if(empty($model->extendSearchJSON)) {
                    $model->extendSearchJSON = $model->default_extendSearchJSON;
                }
                ?>
                <?php echo $form->field($model, 'extendSearchJSON')->textarea(array('class' => 'form-control', 'rows' => '8')); ?>
                <small>Use a comma seperated list to specify multiple Models to <u>include</u> (e.g. User,Space) </small>
            </div>

            <hr>
            
            <?php echo Html::submitButton('Save', array('class' => 'btn btn-primary')); ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>
