<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vk_profile".
 *
 * @property int $id
 * @property int $user_id
 * @property int $vk_uid
 * @property string $name
 * @property string $surname
 *
 * @property User $user
 * @property VkuserHasVkgroups[] $vkuserHasVkgroups
 */
class VkProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vk_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'surname'], 'required'],
            [['user_id', 'vk_uid'], 'integer'],
            [['name', 'surname'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'vk_uid' => 'User VK ID',
            'name' => 'Name',
            'surname' => 'Surname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVkuserHasVkgroups()
    {
        return $this->hasMany(VkuserHasVkgroups::className(), ['vk_profile_id' => 'id']);
    }

    public static function findByUID($vkUid)
    {
        return self::find()->where(['vk_uid'=>$vkUid])->one();
    }

    public static function isVkUser($userID)
    {
        return self::find()->where(['user_id' => $userID])->exists();
    }
}
