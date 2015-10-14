<?php

class AdminController extends Controller{

	public $subLayout = "application.modules_core.admin.views._layout";


    /**
     * Configuration Action for Super Admins
     */
    public function actionIndex() {

        $form = new ExtendSearchSettingsForm;
                        
        if (isset($_POST['ExtendSearchSettingsForm'])) {

            $form->attributes = $_POST['ExtendSearchSettingsForm'];

            if ($form->validate()) {

                // Validate JSON by running it through json_decode
                if(empty(json_decode($form->extendSearchJSON))) {
                    $form->addError('extendSearchJSON', 'JSON is invalid or empty.');
                } else {
                    $form->extendSearchJSON = HSetting::SetText('extendSearchJSON', $form->extendSearchJSON);
                    Yii::app()->user->setFlash('data-saved', Yii::t('AdminModule.controllers_SettingController', 'Saved'));
                    $this->redirect(Yii::app()->createUrl('//extend_search/admin/index'));
                }
                
                
            }

        } else {
            $form->extendSearchJSON = HSetting::GetText('extendSearchJSON');
        }

        $this->render('index', array(
            'model' => $form
        ));

    }


    /** 
     * Reindex Lucene records from the 
     * provided Model
     */
    public function actionReindex() 
    {
        
        $model_str = Yii::app()->request->getParam('model');
        $model = new $model_str;

        foreach($model::model()->findAll() as $record) {
            HSearch::getInstance()->deleteModel($record);
            $record->save();
        }

    }


    /**
     * Reindex all User models
     */
    public function actionReindexUsers() {

        // Get all users

        // For each user
            // $user->save();

        // Done. The afterSave listener will take over and 
        //      do what needs to be done.

        // example
        $user = User::model()->findByPk(1);
        $user->save();

    }
}
