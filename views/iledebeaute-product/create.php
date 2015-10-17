<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\IledebeauteProduct */

$this->title = 'Create Iledebeaute Product';
$this->params['breadcrumbs'][] = ['label' => 'Iledebeaute Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iledebeaute-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
