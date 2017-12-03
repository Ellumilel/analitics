<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use \app\models\RivegaucheProduct;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RivegaucheProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список товаров РивГош';
$this->params['breadcrumbs'][] = $this->title;
?>
<a href="<?= \Yii::$app->getUrlManager()->createUrl(['download/download', 'company' => 'rive']); ?>"
   class="btn btn-primary"><i class="fa fa-download"></i> Выгрузка РивГош</a><br><br>
<div class="rivegauche-product-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'striped' => false,
            'rowOptions' => function ($model, $key, $index, $grid) {
                return [
                    'id' => $model['id'],
                    'onclick' => '
            if ( !$(this).hasClass("success") ) {
                $(this).addClass("success");
            } else {
                $(this).removeClass("success");
            }',
                ];
            },
            'columns' => [
                [
                    'attribute' => 'article',
                    'value' => function ($data) {
                        if (!empty($data->article)) {
                            return '<a href="'.$data->link.'" target="_blank">'.$data->article.'</a>';
                        } else {
                            return '';
                        }
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'contentOptions' => ['style' => 'padding-left:10px; text-align: center; width: 100px;'],
                ],
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'group',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(
                        (new RivegaucheProduct)->dropDownGroup($condition),
                        'group',
                        'group'
                    ),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'value' => 'group',
                ],
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'category',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(
                        (new RivegaucheProduct)->dropDownCategory($condition),
                        'category',
                        'category'
                    ),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'value' => 'category',
                ],
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'sub_category',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(
                        (new RivegaucheProduct)->dropDownSubCategory($condition),
                        'sub_category',
                        'sub_category'
                    ),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'value' => 'sub_category',
                ],
                [
                    'format' => 'raw',
                    'filterInputOptions' => ['placeholder' => ''],
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'brand',
                    //'filter' => \app\models\LetualProductSearch::dropDownBrand(),
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map((new RivegaucheProduct)->dropDownBrand($condition), 'brand', 'brand'),
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
                            'action' => \Yii::$app->getUrlManager()->createUrl(
                                ['rivegauche-product/brand-update']
                            ),
                        ],
                    ],
                ],
                'title:ntext',
                'description',
                [
                    //'filterType' => GridView::FILTER_RANGE,
                    'attribute' => 'price',
                    'value' => 'price',
                    'format' => 'raw',
                    // 'filterType' => GridView::FILTER_POS_HEADER,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'contentOptions' => ['style' => 'text-align: center;'],
                ],
                [
                    //'filterType' => GridView::FILTER_RANGE,
                    'attribute' => 'special_price',
                    'value' => 'special_price',
                    'format' => 'raw',
                    // 'filterType' => GridView::FILTER_POS_HEADER,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'contentOptions' => ['style' => 'text-align: center;'],
                ],
                [
                    //'filterType' => GridView::FILTER_RANGE,
                    'attribute' => 'blue_price',
                    'value' => 'blue_price',
                    'format' => 'raw',
                    // 'filterType' => GridView::FILTER_POS_HEADER,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'contentOptions' => ['style' => 'text-align: center;'],
                ],
                [
                    //'filterType' => GridView::FILTER_RANGE,
                    'attribute' => 'gold_price',
                    'value' => 'gold_price',
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
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => 'Список товаров РивГош',
            ],
            'pjaxSettings' => [
                'neverTimeout' => true,
                'options'=>[
                    'id'=>'rivegauche_product',
                ]
                // 'afterGrid' => 'My fancy content after.',
            ],
        ]
    ); ?>
</div>
