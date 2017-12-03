<?php
/**
 * Created by PhpStorm.
 * User: OzY
 * Date: 01.10.2015
 * Time: 20:50
 */
$this->title = 'Среднее по сопоставленным (категория и бренд)';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row avg_category">
    <div class="col-md-6">
        <div class="box ">
            <div class="box-header with-border panel-heading">
                <h3 class="box-title">Летуаль</h3>
            </div>
            <div class="box-body">
                <table id="avg_category" class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 30px">#</th>
                        <th>Категория</th>
                        <th>Бренд</th>
                        <th>кол-во арт.</th>
                        <th>p_price</th>
                        <th>p_ma_price</th>
                        <th>l_old_price</th>
                        <th>l_new_price</th>
                        <th>l_old %</th>
                        <th>l_new %</th>
                    </tr>
                    <?php foreach ($brandsLet as $key => $brand) : ?>
                        <tr>
                            <td><?= $key + 1; ?></td>
                            <td><?= $brand['category']; ?></td>
                            <td><?= $brand['brand']; ?></td>
                            <td><?= $brand['count']; ?></td>
                            <?php if (!empty($brand['p_price']) && $brand['p_price'] != 0) : ?>
                                <td><?= number_format($brand['p_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['p_ma_price']) && $brand['p_ma_price'] != 0) : ?>
                                <td><?= number_format($brand['p_ma_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['l_old_price']) && $brand['l_old_price'] != 0) : ?>
                                <td><?= number_format($brand['l_old_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['l_new_price']) && $brand['l_new_price'] != 0) : ?>
                                <td><?= number_format($brand['l_new_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>

                            <?php if (!empty($brand['l_old_percent']) && $brand['l_old_percent'] != 0) : ?>
                                <?php if ($brand['l_old_percent'] > 0) : ?>
                                    <td><span class="badge bg-green"><?= number_format(
                                                $brand['l_old_percent'],
                                                0,
                                                ',',
                                                ' '
                                            ); ?>%</span></td>
                                <?php else : ?>
                                    <td><span class="badge bg-red"><?= number_format(
                                                $brand['l_old_percent'],
                                                0,
                                                ',',
                                                ' '
                                            ); ?>%</span></td>
                                <?php endif; ?>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['l_new_percent']) && $brand['l_new_percent'] != 0) : ?>
                                <?php if ($brand['l_new_percent'] > 0) : ?>
                                    <td><span class="badge bg-green"><?= number_format(
                                                $brand['l_new_percent'],
                                                0,
                                                ',',
                                                ' '
                                            ); ?>%</span></td>
                                <?php else : ?>
                                    <td><span class="badge bg-red"><?= number_format(
                                                $brand['l_new_percent'],
                                                0,
                                                ',',
                                                ' '
                                            ); ?>%</span></td>
                                <?php endif; ?>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border  panel-heading">
                <h3 class="box-title">РивГош</h3>
            </div>
            <div class="box-body">
                <table id="avg_category" class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 30px">#</th>
                        <th>Категория</th>
                        <th>Бренд</th>
                        <th>кол-во арт.</th>
                        <th>p_price</th>
                        <th>p_ma_price</th>
                        <th>r_price</th>
                        <th>r_blue_price</th>
                        <th>r_gold_price</th>
                        <th>r %</th>
                    </tr>
                    <?php foreach ($brandsRiv as $key => $brand) : ?>
                        <tr>
                            <td><?= $key + 1; ?></td>
                            <td><?= $brand['category']; ?></td>
                            <td><?= $brand['brand']; ?></td>
                            <td><?= $brand['count']; ?></td>
                            <?php if (!empty($brand['p_price']) && $brand['p_price'] != 0) : ?>
                                <td><?= number_format($brand['p_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['p_ma_price']) && $brand['p_ma_price'] != 0) : ?>
                                <td><?= number_format($brand['p_ma_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['r_price']) && $brand['r_price'] != 0) : ?>
                                <td><?= number_format($brand['r_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['r_blue_price']) && $brand['r_blue_price'] != 0) : ?>
                                <td><?= number_format($brand['r_blue_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['r_gold_price']) && $brand['r_gold_price'] != 0) : ?>
                                <td><?= number_format($brand['r_gold_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>

                            <?php if (!empty($brand['r_percent']) && $brand['r_percent'] != 0) : ?>
                                <?php if ($brand['r_percent'] > 0) : ?>
                                    <td><span class="badge bg-green"><?= number_format(
                                                $brand['r_percent'],
                                                0,
                                                ',',
                                                ' '
                                            ); ?>%</span></td>
                                <?php else : ?>
                                    <td><span class="badge bg-red"><?= number_format(
                                                $brand['r_percent'],
                                                0,
                                                ',',
                                                ' '
                                            ); ?>
                                            %</span></td>
                                <?php endif; ?>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div>
<div class="row avg_category">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border  panel-heading">
                <h3 class="box-title">Элизе</h3>
            </div>
            <div class="box-body">
                <table id="avg_category" class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 30px">#</th>
                        <th>Категория</th>
                        <th>Бренд</th>
                        <th>кол-во арт.</th>
                        <th>p_price</th>
                        <th>p_ma_price</th>
                        <th>e_old_price</th>
                        <th>e_new_price</th>
                        <th>e %</th>
                    </tr>
                    <?php foreach ($brandsEli as $key => $brand) : ?>
                        <tr>
                            <td><?= $key + 1; ?></td>
                            <td><?= $brand['category']; ?></td>
                            <td><?= $brand['brand']; ?></td>
                            <td><?= $brand['count']; ?></td>
                            <?php if (!empty($brand['p_price']) && $brand['p_price'] != 0) : ?>
                                <td><?= number_format($brand['p_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['p_ma_price']) && $brand['p_ma_price'] != 0) : ?>
                                <td><?= number_format($brand['p_ma_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['e_old_price']) && $brand['e_old_price'] != 0) : ?>
                                <td><?= number_format($brand['e_old_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['e_new_price']) && $brand['e_new_price'] != 0) : ?>
                                <td><?= number_format($brand['e_new_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>

                            <?php if (!empty($brand['e_percent']) && $brand['e_percent'] != 0) : ?>
                                <?php if ($brand['e_percent'] > 0) : ?>
                                    <td><span class="badge bg-green"><?= number_format(
                                                $brand['e_percent'],
                                                0,
                                                ',',
                                                ' '
                                            ); ?>%</span></td>
                                <?php else : ?>
                                    <td><span class="badge bg-red"><?= number_format(
                                                $brand['e_percent'],
                                                0,
                                                ',',
                                                ' '
                                            ); ?>%</span></td>
                                <?php endif; ?>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border panel-heading">
                <h3 class="box-title">ИльДэБоте</h3>
            </div>
            <div class="box-body">
                <table id="avg_category" class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 30px">#</th>
                        <th>Категория</th>
                        <th>Бренд</th>
                        <th>кол-во арт.</th>
                        <th>p_price</th>
                        <th>p_ma_price</th>
                        <th>i_old_price</th>
                        <th>i_new_price</th>
                        <th>i %</th>
                    </tr>
                    <?php foreach ($brandsIle as $key => $brand) : ?>
                        <tr>
                            <td><?= $key + 1; ?></td>
                            <td><?= $brand['category']; ?></td>
                            <td><?= $brand['brand']; ?></td>
                            <td><?= $brand['count']; ?></td>
                            <?php if (!empty($brand['p_price']) && $brand['p_price'] != 0) : ?>
                                <td><?= number_format($brand['p_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['p_ma_price']) && $brand['p_ma_price'] != 0) : ?>
                                <td><?= number_format($brand['p_ma_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['i_old_price']) && $brand['i_old_price'] != 0) : ?>
                                <td><?= number_format($brand['i_old_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if (!empty($brand['i_new_price']) && $brand['i_new_price'] != 0) : ?>
                                <td><?= number_format($brand['i_new_price'], 0, ',', ' '); ?></td>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>

                            <?php if (!empty($brand['i_percent']) && $brand['i_percent'] != 0) : ?>
                                <?php if ($brand['i_percent'] > 0) : ?>
                                    <td><span class="badge bg-green"><?= number_format(
                                                $brand['i_percent'],
                                                0,
                                                ',',
                                                ' '
                                            ); ?>%</span></td>
                                <?php else : ?>
                                    <td><span class="badge bg-red"><?= number_format(
                                                $brand['i_percent'],
                                                0,
                                                ',',
                                                ' '
                                            ); ?>%</span></td>
                                <?php endif; ?>
                            <?php else : ?>
                                <td>0</td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div>
