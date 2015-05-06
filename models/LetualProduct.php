<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "letual_product".
 *
 * @property integer $id
 * @property string $article
 * @property string $link
 * @property string $group
 * @property string $category
 * @property string $sub_category
 * @property string $brand
 * @property string $title
 * @property string $description
 * @property string $image_link
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property LetualPrice[] $letualPrices
 */
class LetualProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'letual_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article'], 'required'],
            [['link', 'title', 'image_link'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['article'], 'string', 'max' => 100],
            [['group', 'category', 'sub_category', 'brand', 'description'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article' => 'Article',
            'link' => 'Link',
            'group' => 'Group',
            'category' => 'Category',
            'sub_category' => 'Sub Category',
            'brand' => 'Brand',
            'title' => 'Title',
            'description' => 'Description',
            'image_link' => 'Image Link',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLetualPrices()
    {
        return $this->hasMany(LetualPrice::className(), ['article' => 'article']);
    }
}
