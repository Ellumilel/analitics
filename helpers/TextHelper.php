<?php

namespace app\helpers;

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

    public static function getPriceMatchLink($price, $priceCompare, $link)
    {
        $result = sprintf('<a href="%s" target="_blank">%s</a>', $link, self::getPriceMatch($price, $priceCompare));

        return $result;
    }
}
