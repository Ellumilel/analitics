<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RivegaucheProduct */

$this->title = 'Create Rivegauche Product';
$this->params['breadcrumbs'][] = ['label' => 'Rivegauche Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rivegauche-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
