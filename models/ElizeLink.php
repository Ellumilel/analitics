<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "elize_link".
 *
 * @property integer $id
 * @property string $link
 * @property string $group
 * @property string $category
 * @property string $sub_category
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class ElizeLink extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elize_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link'], 'required'],
            [['link'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['group', 'category', 'sub_category'], 'string', 'max' => 500]
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Link',
            'group' => 'Group',
            'category' => 'Category',
            'sub_category' => 'Sub Category',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * @param $offset
     * @param $limit
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getLinks($offset, $limit)
    {
        return $this::find()->offset($offset)->limit($limit)->all();
    }
}
