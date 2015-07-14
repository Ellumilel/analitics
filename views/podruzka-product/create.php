<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PodruzkaProduct */

$this->title = 'Create Podruzka Product';
$this->params['breadcrumbs'][] = ['label' => 'Podruzka Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="podruzka-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
