<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LetualCategory */

$this->title = 'Создание категории';
$this->params['breadcrumbs'][] = ['label' => 'Letual категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letual-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
