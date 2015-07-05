<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RivegaucheCategory */

$this->title = 'Update Rivegauche Category: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rivegauche Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rivegauche-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
