<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\IledebeauteProduct */

$this->title = 'Update Iledebeaute Product: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Iledebeaute Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="iledebeaute-product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
