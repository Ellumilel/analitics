<?php

namespace app\helpers;

use app\models\ElizeProduct;
use app\models\IledebeauteProduct;
use app\models\LetualProduct;
use app\models\RivegaucheProduct;
use yii\helpers\Html;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class TextHelper
 *
 * @package app\helpers
 */
class TextHelper
{
    /**
     * @param $price
     * @param $priceCompare
     *
     * @return string
     */
    public static function getPriceMatch($price, $priceCompare)
    {
        $result = '';
        $price = round($price);
        $priceCompare = round($priceCompare);
        if ($price > $priceCompare) {
            $result = sprintf(
                '%s <sub><span class="text-red"><i class="fa fa-angle-up">%s</i></span></sub>',
                $priceCompare,
                (($price - $priceCompare) < 0) ? ($price - $priceCompare) * -1 : ($price - $priceCompare)
            );
        } elseif ($price < $priceCompare) {
            $result = sprintf(
                '%s <sub><span class="text-green"><i class="fa fa-angle-down">%s</i></span></sub>',
                $priceCompare,
                (($price - $priceCompare) < 0) ? ($price - $priceCompare) * -1 : ($price - $priceCompare)
            );
        } elseif ($price == $priceCompare) {
            $result = sprintf(
                '%s <sub><span class="text-yellow"><i class="fa fa-angle-left">%s</i></span></sub>',
                $priceCompare,
                (($price - $priceCompare) < 0) ? ($price - $priceCompare) * -1 : ($price - $priceCompare)
            );
        }

        return $result;
    }

    /**
     * Выбирает цену и оборачивает в ссылку
     *
     * @param string $price
     * @param string $priceCompare
     * @param string $link
     *
     * @return string
     */
    public static function getPriceMatchLink($price, $priceCompare, $link)
    {
        $result = sprintf('<a href="%s" target="_blank">%s</a>', $link, self::getPriceMatch($price, $priceCompare));

        return $result;
    }

    /**
     * Возвращаем объект картинку для отображения в grid
     *
     * @param string $imgLink
     *
     * @return string
     */
    public static function getImage($imgLink)
    {
        $result = Html::img($imgLink, ['width' => '100px;']);

        return $result;
    }

    /**
     * Возвращаем объект картинку обернутую в <a>
     *
     * @param string $imgLink
     * @param string $link
     *
     * @return string
     */
    public static function getImageLink($imgLink, $link)
    {
        $result = sprintf(
            '<a href="%s" target="_blank">%s</a>',
            $link,
            Html::img(
                $imgLink,
                [
                    'height' => '130px;',
                    'width' => '100px;',
                ]
            )
        );

        return $result;
    }

    /**
     * Возвращает строку с инпутами для таблицы с формой сопоставления
     *
     * @param $model
     *
     * @return string
     */
    public static function getArticleMatchingForm($model)
    {
        $result = Html::activeLabel($model, 'l_id');
        $result .= Html::input(
            '',
            'l_id',
            (!empty($lp = LetualProduct::find()->where(['id' => $model->l_id])->one())) ? $lp->article : '',
            ['class' => 'kv-editable-input form-control']
        );

        $result .= Html::activeLabel($model, 'r_id');
        $result .= Html::input(
            '',
            'r_id',
            (!empty($lp = RivegaucheProduct::find()->where(['id' => $model->r_id])->one())) ? $lp->article : '',
            ['class' => 'kv-editable-input form-control']
        );

        $result .= Html::activeLabel($model, 'e_id');
        $result .= Html::input(
            '',
            'e_id',
            (!empty($ep = ElizeProduct::find()->where(['id' => $model->e_id])->one())) ? $ep->article : '',
            ['class' => 'kv-editable-input form-control']
        );

        $result .= Html::activeLabel($model, 'i_id');
        $result .= Html::input(
            '',
            'i_id',
            (!empty($lp = IledebeauteProduct::find()->where(['id' => $model->i_id])->one())) ? $lp->article : '',
            ['class' => 'kv-editable-input form-control']
        );

        return $result;
    }
}
