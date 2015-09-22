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

                $form->extendSearchJSON = HSetting::SetText('extendSearchJSON', $form->extendSearchJSON);

                // set flash message
                Yii::app()->user->setFlash('data-saved', Yii::t('AdminModule.controllers_SettingController', 'Saved'));

                $this->redirect(Yii::app()->createUrl('//extend_search/admin/index'));
            }

        } else {
            $form->extendSearchJSON = HSetting::GetText('extendSearchJSON');
        }

        $this->render('index', array(
            'model' => $form
        ));

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
