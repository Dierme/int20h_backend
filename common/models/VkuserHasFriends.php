<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vkuser_has_friends".
 *
 * @property int $id
 * @property int $vk_user_id
 * @property int $vk_friend_id
 *
 * @property VkProfile $vkFriend
 * @property VkProfile $vkUser
 */
class VkuserHasFriends extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vkuser_has_friends';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vk_user_id', 'vk_friend_id'], 'required'],
            [['vk_user_id', 'vk_friend_id'], 'integer'],
            [['vk_friend_id'], 'exist', 'skipOnError' => true, 'targetClass' => VkProfile::className(), 'targetAttribute' => ['vk_friend_id' => 'id']],
            [['vk_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => VkProfile::className(), 'targetAttribute' => ['vk_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vk_user_id' => 'Vk User ID',
            'vk_friend_id' => 'Vk Friend ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkFriend()
    {
        return $this->hasOne(VkProfile::className(), ['id' => 'vk_friend_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkUser()
    {
        return $this->hasOne(VkProfile::className(), ['id' => 'vk_user_id']);
    }
}
