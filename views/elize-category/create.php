<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ElizeCategory */

$this->title = 'Создать категорию для Elize';
$this->params['breadcrumbs'][] = ['label' => 'Elize Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="elize-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
