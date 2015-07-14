<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "iledebeaute_category".
 *
 * @property integer $id
 * @property string $link
 * @property string $group
 * @property string $category
 * @property string $sub_category
 */
class IledebeauteCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iledebeaute_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link'], 'required'],
            [['link'], 'string'],
            [['group', 'category', 'sub_category'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Ссылка',
            'group' => 'Группа',
            'category' => 'Категория',
            'sub_category' => 'Подкатегория',
        ];
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this::find()->all();
    }
}
