<?php

namespace app\src\Parser\Response;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 */
interface ParserInterface
{
    /**
     * @return string
     */
    public function getTitle();
    /**
     * @return string
     */
    public function getArticle();
    /**
     * @return string
     */
    public function getBrand();
    /**
     * @return string
     */
    public function getDescription();
    /**
     * @return string
     */
    public function getImageLink();
    /**
     * @return bool
     */
    public function getShowcasesNew();
    /**
     * @return bool
     */
    public function getShowcasesBest();
    /**
     * @return bool
     */
    public function getShowcasesBestsellers();
    /**
     * @return bool
     */
    public function getShowcasesCompliment();
    /**
     * @return bool
     */
    public function getShowcasesExclusive();
    /**
     * @return bool
     */
    public function getShowcasesExpertiza();
    /**
     * @return bool
     */
    public function getShowcasesLimit();
    /**
     * @return bool
     */
    public function getShowcasesOffer();
    /**
     * @return bool
     */
    public function getShowcasesSale();
    /**
     * @return number
     */
    public function getPrice();
    /**
     * @return array
     */
    public function getUrls();
}
