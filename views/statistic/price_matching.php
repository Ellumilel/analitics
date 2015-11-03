<?php

use yii\widgets\Pjax;
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
<div class="row">
    <div class="col-md-4">
        <div>
            <ul>
                <li><a href="#">Цена Подружки выше на <span class="pull text-red">   <i class="fa fa-angle-up"></i> 123</span></a>
                </li>
                <li><a href="#">Цена Подружки ниже на<span class="pull text-green">   <i class="fa fa-angle-down"></i> 123</span></a>
                </li>
                <li><a href="#">Цены одинаковые <span class="pull text-yellow">   <i
                                class="fa fa-angle-left"></i> 0</span></a></li>
            </ul>
        </div>
    </div>
</div>
<div class="podruzka-product-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'resizableColumns' => true,
            'bordered' => false,
            'rowOptions' => function ($model, $key, $index, $grid) {
                return [
                    'id' => $model['id'],
                    'onclick' => '
            if ( !$(this).hasClass("success") ) {
                $(this).addClass("success");
            } else {
                $(this).removeClass("success");
            }'
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
                    }
                ],
                'title',
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'arrival',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map((new PodruzkaProduct)->getListArrival($condition), 'arrival',
                        'arrival'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true, 'width' => '90px'],
                    ],
                    'value' => 'arrival',
                ],
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'category',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map((new PodruzkaProduct)->getListCategory($condition, true), 'category',
                        'category'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true, 'width' => '190px'],
                    ],
                    'value' => 'category',
                ],
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'brand',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map((new PodruzkaProduct)->getListBrand($condition, true), 'brand',
                        'brand'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true, 'width' => '120px'],
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
            'pjaxSettings' => [
                'neverTimeout' => true,
                'beforeGrid' => 'My fancy content before.',
                'afterGrid' => 'My fancy content after.',
            ]
        ]
    ); ?>
</div>
