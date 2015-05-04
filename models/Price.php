<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "price".
 *
 * @property integer $id
 * @property string $link
 * @property string $brand
 * @property string $article
 * @property string $title
 * @property string $description
 * @property string $new_price
 * @property string $old_price
 */
class Price extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string'],
            [['link', 'brand', 'article', 'description', 'new_price', 'old_price'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Link',
            'brand' => 'Brand',
            'article' => 'Article',
            'title' => 'Title',
            'description' => 'Description',
            'new_price' => 'New Price',
            'old_price' => 'Old Price',
        ];
    }
}
