<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LetualCategory */

$this->title = 'Изменение категории: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Letual категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="letual-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
