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

class UserExtended extends User {


    /** 
     * Removes attributes from the model
     * 
     * @param array $attributes
     * @return void
     */
    // public function excludeAttributes($attributes = array()) 
    // {
    //     // $this->unsetAttributes($attributes);
    // }

    /**
     * Returns an array of informations used by search subsystem.
     * Function is defined in interface ISearchable
     *
     * @return Array
     */
    public function getSearchAttributes()
    {
        $attributes = array(
            // Assignment
            'belongsToType' => 'User',
            'belongsToId' => $this->id,
            'belongsToGuid' => $this->guid,
            'model' => 'User',
            'pk' => $this->id,
            'title' => $this->getDisplayName(),
            'url' => $this->getUrl(),
            'tags' => $this->tags,
            // 'email' => $this->email,
            'groupId' => $this->group_id,
            'status' => $this->status,
            'username' => $this->username,
        );

        $profile = $this->getProfile();

        if (!$profile->isNewRecord) {
            foreach ($profile->getProfileFields() as $profileField) {
                $attributes['profile_' . $profileField->internal_name] = $profileField->getUserValue($this, true);
            }
        }

        return $attributes;
    }


}
