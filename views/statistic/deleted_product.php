<?php
use yii\widgets\Pjax;
use kartik\grid\GridView;
use app\helpers\TextHelper;
use yii\helpers\ArrayHelper;

/* @var $searchModel app\models\PodruzkaProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $product array */
/* @var $partner string */

$this->title = 'Статистика удаленных: '.$partner;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box-body">
    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['/statistic/index-deleted']); ?>">
        <button class="btn btn-primary btn-xl"><i class="fa fa-share"></i> Назад к статистике</button>
    </a>
</div>
<div class="product-index">
    <?php Pjax::begin(); ?>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'resizableColumns' => true,
            'bordered' => false,
            'bootstrap' => true,
            'striped' => false,
            'columns' => array_merge(
                [
                    [
                        'attribute' => 'article',
                        'value' => function ($data) {
                            return $data->article;
                        },
                        'format' => 'raw',
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'contentOptions' => ['style' => 'padding-left:10px; text-align: center; width: 100px;'],
                    ],
                    [
                        'attribute' => 'link',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return TextHelper::getImageLink($data->image_link, $data->link);
                        },
                    ],
                    [
                        'filterInputOptions' => ['placeholder' => ''],
                        'attribute' => 'group',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ArrayHelper::map($model->getListGroup($condition), 'group', 'group'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'value' => 'group',
                        'contentOptions' => ['style' => 'min-width:150px;'],
                    ],
                    [
                        'filterInputOptions' => ['placeholder' => ''],
                        'attribute' => 'category',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ArrayHelper::map($model->getListCategory($condition), 'category', 'category'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'value' => 'category',
                        'contentOptions' => ['style' => 'min-width:150px;'],
                    ],
                    [
                        'filterInputOptions' => ['placeholder' => ''],
                        'attribute' => 'sub_category',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ArrayHelper::map(
                            $model->getListSubCategory($condition),
                            'sub_category',
                            'sub_category'
                        ),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'value' => 'sub_category',
                        'contentOptions' => ['style' => 'min-width:150px;'],
                    ],
                    [
                        'filterInputOptions' => ['placeholder' => ''],
                        'attribute' => 'brand',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' =>  ArrayHelper::map($model->getListBrand($condition), 'brand', 'brand'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'value' => 'brand',
                        'contentOptions' => ['style' => 'min-width:120px;'],
                    ],
                    'title',
                    'description',
                    'created_at',
                ],
                $price
            ),
            'responsive' => true,
            'hover' => true,
            'pjax' => true,
            'export' => false,
            'toggleData' => false,
            'floatHeader' => true,
            'id' => 'deleted',
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'panel' => [
                'type' => GridView::TYPE_PRIMARY,
                'heading' => 'Список удаленных',
            ],
            'floatOverflowContainer' => true,
            'pjaxSettings' => [
                'neverTimeout' => true,
                'options'=>[
                    'id'=>'deleted_product',
                ]
                // 'afterGrid' => 'My fancy content after.',
            ],
        ]
    ); ?>
    <?php Pjax::end(); ?>
</div>
