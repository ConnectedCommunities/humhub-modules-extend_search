<?php

/**
 * HumHub
 * Copyright © 2014 The HumHub Project
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 */

/**
 * Search Controller provides search functions inside the application.
 *
 * @author Luke
 * @package humhub.controllers
 * @since 0.5
 */
class SearchController extends Controller
{

    public $subLayout = "_layout";

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
                'users' => array('@', (HSetting::Get('allowGuestAccess', 'authentication_internal')) ? "?" : "@"),
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
        $keyword = Yii::app()->request->getParam('keyword', "");
        $spaceGuid = Yii::app()->request->getParam('sguid', "");
        $mode = Yii::app()->request->getParam('mode', "normal");
        $page = (int) Yii::app()->request->getParam('page', 1); // current page (pagination)
        // Cleanup
        $keyword = Yii::app()->input->stripClean($keyword);
        $spaceGuid = Yii::app()->input->stripClean($spaceGuid);

        if ($mode != 'quick') {
            $mode = "normal";
        }

        $limit = HSetting::Get('paginationSize');         // Show Hits
        $hitCount = 0;      // Total Hit Count
        $query = "";        // Lucene Query
        // $append = " AND (model:User OR model:Space)";  // Appends for Lucene Query
        $moreResults = false;  // Indicates if there are more hits
        $results = array();

        // Quick Search shows always 1
        if ($mode == 'quick')
            $limit = 5;

        // Load also Space if requested
        $currentSpace = null;
        if ($spaceGuid) {
            $currentSpace = Space::model()->findByAttributes(array('guid' => $spaceGuid));
        }

        /*
         * $index = new Zend_Search_Lucene_Interface_MultiSearcher();
         * $index->addIndex(Zend_Search_Lucene::open('search/index1'));
         * $index->addIndex(Zend_Search_Lucene::open('search/index2'));
         * $index->find('someSearchQuery');
         */

        // Do Search
        if ($keyword != "") {

            $query = $this->generateQueryStr($keyword);

            if ($currentSpace != null) {
                $query .= " AND (model:User OR model:Space OR (belongsToType:Space AND belongsToId:" . $currentSpace->id . "))";
            }

            $hits = new ArrayObject(HSearch::getInstance()->Find($query));
            $hitCount = count($hits);


            // Limit Hits
            $hits = new LimitIterator($hits->getIterator(), ($page - 1) * $limit, $limit);

            if ($hitCount > $limit)
                $moreResults = true;

            // Build Results Array

            foreach ($hits as $hit) {

                $doc = $hit->getDocument();
                $model = $doc->getField('model')->value;
                $pk = $doc->getField('pk')->value;

                $object = $model::model()->findByPk($pk);
                $results[] = $object->getSearchResult();
            }
        }

        // Create Pagination Class
        $pages = new CPagination($hitCount);
        $pages->setPageSize($limit);
        $_GET['keyword'] = $keyword; // Fix for post var

        if ($mode == 'quick') {
            $this->renderPartial('quick', array(
                'keyword' => $keyword,
                'results' => $results,
                'spaceGuid' => $spaceGuid,
                'moreResults' => $moreResults,
                'hitCount' => $hitCount,
            ));
        } else {
            $this->render('index', array(
                'keyword' => $keyword,
                'results' => $results,
                'spaceGuid' => $spaceGuid,
                'moreResults' => $moreResults,
                'pages' => $pages, // CPagination,
                'pageSize' => $limit,
                'hitCount' => $hitCount,
            ));
        }
    }

    /**
     * JSON Search interface for Mentioning
     */
    public function actionMentioning()
    {

        $results = array();
        $keyword = Yii::app()->request->getParam('keyword', "");
        $keyword = Yii::app()->input->stripClean(trim($keyword));
        
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
        
        print CJSON::encode($results);
    }

    /**
     * Generates a query string based on the provided 
     * JSON of Models and Attributes
     */
    private function generateQueryStr($keyword) {
        
        // First, if there's no extendSearchJSON HSetting, add it
        $form = new ExtendSearchSettingsForm;
        if(empty(HSetting::GetText('extendSearchJSON'))) {
            HSetting::SetText('extendSearchJSON', $form->default_extendSearchJSON);
        }

        // Generate Query String
        $str = "{$keyword}* AND ";
        $extendSearchJSON = json_decode(HSetting::GetText('extendSearchJSON'));
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
