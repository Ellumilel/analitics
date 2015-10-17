<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RivegaucheProduct */

$this->title = 'Update Rivegauche Product: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Rivegauche Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rivegauche-product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
