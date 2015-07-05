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
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-litsom/sredstva-dlya-ochishcheniya-kozhi?viewAll=true' => [
                'Уход за кожей',
                'Уход за лицом',
                'Средства для очищения кожи',
            ],
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-litsom/sredstva-dlya-uvlazhneniya-kozhi?viewAll=true' => [
                'Уход за кожей',
                'Уход за лицом',
                'Средства для увлажнения кожи',
            ],
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-litsom/pitatelnye-sredstva?viewAll=true' => [
                'Уход за кожей',
                'Уход за лицом',
                'Питательные средства',
            ],
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-litsom/sredstva-protiv-stareniya?viewAll=true' => [
                'Уход за кожей',
                'Уход за лицом',
                'Средства против старения',
            ],
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-litsom/sredstva-dlya-kontura-glaz?viewAll=true' => [
                'Уход за кожей',
                'Уход за лицом',
                'Средства для контура глаз',
            ],
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-litsom/sredstva-dlya-zagara?viewAll=true' => [
                'Уход за кожей',
                'Уход за лицом',
                'Средства для загара',
            ],
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-telom/sredstva-dlya-ochishcheniya-kozhi?viewAll=true' => [
                'Уход за кожей',
                'Уход за телом',
                'Средства для очищения кожи',
            ],
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-telom/sredstva-dlya-uvlazhneniya-kozhi?viewAll=true' => [
                'Уход за кожей',
                'Уход за телом',
                'Средства для увлажнения кожи',
            ],
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-telom/pitatelnye-sredstva?viewAll=true' => [
                'Уход за кожей',
                'Уход за телом',
                'Питательные средства',
            ],
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-telom/antitsellyulitnye-sredstva?viewAll=true' => [
                'Уход за кожей',
                'Уход за телом',
                'Антицеллюлитные средства',
            ],
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-telom/sredstva-protiv-stareniya?viewAll=true' => [
                'Уход за кожей',
                'Уход за телом',
                'Средства против старения',
            ],
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-telom/sredstva-dlya-zagara?viewAll=true' => [
                'Уход за кожей',
                'Уход за телом',
                'Средства для загара',
            ],
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-telom/dezodoranty-antiperspiranty?viewAll=true' => [
                'Уход за кожей',
                'Уход за телом',
                'Дезодоранты-антиперспиранты',
            ],
            'http://www.letu.ru/uhod-za-kozhei/uhod-za-telom/sredstva-dlya-britya?viewAll=true' => [
                'Уход за кожей',
                'Уход за телом',
                'Средства для бритья',
            ],
            'http://www.letu.ru/uhod-za-kozhei/dlya-muzhchin/do-i-posle-britya?viewAll=true' => [
                'Уход за кожей',
                'Для мужчин',
                'До и после бритья',
            ],
            'http://www.letu.ru/uhod-za-kozhei/dlya-muzhchin/dlya-vannoi-i-dusha?viewAll=true' => [
                'Уход за кожей',
                'Для мужчин',
                'Для ванной и душа',
            ],
            'http://www.letu.ru/uhod-za-kozhei/dlya-muzhchin/spetsialnye-sredstva?viewAll=true' => [
                'Уход за кожей',
                'Для мужчин',
                'Специальные средства',
            ],
            'http://www.letu.ru/uhod-za-kozhei/dlya-muzhchin/dezodoranty-antiperspiranty?viewAll=true' => [
                'Уход за кожей',
                'Для мужчин',
                'Дезодоранты-антиперспиранты',
            ],
            'http://www.letu.ru/uhod-za-kozhei/dlya-volos/shampuni-i-konditsionery?viewAll=true' => [
                'Уход за кожей',
                'Для волос',
                'Шампуни и кондиционеры',
            ],
            'http://www.letu.ru/uhod-za-kozhei/dlya-volos/dlya-ukladki?viewAll=true' => [
                'Уход за кожей',
                'Для волос',
                'Для укладки',
            ],
            'http://www.letu.ru/uhod-za-kozhei/dlya-volos/spetsialnye-sredstva?viewAll=true' => [
                'Уход за кожей',
                'Для волос',
                'Специальные средства',
            ],
            'http://www.letu.ru/uhod-za-kozhei/dlya-volos/kraska-dlya-volos?viewAll=true' => [
                'Уход за кожей',
                'Для волос',
                'Краска для волос',
            ],
            'http://www.letu.ru/uhod-za-kozhei/gigiena?viewAll=true' => [
                'Уход за кожей',
                'Гигиена',
                '',
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
        if(array_key_exists($link, $this->link)) {
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
        if(array_key_exists($link, $this->link)) {
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
        if(array_key_exists($link, $this->link)) {
            $result = $this->link[$link][2];
        }

        return $result;
    }
}