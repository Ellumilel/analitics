<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "rivegauche_product".
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
 * @property integer $showcases_compliment
 * @property integer $showcases_offer
 * @property integer $showcases_exclusive
 * @property integer $showcases_bestsellers
 * @property integer $showcases_expertiza
 * @property string $gold_price
 * @property string $blue_price
 * @property string $price
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @property RivegauchePrice[] $rivegauchePrices
 */
class RivegaucheProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rivegauche_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article'], 'required'],
            [['link', 'title', 'image_link'], 'string'],
            [['gold_price', 'blue_price', 'price'], 'number'],
            [['showcases_new', 'showcases_compliment', 'showcases_offer', 'showcases_exclusive', 'showcases_bestsellers', 'showcases_expertiza'], 'integer'],
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
            'sub_category' => 'Под категория',
            'brand' => 'Бренд',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'image_link' => 'Картинка',
            'showcases_new' => 'Новинка',
            'showcases_compliment' => 'Showcases Compliment',
            'showcases_offer' => 'Showcases Offer',
            'showcases_exclusive' => 'Showcases Exclusive',
            'showcases_bestsellers' => 'Showcases Bestsellers',
            'showcases_expertiza' => 'Showcases Expertiza',
            'gold_price' => 'Цена по золотой карте',
            'blue_price' => 'Цена по стандартной карте',
            'price' => 'Полная цена',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRivegauchePrices()
    {
        return $this->hasMany(RivegauchePrice::className(), ['article' => 'article']);
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
     * @param $offset
     * @param $limit
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getEntity($offset, $limit)
    {
        return $this::find()->offset($offset)->limit($limit)->all();
    }

    static function getStatistic()
    {
        $rows = (new \yii\db\Query())
            ->select(['count(id) as counts', 'DATE_FORMAT(created_at,  "%Y-%m-%d") as dates'])
            ->from('rivegauche_product')
            ->groupBy(['DATE_FORMAT(created_at,  "%Y-%m-%d")'])
            ->orderBy('created_at')
            ->all();

        return $rows;
    }
}
