<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "error_processing".
 *
 * @property string $id
 * @property string $link
 * @property string $competitor
 * @property string $error
 * @property integer $processing
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 */
class ErrorProcessing extends \yii\db\ActiveRecord
{
    const RIVE = 'riv';
    const LETU = 'let';
    const ILDE = 'ilde';
    const ELIZ = 'eliz';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'error_processing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link', 'competitor'], 'required'],
            [['link', 'error', 'comment'], 'string'],
            [['processing'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['competitor'], 'string', 'max' => 50],
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
            'competitor' => 'Competitor',
            'error' => 'Error',
            'processing' => 'Processing',
            'comment' => 'Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
     * @param $offset
     * @param $limit
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getLinks($offset, $limit)
    {
        return $this::find()->offset($offset)->limit($limit)->all();
    }
    /**
     * @inheritdoc
     * @return ErrorProcessingQuery the active query used by this AR class.
     */
    /*public static function find()
    {
        return new ErrorProcessingQuery(get_called_class());
    }*/
}
