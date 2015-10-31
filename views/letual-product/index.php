<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LetualProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список товаров Летуаль';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="letual-product-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            //'floatHeader'=>true,
            //'floatHeaderOptions'=>['scrollingTop'=>'50'],
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                //'id',
                [
                    //'filterType' => GridView::FILTER_RANGE,
                        'attribute' => 'article',
                        'value'=>'article',
                        'format' => 'raw',
                    // 'filterType' => GridView::FILTER_POS_HEADER,
                        'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true, 'width' => '400px'],
                        ],
                ],
                //'link:ntext',
                [
                        'filterInputOptions'=>['placeholder'=>''],
                        'attribute' => 'group',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => \app\models\LetualProduct::dropDownGroup(),
                        'filterWidgetOptions'=>[
                                'pluginOptions'=>['allowClear'=>true, 'width' => '200px'],
                        ],
                        'value' => 'group',
                ],
                [
                    'filterInputOptions'=>['placeholder'=>''],
                    'attribute' => 'category',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \app\models\LetualProduct::dropDownCategory(),
                    'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear'=>true, 'width' => '200px'],
                    ],
                    'value' => 'category',
                ],
                [
                    'filterInputOptions'=>['placeholder'=>''],
                    'attribute' => 'sub_category',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => \app\models\LetualProduct::dropDownCategory(),
                    'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true, 'width' => '200px'],
                    ],
                    'value' => 'sub_category',
                ],
                [
                    'format'=>'raw',
                    'filterInputOptions'=>['placeholder'=>''],
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'brand',
                    //'filter' => \app\models\LetualProductSearch::dropDownBrand(),
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter'=>\app\models\LetualProduct::dropDownBrand(), //
                    'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true, 'width' => '200px'],
                    ],
                    'value'=>function ($model, $key, $index, $widget) {
                        return Html::a($model->brand, '#', ['title'=>'Редактировать']);
                    },
                    'editableOptions' => [
                            'header' => 'brand',
                            'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                            'size'=>'md',
                            'formOptions' => [
                                    'action' => \Yii::$app->getUrlManager()->createUrl(['letual-product/brand-update']),
                            ],
                    ],
                ],
                'title:ntext',
                'description',
                [

                    //'filterType' => GridView::FILTER_RANGE,
                    'attribute' => 'new_price',
                    'value'=>'new_price',
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                    ],
                ],
                [
                    //'filterType' => GridView::FILTER_RANGE,
                    'attribute' => 'old_price',
                    'value'=>'old_price',
                    'format' => 'raw',
                       // 'filterType' => GridView::FILTER_POS_HEADER,
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                    ],
                ],
                //'new_price',
                //'old_price',
                //'image_link:ntext',
                // 'created_at',
                // 'updated_at',
                // 'deleted_at',

               // ['class' => 'yii\grid\ActionColumn'],
            ],

            'responsive' => true,
            'hover' => true,
            'pjax'=>true,
            'pjaxSettings'=>[
                    'neverTimeout'=>true,
                    'beforeGrid'=>'My fancy content before.',
                    'afterGrid'=>'My fancy content after.',
            ]
    ]); ?>

</div>
