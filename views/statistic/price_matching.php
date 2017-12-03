<?php

use app\helpers\TextHelper;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\models\PodruzkaProduct;


/* @var $this yii\web\View */
/* @var $searchModel app\models\PodruzkaProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $product array */

$this->title = 'Сравнение цен по сопоставленным артикулам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="podruzka-product-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'resizableColumns' => true,
            'bordered' => false,
            'bootstrap' => true,
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
                    'format' => 'raw',
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'article',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true, 'width' => '200px'],
                    ],
                    'contentOptions' => ['style' => 'padding-left:10px; text-align: center;'],
                    'value' => function ($model, $key, $index, $widget) {
                        return $model->article;
                    },
                    'editableOptions' => function ($model, $key, $index) {
                        return [
                            'header' => 'l_id',
                            'size' => 'md',
                            'afterInput' => function ($form, $widget) use ($model, $index) {
                                return TextHelper::getArticleMatchingForm($model);
                            },
                            'formOptions' => [
                                'action' => \Yii::$app->getUrlManager()->createUrl(['podruzka-product/article-update']),
                            ],
                        ];
                    },
                ],
                'title',
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'arrival',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(
                        (new PodruzkaProduct)->getListArrival($condition),
                        'arrival',
                        'arrival'
                    ),
                    'contentOptions' => ['style' => 'padding-left:10px; text-align: center;'],
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true, 'width' => '90px'],
                    ],
                    'value' => 'arrival',
                ],
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'category',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(
                        (new PodruzkaProduct)->getListCategory($condition, true),
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
                    'attribute' => 'brand',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(
                        (new PodruzkaProduct)->getListBrand($condition, true),
                        'brand',
                        'brand'
                    ),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'value' => 'brand',
                ],
                'price',
                'ma_price',
                [
                    'attribute' => 'l_old_price',
                    'label' => 'let.price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->l->old_price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->l->old_price,
                            $data->l->link
                        ) : '';
                    },
                ],
                [
                    'attribute' => 'l_new_price',
                    'label' => 'let.new_price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->l->new_price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->l->new_price,
                            $data->l->link
                        ) : '';
                    },
                ],
                [
                    'attribute' => 'r_price',
                    'label' => 'rive.r_price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->r->price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->r->price,
                            $data->r->link
                        ) : '';
                    },
                ],
                [
                    'attribute' => 'r_gold_price',
                    'label' => 'rive.r_gold_price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->r->gold_price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->r->gold_price,
                            $data->r->link
                        ) : '';
                    },
                ],
                [
                    'attribute' => 'e_old_price',
                    'label' => 'eli.old_price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->e->old_price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->e->old_price,
                            $data->e->link
                        ) : '';
                    },
                ],
                [
                    'attribute' => 'e_new_price',
                    'label' => 'eli.new_price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->e->new_price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->e->new_price,
                            $data->e->link
                        ) : '';
                    },
                ],
                [
                    'attribute' => 'i_old_price',
                    'label' => 'ile.old_price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->i->old_price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->i->old_price,
                            $data->i->link
                        ) : '';
                    },
                ],
                [
                    'attribute' => 'i_new_price',
                    'label' => 'ile.new_price',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return (!empty($data->i->new_price)) ? TextHelper::getPriceMatchLink(
                            $data->price,
                            $data->i->new_price,
                            $data->i->link
                        ) : '';
                    },
                ],
                //['class' => 'yii\grid\ActionColumn'],
            ],
            'responsive' => true,
            'hover' => true,
            'pjax' => true,
            'floatHeader' => true,
            //'htmlOptions' => ['class' => 'table-matching'],
            'headerRowOptions'=>['class'=>'kartik-sheet-style'],
            'panel'=>[
                'type'=>GridView::TYPE_PRIMARY,
                'heading'=> 'Сравнение цен по сопоставленным артикулам ',
            ],
            //'floatHeaderOptions' => ['scrollingTop' => '180'],
            'floatOverflowContainer' => true,
            'export'=>false,
            'toolbar' => false,
            'id' => 'price-matching',
            'autoXlFormat'=>true,
            'pjaxSettings' => [
                'neverTimeout' => true,
                'options'=>[
                    'id'=>'price-matching',
                ]
            //'beforeGrid' => 'My fancy content before.',
                //'afterGrid' => 'My fancy content after.',
            ],
        ]
    );
    ?>
</div>
