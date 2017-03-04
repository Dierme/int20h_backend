<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vkuser_has_vkgroups".
 *
 * @property int $id
 * @property int $vk_profile_id
 * @property int $vk_group_id
 *
 * @property Vkgroups $vkGroup
 * @property VkProfile $vkProfile
 */
class VkuserHasVkgroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vkuser_has_vkgroups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vk_profile_id', 'vk_group_id'], 'required'],
            [['vk_profile_id', 'vk_group_id'], 'integer'],
            [['vk_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vkgroups::className(), 'targetAttribute' => ['vk_group_id' => 'id']],
            [['vk_profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => VkProfile::className(), 'targetAttribute' => ['vk_profile_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vk_profile_id' => 'Vk Profile ID',
            'vk_group_id' => 'Vk Group ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkGroup()
    {
        return $this->hasOne(Vkgroups::className(), ['id' => 'vk_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkProfile()
    {
        return $this->hasOne(VkProfile::className(), ['id' => 'vk_profile_id']);
    }
}
