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
 * @property integer $showcases_new
 * @property integer $showcases_exclusive
 * @property integer $showcases_bestsellers
 * @property integer $showcases_limit
 * @property string $showcases_promotext
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
            [['link', 'title', 'image_link', 'showcases_promotext'], 'string'],
            [['showcases_new', 'showcases_exclusive', 'showcases_bestsellers', 'showcases_limit'], 'integer'],
            [['old_price', 'new_price'], 'number'],
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
            'article' => 'Артикул',
            'link' => 'Ссылка',
            'group' => 'Группа',
            'category' => 'Категория',
            'sub_category' => 'Подкатегория',
            'brand' => 'Бренд',
            'title' => 'Наименование',
            'description' => 'Описание',
            'showcases_new' => 'Showcases New',
            'showcases_exclusive' => 'Showcases Exclusive',
            'showcases_bestsellers' => 'Showcases Bestsellers',
            'showcases_limit' => 'Showcases Limit',
            'showcases_promotext' => 'showcases Promotext',
            'old_price' => 'Цена',
            'new_price' => 'Цена со скидкой',
            'image_link' => 'Картинка',
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
     * @param $offset
     * @param $limit
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getEntity($offset, $limit)
    {
        return $this::find()->offset($offset)->limit($limit)->all();
    }

    public static function getStatistic()
    {
        $rows = (new \yii\db\Query())
            ->select(['count(id) as counts', 'DATE_FORMAT(created_at,  "%Y-%m-%d") as dates'])
            ->from('letual_product')
            ->groupBy(['DATE_FORMAT(created_at,  "%Y-%m-%d")'])
            ->orderBy('created_at')
            ->all();

        return $rows;
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
     * @return int
     *
     * @throws \yii\db\Exception
     */
    public function setDeleted()
    {
        $db = Yii::$app->getDb();
        $sql = 'UPDATE letual_product, (SELECT MAX(DATE_FORMAT(updated_at,  "%Y-%m-%d")) as date FROM letual_product) a
                SET deleted_at = NOW()
                WHERE updated_at < a.date and DATE_FORMAT(deleted_at,  "%Y-%m-%d") = "0000-00-00"';
        return $db->createCommand($sql)->execute();
    }
}
