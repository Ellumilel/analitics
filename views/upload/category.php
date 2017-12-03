<?php
$this->title = 'Загрузка "Без категории"';

?>
<div class="row">
    <div class="col-md-6">
        <!-- /.box-body -->
        <!-- The time line -->
        <ul class="timeline">
            <li>
                <i class="fa fa-cloud-download bg-red"></i>
                <div class="timeline-item">

                    <h3 class="timeline-header">Для начала необходимо скачать файл с данными "Без категории" <a
                            href="<?= \Yii::$app->getUrlManager()->createUrl(['upload/example', 'file' => 'category']); ?>"
                            class="btn btn-success btn-xs">
                            Выгрузить &nbsp<i class="fa fa-download"></i>
                        </a></h3>
                </div>
            </li>
            <li>
                <i class="fa fa-file-word-o bg-aqua"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header no-border">Заполняем колонки нужными наименованиями категорий</h3>
                    <div class="col-md-3">
                        <div class="box box-success collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Группа</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body" style="">
                                <table class="table table-condensed">
                                    <tbody>
                                    <? foreach ($group as $item): ?>
                                        <tr>
                                            <td><?= $item['group']; ?></td>
                                        </tr>
                                    <? endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box box-success collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Категория</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body" style="">
                                <table class="table table-condensed">
                                    <tbody>
                                    <? foreach ($category as $item): ?>
                                        <tr>
                                            <td><?= $item['category']; ?></td>
                                        </tr>
                                    <? endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box box-success collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Под Категория</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body" style="">
                                <table class="table table-condensed">
                                    <tbody>
                                    <? foreach ($sub as $item): ?>
                                        <tr>
                                            <td><?= $item['sub_category']; ?></td>
                                        </tr>
                                    <? endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <i class="fa fa-cloud-upload bg-yellow"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header">Загрузить полученный файл в формате xls, xlsx</h3>
                    <div class="upload-index">
                        <?php $kato = \kato\DropZone::widget(
                            [
                                'dropzoneContainer' => 'upload_category',
                                'options' => [
                                    'url' => \Yii::$app->getUrlManager()->createUrl(['site/upload-category']),
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
