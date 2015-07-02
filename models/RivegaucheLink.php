<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rivegauche_link".
 *
 * @property integer $id
 * @property string $link
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class RivegaucheLink extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'rivegauche_link';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['link'], 'required'],
            [['link'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Link',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }
}
