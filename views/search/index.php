<?php
/**
 * Connected Communities Initiative
 * Copyright (C) 2016 Queensland University of Technology
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?>

<?php
/**
 * This View shows the quick search results
 *
 * @property Boolean $moreResults if there are more results
 * @property String $keyword is the query search query
 * @property Array $results of search result content (html)
 * @property String $spaceGuid if we search also inside of a space, this is the space guid
 * @property Int $pageSize shows max. records per page
 * @property CPagination $pages is the pagination instance
 *
 * @package humhub.controllers
 * @since 0.5
 */
?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('base', 'Search'); ?></div>


    <div class="panel-body">
        <?php echo CHtml::beginForm(null, 'GET'); ?>
        <?php //echo Yii::t('base', 'Keyword:') ?>
        <?php echo CHtml::textField('keyword', $keyword, array('placeholder' => 'Keyword', 'class' => 'form-control')); ?>
        <?php echo CHtml::hiddenField('sguid', $spaceGuid); ?><br>
        <?php echo CHtml::submitButton(Yii::t('base', 'Search'), array('class' => 'btn btn-primary')); ?>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>


<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('base', 'Results'); ?></div>

    <div class="panel-body">

        <?php if (count($results) > 0): ?>
            <ul class="media-list">
                <?php foreach ($results as $result): ?>
                    <?php echo $result; ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>

            <p><?php echo Yii::t('base', 'Sorry, nothing found!'); ?></p>

        <?php endif; ?>
    </div>

</div>
<div class="pagination-container">
    <?php
    $this->widget('CLinkPager', array(
        'currentPage' => $pages->getCurrentPage(),
        'itemCount' => $hitCount,
        'pageSize' => $pageSize,
        'maxButtonCount' => 5,
        'nextPageLabel' => '<i class="fa fa-step-forward"></i>',
        'prevPageLabel' => '<i class="fa fa-step-backward"></i>',
        'firstPageLabel' => '<i class="fa fa-fast-backward"></i>',
        'lastPageLabel' => '<i class="fa fa-fast-forward"></i>',
        'header' => '',
        'htmlOptions' => array('class' => 'pagination'),
    ));
    ?>
</div>
