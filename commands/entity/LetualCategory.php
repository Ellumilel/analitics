<?php

namespace app\commands\entity;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class LetualCategory
 *
 * @package app\commands
 */
class LetualCategory
{
    private $link;

    public function __construct()
    {
        //TODO продумать механизм получение и записи категорий через базу
        $this->link = [
            'http://www.letu.ru/makiyazh/dlya-litsa/osnova-dlya-makiyazha?viewAll=true' => [
                'Макияж',
                'Для лица',
                'Основа для макияжа',
            ],
            'http://www.letu.ru/makiyazh/dlya-litsa/tonalnye-sredstva?viewAll=true' => [
                'Макияж',
                'Для лица',
                'Тональные средства',
            ],
            'http://www.letu.ru/makiyazh/dlya-litsa/korrektiruyushchie-sredstva?viewAll=true' => [
                'Макияж',
                'Для лица',
                'Корректирующие средства',
            ],
            'http://www.letu.ru/makiyazh/dlya-litsa/pudra?viewAll=true' => [
                'Макияж',
                'Для лица',
                'Пудра',
            ],
            'http://www.letu.ru/makiyazh/dlya-litsa/rumyana?viewAll=true' => [
                'Макияж',
                'Для лица',
                'Румяна',
            ],
            'http://www.letu.ru/makiyazh/dlya-litsa/matiruyushchie-sredstva?viewAll=true' => [
                'Макияж',
                'Для лица',
                'Матирующие средства',
            ],
            'http://www.letu.ru/makiyazh/dlya-glaz/tush?viewAll=true' => [
                'Макияж',
                'Для глаз',
                'Тушь',
            ],
            'http://www.letu.ru/makiyazh/dlya-glaz/teni?viewAll=true' => [
                'Макияж',
                'Для глаз',
                'Тени',
            ],
            'http://www.letu.ru/makiyazh/dlya-glaz/konturnye-karandashi-i-podvodka?viewAll=true' => [
                'Макияж',
                'Для глаз',
                'Контурные карандаши и подводка',
            ],
            'http://www.letu.ru/makiyazh/dlya-glaz/osnova-dlya-makiyazha?viewAll=true' => [
                'Макияж',
                'Для глаз',
                'Основа для макияжа',
            ],
            'http://www.letu.ru/makiyazh/dlya-glaz/dlya-brovei?viewAll=true' => [
                'Макияж',
                'Для глаз',
                'Для бровей',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return array_keys($this->link);
    }

    /**
     * @param string $link
     *
     * @return string
     */
    public function getGroup($link)
    {
        $result = '';
        if(array_key_exists($link,$this->link)) {
            $result = $this->link[$link][0];
        }

        return $result;
    }

    /**
     * @param string $link
     *
     * @return string
     */
    public function getCategory($link)
    {
        $result = '';
        if(array_key_exists($link,$this->link)) {
            $result = $this->link[$link][1];
        }

        return $result;
    }

    /**
     * @param string $link
     *
     * @return string
     */
    public function getSubCategory($link)
    {
        $result = '';
        if(array_key_exists($link,$this->link)) {
            $result = $this->link[$link][2];
        }

        return $result;
    }
}