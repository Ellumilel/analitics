<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ElizeCategory */

$this->title = 'Update Elize Category: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Elize Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="elize-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
