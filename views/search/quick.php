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
 *
 * @package humhub.controllers
 * @since 0.5
 */
?>
<?php if (count($results) > 0): ?>
    <?php foreach ($results as $result): ?>
        <?php echo $result; ?>
    <?php endforeach; ?>

    <?php if ($moreResults): ?>
        <li class="footer"><a id="show_more_button" href="<?php echo $this->createUrl('//search/index', array('keyword' => $keyword)); ?>"><?php echo Yii::t('base', 'Show more results') ?></a></li>
    <?php endif; ?>
<?php else: ?>
    <li><div style="padding: 10px 5px; color: #999; font-weight: normal;"><em><?php echo Yii::t('base', 'Nothing found with your input.'); ?></em></div></li>
<?php endif; ?>