<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "parsing_status".
 *
 * @property integer $id
 * @property string $company
 * @property string $status
 * @property string $start_date
 * @property string $end_date
 */
class ParsingStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parsing_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_date', 'end_date'], 'safe'],
            [['company', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company' => 'Company',
            'status' => 'Status',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
    }

    /**
     * @param $company
     */
    public function start($company)
    {
        $customer = $this::findOne(['company' => $company]);
        if (!$customer) {
            $customer = new $this();
        }
        $customer->company = $company;
        $customer->status = 'start';
        $customer->start_date = (new \DateTime())->format('Y-m-d H:i:s');

        $customer->save();
    }

    /**
     * @param $company
     */
    public function end($company)
    {
        $customer = $this::findOne(['company' => $company]);
        if (!$customer) {
            $customer = new $this();
        }
        $customer->company = $company;
        $customer->status = 'end';
        $customer->end_date = (new \DateTime())->format('Y-m-d H:i:s');

        $customer->save();
    }
}
