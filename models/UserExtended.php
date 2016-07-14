<?php

namespace humhub\modules\extend_search\models;

use humhub\modules\user\models\User;

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
