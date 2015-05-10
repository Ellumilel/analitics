<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LetualProduct */

$this->title = 'Create Letual Product';
$this->params['breadcrumbs'][] = ['label' => 'Letual Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letual-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
