<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RivegaucheProduct */

$this->title = 'Обновление: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'РивГош позиция', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rivegauche-product-update">
    <?= $this->render('_empty_category_form', [
        'model' => $model,
    ]) ?>
</div>
