<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_link".
 *
 * @property integer $id
 * @property string $link
 * @property string $added_at
 * @property string $update_at
 * @property string $deleted_at
 */
class ProductLink extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link'], 'required'],
            [['link'], 'string'],
            [['added_at', 'update_at', 'deleted_at'], 'safe']
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
            'added_at' => 'Added At',
            'update_at' => 'Update At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
