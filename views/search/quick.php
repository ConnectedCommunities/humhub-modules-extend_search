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

use yii\helpers\Url;

?>

    <div class="searchResults">

        <?php if (count($results) > 0): ?>
            <?php foreach ($results as $result): ?>
                    <?php echo $result->getWallOut(); ?>
            <?php endforeach; ?>
        <?php else: ?>
            <li><div style="padding: 10px 5px; color: #999; font-weight: normal;"><em><?php echo Yii::t('base', 'Nothing found with your input.'); ?></em></div></li>
        <?php endif; ?>
    </div>