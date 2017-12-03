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
        class="fa fa-download"></i> Выгрузить в Excel</a></br></br>
<div class="pdddodruzka-product-index">
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>
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
                    'style' => 'min-width:200px;',
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
                    //'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'article',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'contentOptions' => ['style' => 'padding-left:10px; text-align: center;'],
                    'value' => function ($model, $key, $index, $widget) {
                        return $model->article;
                    },
                    /*'editableOptions' => function ($model, $key, $index) {
                        return [
                            'header' => 'l_id',
                            'size' => 'sm',
                            'format' => 'link',
                            'asPopover' => true,
                            //'placement' => 'left',
                            'afterInput' => function ($form, $widget) use ($model, $index) {
                                return TextHelper::getArticleMatchingForm($model);
                            },
                            'formOptions' => [
                                'action' => \Yii::$app->getUrlManager()->createUrl(['podruzka-product/article-update']),
                            ],
                        ];
                    },*/
                ],
                [
                    'attribute' => 'title',
                    'value' => 'title',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'max-width: 500px; width: 500px;'],
                ],
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'arrival',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(
                        (new PodruzkaProduct)->getListArrival($condition),
                        'arrival',
                        'arrival'
                    ),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true, 'width' => '80px'],
                    ],
                    'value' => 'arrival',
                ],
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'group',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(
                        (new PodruzkaProduct)->getListGroup($condition, true),
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
                    'attribute' => 'sub_category',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(
                        (new PodruzkaProduct)->getListSubCategory($condition, true),
                        'sub_category',
                        'sub_category'
                    ),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'value' => 'sub_category',
                ],
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'detail',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(
                        (new PodruzkaProduct)->getListDetail($condition, true),
                        'detail',
                        'detail'
                    ),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'value' => 'detail',
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
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'sub_brand',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(
                        (new PodruzkaProduct)->getListSubBrand($condition, true),
                        'sub_brand',
                        'sub_brand'
                    ),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'value' => 'sub_brand',
                ],
                [
                    'filterInputOptions' => ['placeholder' => ''],
                    'attribute' => 'line',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map((new PodruzkaProduct)->getListLine($condition, true), 'line', 'line'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
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
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                [
                    'attribute' => 'l_new_price',
                    'value' => function ($data) {
                        return (!empty($data->l->new_price)) ? $data->l->new_price : '';
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                [
                    'attribute' => 'r_price',
                    'value' => function ($data) {
                        return (!empty($data->r->price)) ? $data->r->price : '';
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                /*[
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
                ],*/
                [
                    'attribute' => 'e_old_price',
                    'value' => function ($data) {
                        return (!empty($data->e->old_price)) ? $data->e->old_price : '';
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                [
                    'attribute' => 'e_new_price',
                    'value' => function ($data) {
                        return (!empty($data->e->new_price)) ? $data->e->new_price : '';
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                [
                    'attribute' => 'i_old_price',
                    'value' => function ($data) {
                        return (!empty($data->i->old_price)) ? $data->i->old_price : '';
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                /*[
                    'attribute' => 'i_new_price',
                    'value' => function ($data) {
                        return (!empty($data->i->new_price)) ? $data->i->new_price : '';
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true, 'width' => '50px'],
                    ],
                ],*/
                [
                    'attribute' => 'l_title',
                    'value' => function ($data) {
                        $text = '';
                        if (!empty($data->l->title)) {
                            $text .= $data->l->title;
                        }
                        if (!empty($data->l->description)) {
                            $text .= ' '.$data->l->description;
                        }

                        if (!empty($data->l->link) && !empty($text)) {
                            return '<a href="'.$data->l->link.'" target="_blank">'.$text.'</a>';
                        } else {
                            return $text;
                        }
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                [
                    'attribute' => 'let_comment',
                    'value' => function ($data) {
                        $text = '';
                        if (!empty($data->let_comment)) {
                            $text = $data->let_comment;
                        }
                        return $text;
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
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
                            $text .= ' '.$data->r->description;
                        }
                        if (!empty($data->r->link) && !empty($text)) {
                            return '<a href="'.$data->r->link.'" target="_blank">'.$text.'</a>';
                        } else {
                            return $text;
                        }
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                [
                    'attribute' => 'riv_comment',
                    'value' => function ($data) {
                        $text = '';
                        if (!empty($data->riv_comment)) {
                            $text = $data->riv_comment;
                        }
                        return $text;
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                [
                    'attribute' => 'e_title',
                    'value' => function ($data) {
                        $text = '';
                        if (!empty($data->e->title)) {
                            $text .= $data->e->title;
                        }
                        if (!empty($data->e->description)) {
                            $text .= ' '.$data->e->description;
                        }

                        if (!empty($data->e->link) && !empty($text)) {
                            return '<a href="'.$data->e->link.'" target="_blank">'.$text.'</a>';
                        } else {
                            return $text;
                        }
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                [
                    'attribute' => 'eli_comment',
                    'value' => function ($data) {
                        $text = '';
                        if (!empty($data->eli_comment)) {
                            $text = $data->eli_comment;
                        }
                        return $text;
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
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
                            $text .= ' '.$data->i->description;
                        }

                        if (!empty($data->i->link) && !empty($text)) {
                            return '<a href="'.$data->i->link.'" target="_blank">'.$text.'</a>';
                        } else {
                            return $text;
                        }
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                [
                    'attribute' => 'ile_comment',
                    'value' => function ($data) {
                        $text = '';
                        if (!empty($data->ile_comment)) {
                            $text = $data->ile_comment;
                        }
                        return $text;
                    },
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                ],
                /*[
                    'attribute' => 'l_date',
                    'value' => function ($data) {
                        if (!empty($data->l->updated_at)) {
                            return date('Y-m-d', strtotime($data->l->updated_at));
                        } else {
                            return null;
                        }
                    }
                ],*/
            ],
            'responsive' => true,
            'hover' => true,
            'pjax' => true,
            'export' => false,
            'toolbar' => false,
            'floatHeader' => true,
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => 'Сопоставление',
            ],
            //'floatHeaderOptions' => ['scrollingTop' => '180'],
            'floatOverflowContainer' => true,
            'id' => 'matching',
            'pjaxSettings' => [
                'neverTimeout' => true,
                'options' => [
                    'id' => 'matching',
                ],
            ],
        ]
    ); ?>
</div>
