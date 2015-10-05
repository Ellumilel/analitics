<?php
/**
 * Created by PhpStorm.
 * User: OzY
 * Date: 01.10.2015
 * Time: 20:50
 */

?>
<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"></h3>
            </div><!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th style="width: 30px">#</th>
                        <th>Бренд</th>
                        <th>Категории</th>
                        <th>кол-во арт.</th>
                        <th>p_price</th>
                        <th>p_ma_price</th>
                        <th>l_old_price</th>
                        <th>l_new_price</th>
                        <th>r_price</th>
                        <th>r_blue_price</th>
                        <th>r_gold_price</th>
                        <th>i_old_price</th>
                        <th>i_new_price</th>
                    </tr>
                    <?php foreach($brands as $key=>$brand): ?>
                        <tr>
                            <td><?= $key+1; ?></td>
                            <td><?= $brand['brand']; ?></td>
                            <td><?= $brand['category']; ?></td>
                            <td><?= $brand['count']; ?></td>
                            <?php if(!empty($brand['p_price']) && $brand['p_price'] !=0): ?>
                                <td><?= number_format($brand['p_price'], 0, ',', ' '); ?></td>
                            <?php else: ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if(!empty($brand['p_ma_price']) && $brand['p_ma_price'] !=0): ?>
                                <td><?= number_format($brand['p_ma_price'], 0, ',', ' '); ?></td>
                            <?php else: ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if(!empty($brand['l_old_price']) && $brand['l_old_price'] !=0): ?>
                                <td><?= number_format($brand['l_old_price'], 0, ',', ' '); ?></td>
                            <?php else: ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if(!empty($brand['l_new_price']) && $brand['l_new_price'] !=0): ?>
                                <td><?= number_format($brand['l_new_price'], 0, ',', ' '); ?></td>
                            <?php else: ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if(!empty($brand['r_price']) && $brand['r_price'] !=0): ?>
                                <td><?= number_format($brand['r_price'], 0, ',', ' '); ?></td>
                            <?php else: ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if(!empty($brand['r_blue_price']) && $brand['r_blue_price'] !=0): ?>
                                <td><?= number_format($brand['r_blue_price'], 0, ',', ' '); ?></td>
                            <?php else: ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if(!empty($brand['r_gold_price']) && $brand['r_gold_price'] !=0): ?>
                                <td><?= number_format($brand['r_gold_price'], 0, ',', ' '); ?></td>
                            <?php else: ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if(!empty($brand['i_old_price']) && $brand['i_old_price'] !=0): ?>
                                <td><?= number_format($brand['i_old_price'], 0, ',', ' '); ?></td>
                            <?php else: ?>
                                <td>0</td>
                            <?php endif; ?>
                            <?php if(!empty($brand['i_new_price']) && $brand['i_new_price'] !=0): ?>
                                <td><?= number_format($brand['i_new_price'], 0, ',', ' '); ?></td>
                            <?php else: ?>
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
