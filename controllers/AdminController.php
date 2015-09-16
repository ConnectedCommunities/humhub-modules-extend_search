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

                $form->extendSearchIncludeModels = HSetting::SetText('extendSearchIncludeModels', $form->extendSearchIncludeModels);

                // set flash message
                Yii::app()->user->setFlash('data-saved', Yii::t('AdminModule.controllers_SettingController', 'Saved'));

                $this->redirect(Yii::app()->createUrl('//extend_search/admin/index'));
            }

        } else {
            $form->extendSearchIncludeModels = HSetting::GetText('extendSearchIncludeModels');
        }

        $this->render('index', array(
            'model' => $form
        ));

    }

}
