<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use \app\models\LetualProduct;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LetualProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список товаров Летуаль';
$this->params['breadcrumbs'][] = $this->title;

?>
<a href="<?= \Yii::$app->getUrlManager()->createUrl(['download/download', 'company' => 'letual']); ?>"
   class="btn btn-primary"><i class="fa fa-download"></i> Выгрузка Летуаль</a><br><br>
<div class="letual-product-index">
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
                    //'value' => 'article',
                    'value' => function ($data) {
                        if (!empty($data->article)) {
                            return '<a href="'.$data->link.'" target="_blank">'.$data->article.'</a>';
                        } else {
                            return '';
                        }
                    },
                    'contentOptions' => ['style' => 'padding-left:10px; text-align: center; width: 100px;'],
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'group',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map((new LetualProduct)->dropDownGroup($condition), 'group', 'group'),
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
                        (new LetualProduct)->dropDownCategory($condition),
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
                        (new LetualProduct)->dropDownSubCategory($condition),
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
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(
                        (new LetualProduct)->dropDownBrand($condition),
                        'brand',
                        'brand'
                    ),
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
                            'action' => \Yii::$app->getUrlManager()->createUrl(['letual-product/brand-update']),
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
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => 'Список товаров Летуаль',
            ],
            'pjaxSettings' => [
                'neverTimeout' => true,
                'options'=>[
                    'id'=>'letual_product',
                ]
            ],
        ]
    ); ?>

</div>
