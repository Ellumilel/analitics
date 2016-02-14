<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "elize_product".
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
 * @property string $new_price
 * @property string $old_price
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property ElizePrice[] $elizePrices
 */
class ElizeProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elize_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article'], 'required'],
            [['link', 'title', 'image_link'], 'string'],
            [
                ['showcases_new', 'showcases_exclusive', 'showcases_limit', 'showcases_sale', 'showcases_best'],
                'integer',
            ],
            [['new_price', 'old_price'], 'number'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['article'], 'string', 'max' => 100],
            [['group', 'category', 'sub_category', 'brand', 'description'], 'string', 'max' => 500],
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
            'new_price' => 'New Price',
            'old_price' => 'Old Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElizePrices()
    {
        return $this->hasMany(ElizePrice::className(), ['article' => 'article']);
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListSubCategory($condition)
    {
        $result = $this::find()->distinct()
            ->select('sub_category')
            ->where($condition);
        if (!empty($condition['created_at'])) {
            $result->where('DATE_FORMAT(created_at,  "%Y-%m-%d") = "'.$condition['created_at'].'"');
        }
        return $result->orderBy('sub_category')->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListCategory($condition)
    {
        $result = $this::find()->distinct()
            ->select('category')
            ->where($condition);
        if (!empty($condition['created_at'])) {
            $result->where('DATE_FORMAT(created_at,  "%Y-%m-%d") = "'.$condition['created_at'].'"');
        }
        return $result->orderBy('category')->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListGroup($condition)
    {
        $result = $this::find()->distinct()
            ->select('group')
            ->where($condition);
        if (!empty($condition['created_at'])) {
            $result->where('DATE_FORMAT(created_at,  "%Y-%m-%d") = "'.$condition['created_at'].'"');
        }
        return $result->orderBy('group')->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getListBrand($condition)
    {
        $result = $this::find()->distinct()
            ->select('brand')
            ->where($condition);

        if (!empty($condition['created_at'])) {
            $result->where('DATE_FORMAT(created_at,  "%Y-%m-%d") = "'.$condition['created_at'].'"');
        }

        return $result->orderBy('brand')->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function dropDownGroup($condition)
    {
        $result = $this::find()->distinct()
            ->select('group')
            ->where($condition);

        return $result->orderBy('group')->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function dropDownSubCategory($condition)
    {
        $result = $this::find()->distinct()
            ->select('sub_category')
            ->where($condition);

        return $result->orderBy('sub_category')->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function dropDownCategory($condition)
    {
        $result = $this::find()->distinct()
            ->select('category')
            ->where($condition);

        return $result->orderBy('category')->all();
    }

    /**
     * @param $condition
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function dropDownBrand($condition)
    {
        $result = $this::find()->distinct()
            ->select('brand')
            ->where($condition);

        return $result->orderBy('brand')->all();
    }

    /**
     * @return array
     */
    public static function getStatistic()
    {
        $rows = (new \yii\db\Query())
            ->select(['count(id) as counts', 'DATE_FORMAT(created_at,  "%Y-%m-%d") as dates'])
            ->from('elize_product')
            ->groupBy(['DATE_FORMAT(created_at,  "%Y-%m-%d")'])
            ->orderBy('created_at')
            ->all();

        return $rows;
    }
}
