<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
 * @property string $old_price
 * @property string $new_price
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
            [['group', 'category', 'sub_category', 'brand', 'description', 'old_price', 'new_price'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article' => 'Артикул',
            'link' => 'Link',
            'group' => 'Группа',
            'category' => 'Категория',
            'sub_category' => 'Подкатегория',
            'brand' => 'Бренд',
            'title' => 'Наименование',
            'description' => 'Description',
            'old_price' => 'Цена',
            'new_price' => 'Цена со скидкой',
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

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    static public function dropDownSubCategory()
    {
        $sql = 'Select distinct sub_category from letual_product';
        $subCategory = self::findBySql($sql)->all();
        $dropDown = [];

        foreach ($subCategory as $category) {
            $dropDown[$category->sub_category] = $category->sub_category;
        }
        return $dropDown;
    }

    static public function dropDownCategory()
    {
        $sql = 'Select distinct category from letual_product';
        $subCategory = self::findBySql($sql)->all();
        $dropDown = [];

        foreach ($subCategory as $category) {
            $dropDown[$category->category] = $category->category;
        }
        return $dropDown;
    }

    static public function dropDownBrand()
    {
        $sql = 'Select distinct brand from letual_product';
        $brands = self::findBySql($sql)->all();
        $dropDown = [];

        foreach ($brands as $brand) {
            $dropDown[$brand->brand] = $brand->brand;
        }
        return $dropDown;
    }
}
