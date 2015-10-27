<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RivegaucheProduct */

$this->title = 'Обновление: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Rivegauche Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rivegauche-product-update">
    <?= $this->render('_empty_brand_form', [
        'model' => $model,
    ]) ?>
</div>
