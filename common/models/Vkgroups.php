<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vkgroups".
 *
 * @property int $id
 * @property int $group_name
 *
 * @property VkuserHasVkgroups[] $vkuserHasVkgroups
 */
class Vkgroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vkgroups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_name'], 'required'],
            [['group_name'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_name' => 'Group Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkuserHasVkgroups()
    {
        return $this->hasMany(VkuserHasVkgroups::className(), ['vk_group_id' => 'id']);
    }
}
