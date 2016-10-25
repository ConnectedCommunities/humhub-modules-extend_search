<?php

/**
 * Connected Communities Initiative
 * Copyright (C) 2016 Queensland University of Technology
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */ 

namespace humhub\modules\extend_search\controllers;

use humhub\modules\search\engine\ZendLuceneSearch;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use Yii;
use humhub\modules\extend_search\forms\ExtendSearchSettingsForm;
use humhub\models\Setting;
use humhub\modules\space\models\Space;
use humhub\modules\search\engine\Search;
use Zend\Stdlib\ArrayObject;

/**
 * Search Controller provides search functions inside the application.
 *
 * @author Luke
 * @package humhub.controllers
 * @since 0.5
 */
class SearchController extends Controller
{

    const SCOPE_ALL = "all";
    const SCOPE_USER = "user";
    const SCOPE_SPACE = "space";
    const SCOPE_CONTENT = "content";

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'users' => array('@', (Setting::Get('allowGuestAccess', 'authentication_internal')) ? "?" : "@"),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * SearchAction
     *
     * Modes: normal for full page, quick as partial for lightbox
     */
    public function actionIndex()
    {

        // Get Parameters
        $keyword = Yii::$app->request->getQueryParam('keyword', "");
        $spaceGuid = Yii::$app->request->getQueryParam('sguid', "");
        $scope = Yii::$app->request->get('scope', "");
        $mode = Yii::$app->request->getQueryParam('mode', "normal");
        $page = (int) Yii::$app->request->getQueryParam('page', 1); // current page (pagination)
        $limitSpaceGuids = Yii::$app->request->get('limitSpaceGuids', "");

        $results = [];
        $results = Yii::$app->search->find($keyword, [
             'page' => 1,
             'pageSize' => 10,
         ]);

        return $this->renderPartial('quick', array(
            'keyword' => $keyword,
            'results' => $results->getResultInstances(),
            'spaceGuid' => $spaceGuid,
        ));
    }

    /**
     * JSON Search interface for Mentioning
     */
    public function actionMentioning()
    {

        $results = array();
        $keyword = Yii::$app->request->getParam('keyword', "");
        $keyword = Yii::$app->input->stripClean(trim($keyword));
        
        if (strlen($keyword) >= 3) {
            $hits = new ArrayObject(HSearch::getInstance()->Find($keyword . "*  AND (model:User OR model:Space)"));
            $hitCount = count($hits);

            $hits = new LimitIterator($hits->getIterator(), 0, 10);

            foreach ($hits as $hit) {

                $doc = $hit->getDocument();
                $model = $doc->getField('model')->value;
                $pk = $doc->getField('pk')->value;

                $object = $model::model()->findByPk($pk);

                if ($object !== null && $object instanceof HActiveRecordContentContainer) {
                    $result = array();
                    $result['guid'] = $object->guid;
                    if ($object instanceof Space) {
                        $result['name'] = CHtml::encode($object->name);
                        $result['type'] = 's';
                    } elseif  ($object instanceof User) {
                        $result['name'] = CHtml::encode($object->displayName);
                        $result['type'] = 'u';
                    }
                    $result['image'] = $object->getProfileImage()->getUrl();
                    $result['link'] = $object->getUrl();
                    $results[] = $result;
                }
            }
        }
        
        print Json::encode($results);
    }

    /**
     * Generates a query string based on the provided 
     * JSON of Models and Attributes
     */
    private function generateQueryStr($keyword) {
        
        // First, if there's no extendSearchJSON HSetting, add it
        $form = new ExtendSearchSettingsForm();
        if(empty(Setting::GetText('extendSearchJSON'))) {
            Setting::SetText('extendSearchJSON', $form->default_extendSearchJSON);
        }

        // Generate Query String
        $str = "{$keyword}* AND ";
        $extendSearchJSON = json_decode(Setting::GetText('extendSearchJSON'));
        $extendSearchArray = (array) $extendSearchJSON;

        $numberOfItems = count($extendSearchArray) - 1;
        $itemCount = 0;
        foreach($extendSearchJSON as $key => $extendSearchItem) {
            $str .= "(model:{$key} ";

            if(count($extendSearchItem) > 0) {
                $numberOfAttributes = count($extendSearchItem) - 1;
                $attributeCount = 0;
                $str .= "AND (";
                foreach($extendSearchItem as $attribute) {
                    $str .= "{$attribute}: {$keyword}* ";
                    if($attributeCount < $numberOfAttributes) $str .= " OR ";
                    $attributeCount++;
                }
                $str .= ")) ";
            } else {
                $str .= ") ";
            }

            if($itemCount < $numberOfItems) {
                $str .= " OR ";
            }

            $itemCount++;
        }

        return $str;

    }

}
