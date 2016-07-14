<?php

namespace humhub\modules\extend_search\widgets;

use humhub\components\Widget;
use Yii;
/**
 * Overwrite the original SearchMenuWidget so
 * it points to our modules controller
 *
 * src: /protected/widgets/SearchMenuWidget.php
 */
class SearchMenuWidget extends Widget {

    /**
     * Displays / Run the Widgets
     */
    public function run() {
        return $this->render('searchMenu', array());
    }

}
