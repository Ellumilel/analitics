<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ElizeProduct */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Elize Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elize-product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'article',
            'link:ntext',
            'group',
            'category',
            'sub_category',
            'brand',
            'title:ntext',
            'description',
            'image_link:ntext',
            'showcases_new',
            'showcases_exclusive',
            'showcases_limit',
            'showcases_sale',
            'showcases_best',
            'new_price',
            'old_price',
            'created_at',
            'updated_at',
            'deleted_at',
        ],
    ]) ?>

</div>
