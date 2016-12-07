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
<li>
    <form action="" class="dropdown-controls">
        <input type="text" id="search-menu-search"
               class="form-control"
               autocomplete="off"
               placeholder="<?php echo Yii::t('base', 'Search...'); ?>">

        <div class="search-reset" id="search-search-reset"><i
                class="fa fa-times-circle"></i></div>
    </form>
</li>

<script type="text/javascript">
    /**
     * Open search menu
     */
    $('#search-menu-nav').click(function () {

        // use setIntervall to setting the focus
        var searchFocus = setInterval(setFocus, 10);

        function setFocus() {

            // set focus
            $('#search-menu-search').focus();
            // stop interval
            clearInterval(searchFocus);
        }

    })
</script>