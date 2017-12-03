<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rivegauche_category".
 *
 * @property integer $id
 * @property string $link
 * @property string $group
 * @property string $category
 * @property string $sub_category
 * @property string $type
 */
class RivegaucheCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rivegauche_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link'], 'required'],
            [['link'], 'string'],
            [['group', 'category', 'sub_category', 'type'], 'string', 'max' => 255],
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
            'type' => 'Тип сбора',
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
