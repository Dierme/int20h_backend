<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $header
 * @property string $intro
 * @property string $text
 * @property string $image
 * @property int $featured
 * @property int $category_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Category $category
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header', 'intro'], 'required'],
            [['text'], 'string'],
            [['featured', 'category_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['header', 'intro', 'image'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'header' => 'Header',
            'intro' => 'Intro',
            'text' => 'Text',
            'image' => 'Image',
            'featured' => 'Featured',
            'category_id' => 'Category ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
