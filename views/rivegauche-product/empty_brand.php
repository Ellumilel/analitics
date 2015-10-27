<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\TextHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RivegaucheProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'РивГош: пустые бренды';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rivegauche-product-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'article',
            [
                'attribute'=>'link',
                'format' => 'raw',
                'value' => function ($data) {
                    return TextHelper::getImageLink($data->image_link, $data->link);
                },
            ],
            'title',
            'description',
            'group',
            'category',
            'sub_category',
            'brand',
            'gold_price',
            'blue_price',
            'price',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    $url = '';
                    if ($action === 'update') {
                        $url = \Yii::$app->getUrlManager()->createUrl(
                            ['rivegauche-product/empty-brand-update', 'id' => $model->id]
                        );
                        return $url;
                    }
                    return $url;
                }
            ],
        ],
    ]); ?>

</div>
