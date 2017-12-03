<?php
$this->title = 'Загрузка комментариев';

?>
<div class="row">
    <div class="col-md-6">
        <!-- /.box-body -->
        <!-- The time line -->
        <ul class="timeline">
            <li>
                <i class="fa fa-cloud-download bg-red"></i>
                <div class="timeline-item">

                    <h3 class="timeline-header">Для начала необходимо скачать файл с комментариями <a
                            href="<?= \Yii::$app->getUrlManager()->createUrl(['upload/example', 'file' => 'comment']); ?>"
                            class="btn btn-success btn-xs">
                            Выгрузить &nbsp<i class="fa fa-download"></i>
                        </a></h3>
                </div>
            </li>
            <li>
                <i class="fa fa-file-word-o bg-aqua"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header no-border">Вносим необходимые изменения</h3>
                </div>
            </li>
            <li>
                <i class="fa fa-times bg-red"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header no-border">Удаляем лишние строки не требующие обновления</h3>
                </div>
            </li>
            <li>
                <i class="fa fa-cloud-upload bg-yellow"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header">Загрузить полученный файл в формате xls, xlsx</h3>
                    <div class="upload-index">
                        <?php $kato = \kato\DropZone::widget(
                            [
                                'dropzoneContainer' => 'upload_comment',
                                'options' => [
                                    'url' => \Yii::$app->getUrlManager()->createUrl(['site/upload-comment']),
                                    'dictDefaultMessage' => 'Перетащите сюда файлы для загрузки',
                                ],
                            ]
                        );
                        echo $kato;
                        ?>
                    </div>
                </div>
            </li>
            <li>
                <div class="timeline-item">
                    <h3 class="timeline-header">Проверить успешную загрузку</h3>
                </div>
                <i class="fa fa-check-square-o bg-green"></i>
            </li>
            <li>
                <i class="fa fa-clock-o bg-gray"></i>
            </li>
        </ul>
    </div>
</div>
