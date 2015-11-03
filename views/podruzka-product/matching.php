<?php

use yii\helpers\ArrayHelper;
use app\models\PodruzkaProduct;
use kartik\grid\GridView;
use \app\helpers\TextHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PodruzkaProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $product array */

$this->title = 'Сопоставление';
$this->params['breadcrumbs'][] = $this->title;
?>
<a href="<?= \Yii::$app->getUrlManager()->createUrl(['download/matching']); ?>" class="btn btn-primary"><i
        class="fa fa-download"></i> Выгрузить в Excel</a>
<div class="pdddodruzka-product-index">
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'resizableColumns' => true,
        'bordered' => false,
        'rowOptions' => function ($model, $key, $index, $grid) {
            return ['id' => $model['id'], 'style' => 'min-width:200px;', 'onclick' => '
            if ( !$(this).hasClass("success") ) {
                $(this).addClass("success");
            } else {
                $(this).removeClass("success");
            }'];
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
                'filter' => ArrayHelper::map((new PodruzkaProduct)->getListArrival($condition), 'arrival', 'arrival'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '80px'],
                ],
                'value' => 'arrival',
            ],
            [
                'filterInputOptions' => ['placeholder' => ''],
                'attribute' => 'group',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new PodruzkaProduct)->getListGroup($condition, true), 'group', 'group'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '150px'],
                ],
                'value' => 'group',
            ],
            [
                'filterInputOptions' => ['placeholder' => ''],
                'attribute' => 'category',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new PodruzkaProduct)->getListCategory($condition, true), 'category',
                    'category'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '150px'],
                ],
                'value' => 'category',
            ],
            [
                'filterInputOptions' => ['placeholder' => ''],
                'attribute' => 'sub_category',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new PodruzkaProduct)->getListSubCategory($condition, true),
                    'sub_category', 'sub_category'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '150px'],
                ],
                'value' => 'sub_category',
            ],
            [
                'filterInputOptions' => ['placeholder' => ''],
                'attribute' => 'detail',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new PodruzkaProduct)->getListDetail($condition, true), 'detail',
                    'detail'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '200px'],
                ],
                'value' => 'detail',
            ],
            [
                'filterInputOptions' => ['placeholder' => ''],
                'attribute' => 'brand',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new PodruzkaProduct)->getListBrand($condition, true), 'brand', 'brand'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '100px'],
                ],
                'value' => 'brand',
            ],
            [
                'filterInputOptions' => ['placeholder' => ''],
                'attribute' => 'sub_brand',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new PodruzkaProduct)->getListSubBrand($condition, true), 'sub_brand',
                    'sub_brand'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '100px'],
                ],
                'value' => 'sub_brand',
            ],
            [
                'filterInputOptions' => ['placeholder' => ''],
                'attribute' => 'line',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map((new PodruzkaProduct)->getListLine($condition, true), 'line', 'line'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '100px'],
                ],
                'value' => 'line',
            ],
            'price',
            'ma_price',
            [
                'attribute' => 'l_old_price',
                'value' => function ($data) {
                    return (!empty($data->l->old_price)) ? $data->l->old_price : '';
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                ],
            ],
            [
                'attribute' => 'l_new_price',
                'value' => function ($data) {
                    return (!empty($data->l->new_price)) ? $data->l->new_price : '';
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                ],
            ],
            [
                'attribute' => 'r_price',
                'value' => function ($data) {
                    return (!empty($data->r->price)) ? $data->r->price : '';
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                ],
            ],
            [
                'attribute' => 'r_blue_price',
                'value' => function ($data) {
                    return (!empty($data->r->blue_price)) ? $data->r->blue_price : '';
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                ],
            ],
            [
                'attribute' => 'r_gold_price',
                'value' => function ($data) {
                    return (!empty($data->r->gold_price)) ? $data->r->gold_price : '';
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                ],
            ],
            [
                'attribute' => 'i_old_price',
                'value' => function ($data) {
                    return (!empty($data->i->old_price)) ? $data->i->old_price : '';
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                ],
            ],
            [
                'attribute' => 'i_new_price',
                'value' => function ($data) {
                    return (!empty($data->i->new_price)) ? $data->i->new_price : '';
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                ],
            ],
            [
                'attribute' => 'l_title',
                'value' => function ($data) {
                    $text = '';
                    if (!empty($data->l->title)) {
                        $text .= $data->l->title;
                    }
                    if (!empty($data->l->description)) {
                        $text .= ' ' . $data->l->description;
                    }
                    return $text;
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                ],
            ],
            [
                'attribute' => 'l_link',
                'value' => function ($data) {
                    if (!empty($data->l->link)) {
                        return '<a href="' . $data->l->link . '" target="_blank">ссылка</a>';
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                ],
            ],
            [
                'attribute' => 'r_title',
                'value' => function ($data) {
                    $text = '';
                    if (!empty($data->r->title)) {
                        $text .= $data->r->title;
                    }
                    if (!empty($data->r->description)) {
                        $text .= ' ' . $data->r->description;
                    }
                    return $text;
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                ],
            ],
            [
                'attribute' => 'r_link',
                'value' => function ($data) {
                    if (!empty($data->r->link)) {
                        return '<a href="' . $data->r->link . '" target="_blank">ссылка</a>';
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                ],
            ],
            [
                'attribute' => 'i_title',
                'value' => function ($data) {
                    $text = '';
                    if (!empty($data->i->title)) {
                        $text .= $data->i->title;
                    }
                    if (!empty($data->i->description)) {
                        $text .= ' ' . $data->i->description;
                    }
                    return $text;
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                ],
            ],
            [
                'attribute' => 'r_link',
                'value' => function ($data) {
                    if (!empty($data->i->link)) {
                        return '<a href="' . $data->i->link . '" target="_blank">ссылка</a>';
                    } else {
                        return '';
                    }
                },
                'format' => 'raw',
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                ],
            ],
            [
                'attribute' => 'l_date',
                'value' => function ($data) {
                    if (!empty($data->l->updated_at)) {
                        return date('Y-m-d', strtotime($data->l->updated_at));
                    } else {
                        return null;
                    }
                }
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
            'beforeGrid' => 'My fancy content before.',
            'afterGrid' => 'My fancy content after.',
        ]
    ]); ?>
</div>
