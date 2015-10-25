<?php
use yii\bootstrap\Nav;
use yii\helpers\Html;

?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?= Html::img('@web/image/snimok_57.jpg', ['class' =>'img-circle', 'alt'=>'Администратор']); ?>
            </div>
            <div class="pull-left info">
                <p>Администратор</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <!--form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= \app\widgets\Menu::widget([
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Управление', 'options' => ['class' => 'header']],
                        [
                                'label' => 'Информационный продукт',
                                'icon' => 'fa fa-dashboard text-aqua',
                                'url' => ['/podruzka-product/index']
                        ],
                        [
                                'label' => 'Статистика сбора данных',
                                'icon' => 'fa fa-book text-aqua',
                                'url' => ['/statistic/index']
                        ],
                        [
                                'label' => 'Сопоставление',
                                'icon' => 'fa fa-files-o text-aqua',
                                'url' => ['/podruzka-product/matching']
                        ],
                        [
                                'label' => 'Сравнение цен',
                                'icon' => 'fa fa-circle-o text-aqua',
                                'url' => ['/statistic/price-matching']
                        ],
                        ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                        [
                            'label' => 'Среднее по сопоставл.:',
                            'icon' => 'fa fa-share text-yellow',
                            'url' => '#',
                            'items' => [
                                    [
                                            'label' => 'по категориям',
                                            'icon' => 'fa fa-circle-o text-aqua',
                                            'url' => ['/statistic/avg-category'],
                                    ],
                                    [
                                            'label' => 'по брендам',
                                            'icon' => 'fa fa-circle-o text-aqua',
                                            'url' => ['/statistic/avg-brand'],
                                    ],
                                    [
                                            'label' => 'по брендам + категориям',
                                            'icon' => 'fa fa-circle-o text-aqua',
                                            'url' => ['/statistic/avg-matching'],
                                    ],
                                /*[
                                    'label' => 'Среднее по сопоставл.',
                                    'icon' => 'fa fa-circle-o',
                                    'url' => '#',
                                    'items' => [
                                        ['label' => 'по категориям', 'icon' => 'fa fa-circle-o', 'url' => ['/statistic/avg-category'],],
                                        [
                                            'label' => 'по брендам',
                                            'icon' => 'fa fa-circle-o',
                                            'url' => ['/statistic/avg-brand'],
                                            'items' => [
                                                ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                                ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                            ],
                                        ],
                                    ],
                                ],*/
                            ],
                        ],
                        /*[
                                'label' => 'Таблицы сбора: ',
                                'icon' => 'fa fa-table text-yellow',
                                'url' => '#',
                                'items' => [
                                        [
                                                'label' => 'Летуаль',
                                                'icon' => 'fa fa-circle-o text-aqua',
                                                'url' => ['/letual-product/index'],
                                        ],
                                        [
                                                'label' => 'РивГош',
                                                'icon' => 'fa fa-circle-o text-aqua',
                                                'url' => ['/rivegauche-product/index'],
                                        ],
                                        [
                                                'label' => 'ИльДеБоте',
                                                'icon' => 'fa fa-circle-o text-aqua',
                                                'url' => ['/iledebeaute-product/index'],
                                        ],
                                ],
                        ],*/
                ],
            ]
        ) ?>
        <!--ul class="sidebar-menu">
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i> <span>Средние значения</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= \yii\helpers\Url::to(['/gii']) ?>"><span class="fa fa-file-code-o"></span>по бренду</a>
                    </li>
                    <li><a href="<?= \yii\helpers\Url::to(['/debug']) ?>"><span class="fa fa-dashboard"></span>по категориям</a>
                    <li><a href="<?= \yii\helpers\Url::to(['/debug']) ?>"><span class="fa fa-dashboard"></span>по сопоставленным</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Level One <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-circle-o"></i> Level Two <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul-->
    </section>
</aside>
