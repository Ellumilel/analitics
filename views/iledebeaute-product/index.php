<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use \app\models\IledebeauteProduct;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\IledebeauteProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список товаров ИльДэБоте';
$this->params['breadcrumbs'][] = $this->title;
?>
<a href="<?= \Yii::$app->getUrlManager()->createUrl(['download/download','company'=>'ile']); ?>" class="btn btn-primary" ><i class="fa fa-download"></i> Выгрузка Иль Де Боте</a><br><br>
<div class="iledebeaute-product-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'striped' => false,
        'rowOptions' => function ($model, $key, $index, $grid) {
            return ['id' => $model['id'], 'onclick' => '
            if ( !$(this).hasClass("success") ) {
                $(this).addClass("success");
            } else {
                $(this).removeClass("success");
            }'];
        },
        'columns' => [
            [
                //'filterType' => GridView::FILTER_RANGE,
                'attribute' => 'article',
                'value' => function ($data) {
                    if (!empty($data->article)) {
                        return '<a href="' . $data->link . '" target="_blank">'.$data->article.'</a>';
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'contentOptions' => ['style' => 'padding-left:10px; text-align: center; width: 150px;'],
            ],
            [
                'filterInputOptions' => ['placeholder' => ''],
                'attribute' => 'group',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new IledebeauteProduct)->dropDownGroup($condition), 'group', 'group'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'value' => 'group',
                'contentOptions' => ['style' => 'min-width:120px;'],
            ],
            [
                'filterInputOptions' => ['placeholder' => ''],
                'attribute' => 'category',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new IledebeauteProduct)->dropDownCategory($condition), 'category',
                    'category'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'value' => 'category',
                'contentOptions' => ['style' => 'min-width:180px;'],
            ],
            [
                'filterInputOptions' => ['placeholder' => ''],
                'attribute' => 'sub_category',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new IledebeauteProduct)->dropDownSubCategory($condition), 'sub_category',
                    'sub_category'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'value' => 'sub_category',
                'contentOptions' => ['style' => 'min-width:180px;'],
            ],
            [
                'format' => 'raw',
                'filterInputOptions' => ['placeholder' => ''],
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'brand',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new IledebeauteProduct)->dropDownBrand($condition), 'brand', 'brand'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'value' => function ($model, $key, $index, $widget) {
                    return Html::a($model->brand, '#', ['title' => 'Редактировать']);
                },
                'editableOptions' => [
                    'header' => 'brand',
                    'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                    'size' => 'md',
                    'formOptions' => [
                        'action' => \Yii::$app->getUrlManager()->createUrl(['iledebeaute-product/brand-update']),
                    ],
                ],
            ],
            'title:ntext',
            'description',
            [
                'attribute' => 'old_price',
                'value' => 'old_price',
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
            [
                'attribute' => 'new_price',
                'value' => 'new_price',
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'contentOptions' => ['style' => 'text-align: center;'],
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'pjax' => true,
        'export' => false,
        'toggleData' => false,
        'panel'=>[
            'type'=>GridView::TYPE_PRIMARY,
            'heading'=> 'Список товаров ИльДэБоте',
        ],
        'pjaxSettings' => [
            'neverTimeout' => true,
            'options'=>[
                'id'=>'iledebeaute_product',
            ]
        ]
    ]); ?>

</div>
