<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vkgroups".
 *
 * @property int $id
 * @property int $gid
 * @property string $group_name
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
            [['gid', 'group_name'], 'required'],
            [['gid'], 'integer'],
            [['group_name'], 'string', 'max' => 255],
            [['gid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gid' => 'Gid',
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

    public static function findByGid($gid)
    {
        return self::find()->where(['gid'=>$gid])->one();
    }
}
