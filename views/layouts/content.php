<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<script>
    function ping() {
        $.ajax({
            url: "/index.php?r=site/ping-status",
            type: "POST",
            cache: false,
            dataType: "json",
            success: function (data) {
                $('#letual_persent .label-danger').text(data.let.persent);
                $('#letual_persent p').text(data.let.count);
                $('#letual_persent .progress-bar').width(data.let.persent);

                $('#rive_persent .label-danger').text(data.rive.persent);
                $('#rive_persent p').text(data.rive.count);
                $('#rive_persent .progress-bar').width(data.rive.persent);

                $('#ile_persent .label-danger').text(data.ile.persent);
                $('#ile_persent p').text(data.ile.count);
                $('#ile_persent .progress-bar').width(data.ile.persent);

                $('#elize_persent .label-danger').text(data.eli.persent);
                $('#elize_persent p').text(data.eli.count);
                $('#elize_persent .progress-bar').width(data.eli.persent);
            },
            error: function () {
                console.log("Ошибка выполнения");
            }
        });
    }

    $(document).ready(function () {
        ping();
        setInterval('ping()', 10000);

        $('a.parse').on('click', function () {
            $.ajax({
                url: "/index.php?r=site/start-parse",
                type: "POST",
                data: 'partner=' + $(this).attr('data'),
                cache: false,
                dataType: "json",
                success: function () {
                    $('#myModal').modal('show');
                },
                error: function () {
                    console.log("Ошибка выполнения");
                }
            });
        });
    });
</script>

<div class="content-wrapper">
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>

    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.0
    </div>
    <strong>&copy; 2014-<?= date('Y'); ?> Подружка</a>.</strong>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
        <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane active" id="control-sidebar-home-tab">
            <h3 class="control-sidebar-heading">Ручной запуск сбора</h3>
            <ul class='control-sidebar-menu'>
                <!--li>
                    <a class="parse" data="iledebeaute" href='javascript:void(0);'>
                        <i class="menu-icon fa fa-download bg-green"></i>

                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">ИльДеБотЭ</h4>
                        </div>
                    </a>
                </li-->
                <li>
                    <a class="parse" data="rivegauche" href='javascript:void(0);'>
                        <i class="menu-icon fa fa-download bg-green"></i>

                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">Сбор РивГош</h4>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="parse" data="elize" href='javascript:void(0);'>
                        <i class="menu-icon fa fa-download bg-green"></i>

                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">Сбор Элизе</h4>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="parse" data="letual" href='javascript:void(0);'>
                        <i class="menu-icon fa fa-download bg-green"></i>

                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">Сбор Летуаль</h4>
                        </div>
                    </a>
                </li>
            </ul>
            <!-- /.control-sidebar-menu -->

            <h3 class="control-sidebar-heading">Прогресс сбора данных</h3>
            <h5 class="control-sidebar-heading">Автоматический сбор каждый 1 и 3 день недели</h5>
            <ul class='control-sidebar-menu'>
                <li id="letual_persent">
                    <a href='javascript:void(0);'>
                        <h4 class="control-sidebar-subheading">
                            Сбор Летуаль
                            <span class="label label-danger pull-right">0%</span>
                        </h4>
                        <p>сбор не запущен</p>
                        <div class="progress progress-xxs">
                            <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                        </div>
                    </a>
                </li>
                <li id="rive_persent">
                    <a href='javascript:void(0);'>
                        <h4 class="control-sidebar-subheading">
                            Сбор РивГош
                            <span class="label label-danger pull-right">0%</span>
                        </h4>
                        <p>сбор не запущен</p>
                        <div class="progress progress-xxs">
                            <div class="progress-bar progress-bar-danger" style="width: 0%"></div>
                        </div>
                    </a>
                </li>
                <!--li id="ile_persent">
                    <a href='javascript:void(0);'>
                        <h4 class="control-sidebar-subheading">
                            Сбор Ильдэботе
                            <span class="label label-danger pull-right">0%</span>
                        </h4>
                        <p>сбор не запущен</p>
                        <div class="progress progress-xxs">
                            <div class="progress-bar progress-bar-danger" style="width: 0%"></div>
                        </div>
                    </a>
                </li-->
                <li id="elize_persent">
                    <a href='javascript:void(0);'>
                        <h4 class="control-sidebar-subheading">
                            Сбор Элизэ
                            <span class="label label-danger pull-right">0%</span>
                        </h4>
                        <p>сбор не запущен</p>
                        <div class="progress progress-xxs">
                            <div class="progress-bar progress-bar-danger" style="width: 0%"></div>
                        </div>
                    </a>
                </li>
            </ul>
            <!-- /.control-sidebar-menu -->

        </div>
        <!-- /.tab-pane -->

        <!-- Settings tab content -->
        <div class="tab-pane" id="control-sidebar-settings-tab">
            <form method="post">
                <h3 class="control-sidebar-heading">General Settings</h3>

                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        Report panel usage
                        <input type="checkbox" class="pull-right" checked/>
                    </label>

                    <p>
                        Some information about this general settings option
                    </p>
                </div>
                <!-- /.form-group -->

                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        Allow mail redirect
                        <input type="checkbox" class="pull-right" checked/>
                    </label>

                    <p>
                        Other sets of options are available
                    </p>
                </div>
                <!-- /.form-group -->

                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        Expose author name in posts
                        <input type="checkbox" class="pull-right" checked/>
                    </label>

                    <p>
                        Allow the user to show his name in blog posts
                    </p>
                </div>
                <!-- /.form-group -->

                <h3 class="control-sidebar-heading">Chat Settings</h3>

                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        Show me as online
                        <input type="checkbox" class="pull-right" checked/>
                    </label>
                </div>
                <!-- /.form-group -->

                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        Turn off notifications
                        <input type="checkbox" class="pull-right"/>
                    </label>
                </div>
                <!-- /.form-group -->

                <div class="form-group">
                    <label class="control-sidebar-subheading">
                        Delete chat history
                        <a href="javascript:void(0);" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                    </label>
                </div>
                <!-- /.form-group -->
            </form>
        </div>
        <!-- /.tab-pane -->
    </div>
</aside><!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>
<div class="example-modal">
    <div id="myModal" class="modal modal-primary">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <p>Скрипт сбора успешно запущен</p>
                    <p>Прогресс сбора отобразится в активностях</p>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-outline pull-right" type="button">OK</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
