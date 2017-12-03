<?php
$this->title = 'Загрузка сопоставления';

/* @var $this yii\web\View */
?>
<div class="col-md-4">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"></h3>
            <a href="<?= \Yii::$app->getUrlManager()->createUrl(['upload/example','file'=>'matching']); ?>" class="btn btn-success btn-xs">
                Скачать шаблон &nbsp<i class="fa fa-download"></i>
            </a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="upload-index">
                <?php $kato = \kato\DropZone::widget([
                    'dropzoneContainer' => 'upload_matching',
                    'options' => [
                        'url' => \Yii::$app->getUrlManager()->createUrl(['site/upload-matching']),
                        'dictDefaultMessage' => 'Перетащите сюда файлы для загрузки'
                    ],
                ]);
                echo $kato;
                ?>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</div>

