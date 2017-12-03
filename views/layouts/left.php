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
                <a href="#"><i class="circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <!--form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="search"></i>
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
                                'icon' => 'dashboard text-aqua',
                                'url' => ['/podruzka-product/index']
                        ],
                        [
                                'label' => 'Статистика сбора данных',
                                'icon' => 'calendar-check-o text-green',
                                'url' => ['/statistic/index']
                        ],
                        [
                            'label' => 'Статистика удаленные',
                            'icon' => 'calendar-times-o text-red',
                            'url' => ['/statistic/index-deleted']
                        ],
                        [
                                'label' => 'Сопоставление',
                                'icon' => 'files-o text-aqua',
                                'url' => ['/podruzka-product/matching']
                        ],
                        [
                                'label' => 'Сравнение цен',
                                'icon' => 'rub text-white',
                                'url' => ['/statistic/price-matching']
                        ],
                        ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                        [
                            'label' => 'Среднее по сопоставл.:',
                            'icon' => 'caret-down text-yellow',
                            'url' => '#',
                            'options' => ['class' => 'treeview'],
                            'items' => [
                                    [
                                            'label' => 'категория',
                                            'icon' => 'table text-white',
                                            'url' => ['/statistic/avg-category'],
                                    ],
                                    [
                                            'label' => 'бренд',
                                            'icon' => 'table text-white',
                                            'url' => ['/statistic/avg-brand'],
                                    ],
                                    [
                                            'label' => 'категория и бренд',
                                            'icon' => 'table text-white',
                                            'url' => ['/statistic/avg-matching'],
                                    ],
                                /*[
                                    'label' => 'Среднее по сопоставл.',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        [
                                            'label' => 'по категориям',
                                            'icon' => 'circle-o',
                                            'url' => ['/statistic/avg-category'],
                                        ],
                                        [
                                            'label' => 'по брендам',
                                            'icon' => 'circle-o',
                                            'url' => ['/statistic/avg-brand'],
                                            'items' => [
                                                ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                                ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ],
                                        ],
                                    ],
                                ],*/
                            ],
                        ],
                        [
                            'label' => 'Таблицы сбора: ',
                            'icon' => 'caret-down text-yellow',
                            'url' => '#',
                            'options' => ['class' => 'treeview'],
                            'items' => [
                                [
                                    'label' => 'Летуаль',
                                    'icon' => 'table text-white',
                                    'url' => ['/letual-product/index'],
                                ],
                                [
                                    'label' => 'РивГош',
                                    'icon' => 'table text-white',
                                    'url' => ['/rivegauche-product/index'],
                                ],
                                [
                                    'label' => 'Элизэ',
                                    'icon' => 'table text-white',
                                    'url' => ['/elize-product/index'],
                                ],
                                [
                                    'label' => 'ИльДеБоте',
                                    'icon' => 'table text-white',
                                    'url' => ['/iledebeaute-product/index'],
                                ],
                            ],
                        ],
                ],
            ]
        ) ?>
        <!--ul class="sidebar-menu">
            <li class="treeview">
                <a href="#">
                    <i class="share"></i> <span>Средние значения</span>
                    <i class="angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?= \yii\helpers\Url::to(['/gii']) ?>"><span class="file-code-o"></span>по бренду</a>
                    </li>
                    <li><a href="<?= \yii\helpers\Url::to(['/debug']) ?>"><span class="dashboard"></span>по категориям</a>
                    <li><a href="<?= \yii\helpers\Url::to(['/debug']) ?>"><span class="dashboard"></span>по сопоставленным</a>
                    </li>
                    <li>
                        <a href="#"><i class="circle-o"></i> Level One <i class="angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="circle-o"></i> Level Two</a></li>
                            <li>
                                <a href="#">
                                    <i class="circle-o"></i> Level Two <i class="angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="circle-o"></i> Level Three</a></li>
                                    <li><a href="#"><i class="circle-o"></i> Level Three</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul-->
    </section>
</aside>
