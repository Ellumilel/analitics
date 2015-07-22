<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "iledebeaute_product".
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
 * @property integer $showcases_new
 * @property integer $showcases_exclusive
 * @property integer $showcases_limit
 * @property integer $showcases_sale
 * @property integer $showcases_best
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property IledebeautePrice[] $iledebeautePrices
 */
class IledebeauteProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iledebeaute_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article'], 'required'],
            [['link', 'title', 'image_link'], 'string'],
            [['showcases_new', 'showcases_exclusive', 'showcases_limit', 'showcases_sale', 'showcases_best'], 'integer'],
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
            'showcases_new' => 'Showcases New',
            'showcases_exclusive' => 'Showcases Exclusive',
            'showcases_limit' => 'Showcases Limit',
            'showcases_sale' => 'Showcases Sale',
            'showcases_best' => 'Showcases Best',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIledebeautePrices()
    {
        return $this->hasMany(IledebeautePrice::className(), ['article' => 'article']);
    }
}
