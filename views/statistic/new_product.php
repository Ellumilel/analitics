<?php

use yii\widgets\Pjax;
use yii\grid\GridView;
use app\helpers\TextHelper;
use yii\helpers\ArrayHelper;

/* @var $searchModel app\models\PodruzkaProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $product array */
/* @var $partner string */

$this->title = 'Статистика сбора данных по новинкам: '. $partner;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-body">
    <a href="<?= \Yii::$app->getUrlManager()->createUrl(['/statistic/index']); ?>">
        <button class="btn btn-default btn-xs"><i class="fa fa-share"></i>   Назад к статистике</button>
    </a>
</div>
<div class="product-index">
    <?php Pjax::begin(); ?>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => array_merge([
                    'article',
                    [
                            'attribute'=>'link',
                            'format' => 'raw',
                            'value' => function ($data) {
                                return TextHelper::getImageLink($data->image_link, $data->link);
                            },
                    ],
                    [
                            'attribute'=>'group',
                            'filter'=> ArrayHelper::map($model->getListGroup($condition), 'group', 'group'),
                    ],
                    [
                            'attribute'=>'category',
                            'filter'=> ArrayHelper::map($model->getListCategory($condition), 'category', 'category'),
                    ],
                    [
                            'attribute'=>'sub_category',
                            'filter'=> ArrayHelper::map($model->getListSubCategory($condition), 'sub_category', 'sub_category'),
                    ],
                    [
                            'attribute'=>'brand',
                            'filter'=> ArrayHelper::map($model->getListBrand($condition), 'brand', 'brand'),
                    ],
                    'title',
                    'description',
                    'created_at',
            ], $price),
        ]
    ); ?>
    <?php Pjax::end(); ?>
</div>
