<?php

use yii\helpers\Html;


$this->title = 'Загрузка информ. продукта';

/* @var $this yii\web\View */
?>
<div class="upload-index">
    <?
    $kato = \kato\DropZone::widget([
        'dropzoneContainer' => 'upload_inform',
        'options' => [
            'url' => \Yii::$app->getUrlManager()->createUrl(['site/upload']),

        ],
    ]);
    echo $kato;
    ?>
</div>
