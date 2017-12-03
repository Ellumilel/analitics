<?php
$this->title = 'Загрузка брендов';
?>
<div class="row">
    <div class="col-md-6">
        <!-- The time line -->
        <ul class="timeline">
            <li>
                <i class="fa fa-cloud-download bg-red"></i>
                <div class="timeline-item">

                    <h3 class="timeline-header">Для начала необходимо скачать файл с пустыми брендами <a
                            href="<?= \Yii::$app->getUrlManager()->createUrl(['upload/example', 'file' => 'brand']); ?>"
                            class="btn btn-success btn-xs">
                            Выгрузить &nbsp<i class="fa fa-download"></i>
                        </a></h3>
                </div>
            </li>
            <li>
                <i class="fa fa-search bg-aqua"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header no-border">Провести проверку и заполнить пустые поля с наименованием
                        бренда</h3>
                    <div class="timeline-header no-border">
                        <i class="fa fa-warning text-red"></i>&nbspВнимание наименование брендов необходимо брать

                        <a target="_blank" href="https://shop.rivegauche.ru/store/ru/brands" class="btn btn-primary btn-xs">
                            из справочника</a>
                    </div>

                </div>
            </li>
            <li>
                <i class="fa fa-cloud-upload bg-yellow"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header">Загрузить полученный файл</h3>
                    <div class="upload-index">
                        <?php $kato = \kato\DropZone::widget(
                            [
                                'dropzoneContainer' => 'upload_brand',
                                'options' => [
                                    'url' => \Yii::$app->getUrlManager()->createUrl(['site/upload-brand']),
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
    <!-- /.col -->
</div>
