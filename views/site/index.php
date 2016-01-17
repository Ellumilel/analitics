<?php
use \yii\bootstrap\Html;
/* @var $this yii\web\View */
$this->title = 'Добро пожаловать в систему мониторинга!';
?>
<div class="site-index">
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= Html::img('@web/image/main_logo.jpg'); ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <table class="table table-striped">
                        <tbody><tr>
                            <th style="width: 30px">#</th>
                            <th>Каталог</th>
                            <th>кол-во</th>
                            <th>кол-во сопоставленных</th>
                        </tr>
                        <tr>
                            <td>1.</td>
                            <td>Летуаль</td>
                            <td><?= $let_count; ?></td>
                            <td><span class="badge bg-green"><?= $let_compare ?></span></td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>РивГош</td>
                            <td><?= $riv_count; ?></td>
                            <td><span class="badge bg-green"><?= $riv_compare ?></span></td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Элизэ</td>
                            <td><?= $eli_count; ?></td>
                            <td><span class="badge bg-green"><?= $eli_compare ?></span></td>
                        </tr>
                        <tr>
                            <td>4.</td>
                            <td>Иль Де Ботте</td>
                            <td><?= $ile_count; ?></td>
                            <td><span class="badge bg-green"><?= $ile_compare ?></span></td>
                        </tr>
                        </tbody></table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Конкуренты</h3>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                    <table class="table table-striped">
                        <tbody><tr>
                            <th style="width: 120px"></th>
                            <th style="width: 180px">Название</th>
                            <th>Средняя цена по сопоставленным</th>
                            <th>Средняя цена пересечение по брендам</th>
                            <th>Последнее обновление</th>
                        </tr>
                        <tr>
                            <td style="color: #000000;background-color: #111111"><a target="_blank" href="http://www.letu.ru/"><img src="http://www.letu.ru//common/img/logo.png" width="120px;"></a></td>
                            <td>Летуаль</td>
                            <td><?= number_format($let_avg->old_price, 2, ',', ' '); ?></td>
                            <td><?= number_format($let_avg_brand->old_price, 2, ',', ' '); ?></td>
                            <td><span class="badge bg-green"><?= $let_update ?></span></td>
                        </tr>
                        <tr>
                            <td style="color: #000000;background-color: #111111"><a target="_blank" href="http://shop.rivegauche.ru/store/ru/"><img src="http://shop.rivegauche.ru/media/images/h6b/h03/8807437893662.png" width="120px;"></a></td>
                            <td>РивГош</td>
                            <td><?= number_format($riv_avg->price, 2, ',', ' '); ?></td>
                            <td><?= number_format($riv_avg_brand->price, 2, ',', ' '); ?></td>
                            <td><span class="badge bg-green"><?= $riv_update ?></span></td>
                        </tr>
                        <tr>
                            <td style="color: #000000;background-color: #111111"><a target="_blank" href="https://elize.ru/"><img src="https://elize.ru/img/logo.png"  width="120px;"></a></td>
                            <td>Элизэ</td>
                            <td><?= number_format($eli_avg->old_price, 2, ',', ' '); ?></td>
                            <td><?= number_format($eli_avg_brand->old_price, 2, ',', ' '); ?></td>
                            <td><span class="badge bg-green"><?= $eli_update ?></span></td>
                        </tr>
                        <tr>
                            <td style="color: #000000;background-color: #111111"><a target="_blank" href="http://iledebeaute.ru/shop/"><img src="http://static.iledebeaute.ru/@/css/logo_idb.png"  width="120px;"></a></td>
                            <td>Иль Де Ботте</td>
                            <td><?= number_format($ile_avg->old_price, 2, ',', ' '); ?></td>
                            <td><?= number_format($ile_avg_brand->old_price, 2, ',', ' '); ?></td>
                            <td><span class="badge bg-green"><?= $ile_update ?></span></td>
                        </tr>
                        <tr>
                            <td ><a target="_blank" href="http://www.podrygka.ru/"><?= Html::img('@web/image/main_logo.jpg',['width'=>'100px;']); ?></a></td>
                            <td >Подружка</td>
                            <td><?= number_format($pod_avg->price, 2, ',', ' '); ?></td>
                            <td><?= number_format($pod_avg_brand->price, 2, ',', ' '); ?></td>
                            <td><span class="badge bg-green"><?= $pod_update ?></span></td>
                        </tr>
                        </tbody></table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
</div>
