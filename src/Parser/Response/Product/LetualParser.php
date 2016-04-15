<?php

namespace app\src\Parser\Response\Product;

use app\src\Parser\Response\ParserInterface;
use app\src\Parser\Response\Response;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 */

class LetualParser implements ParserInterface
{
    /** @var Crawler $response */
    private $response;
    /** @var Crawler $response */
    private $subResponse;
    /** @var string */
    private $category;
    /** @var string */
    private $subCategory;
    /** @var string */
    private $group;
    /** @var string */
    private $link;

    /**
     * @param Crawler $response
     * @param string $category
     * @param string $subCategory
     * @param string $group
     * @param string $link
     */
    public function __construct(Crawler $response, $category, $subCategory, $group, $link)
    {
        $this->response = $response;

        $this->category = $category;
        $this->subCategory = $subCategory;
        $this->group = $group;
        $this->link = $link;
    }

    /**
     * Метод получения объекта Response с данными извлеченными после парсинга
     *
     * @return array
     */
    public function getResponse()
    {
        $result[] = $this->response->filter('table.atg_store_productSummary tr')->each(function ($node) {
            $this->subResponse = $node;
            $result = (new Response())
                ->setTitle($this->getTitle())
                ->setArticle($this->getArticle())
                ->setBrand($this->getBrand())
                ->setDescription($this->getDescription())
                ->setImageLink($this->getImageLink())
                ->setShowcasesNew($this->getShowcasesNew())
                ->setShowcasesBest($this->getShowcasesBest())
                ->setShowcasesBestsellers($this->getShowcasesBestsellers())
                ->setShowcasesCompliment($this->getShowcasesCompliment())
                ->setShowcasesExclusive($this->getShowcasesExclusive())
                ->setShowcasesExpertiza($this->getShowcasesExpertiza())
                ->setShowcasesLimit($this->getShowcasesLimit())
                ->setShowcasesOffer($this->getShowcasesOffer())
                ->setShowcasesSale($this->getShowcasesSale())
                ->setUrls($this->getUrls())
                ->setCategory($this->category)
                ->setSubCategory($this->subCategory)
                ->setGroup($this->group)
                ->setLink($this->link)
            ;
            $price = $this->getPrice();

            if (!empty($price['newPrice'])) {
                $result->setNewPrice($price['newPrice']);
            }

            if (!empty($price['oldPrice'])) {
                $result->setPrice($price['oldPrice']);
            }
            if (!empty($result->getArticle())) {
                return $result;
            } else {
                \Yii::error(
                    sprintf('Ошибка обработки Result Article пустой: %s ', $this->link),
                    'cron'
                );
                return [];
            }
        });

        return reset($result);
    }

    /**
     * Метод получения Title
     *
     * @return string
     */
    public function getTitle()
    {
        $title = $this->subResponse->filter('tr td h2')->each(function ($node) {
            /** @var Crawler $node */
            return $node->text();
        });

        return reset($title);
    }

    /**
     * Метод получения Артикула
     *
     * @return string
     */
    public function getArticle()
    {
        $article = $this->subResponse->filter('td p.article')->each(function ($node) {
            /** @var Crawler $node */
            $article = trim($node->text());
            $article = str_replace('Артикул ', '', $article);
            $article = preg_replace("/[^a-zA-Z0-9]/", "", $article);

            return $article;
        });

        return reset($article);
    }

    /**
     * Метод получения Бренда
     *
     * @return string
     */
    public function getBrand()
    {
        $brand = $this->response->filter('#brandImage')->each(function ($node) {
            /** @var Crawler $node */
            $brand = $node->attr('alt');
            $brand = trim($brand);

            return $this->clearBrand($brand);
        });

        return reset($brand);
    }

    /**
     * Метод получения Описания
     *
     * @return string
     */
    public function getDescription()
    {
        $description = $this->subResponse->filter('td p.description')->each(function ($node) {
            /** @var Crawler $node */
            $description = trim($node->text());
            return $description;
        });

        return reset($description);
    }

    /**
     * Метод получения Описания
     *
     * @return string
     */
    public function getImageLink()
    {
        $imageLink = $this->subResponse->filter('td img')->each(function ($node) {
            /** @var Crawler $node */
            return 'http://www.letu.ru'.$node->attr('src');
        });

        return reset($imageLink);
    }
    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesNew()
    {
        $showcasesNew = $this->subResponse->filter('td ul.markers li img')->each(
            function ($node) {
                /** @var Crawler $node */
                if ($node->attr('alt') == 'Новенькое') {
                    return $node->attr('alt');
                }
                return '';
            }
        );

        return empty(reset($showcasesNew)) ? false : true;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesBest()
    {
        $article = '';

        return $article;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesBestsellers()
    {
        $showcasesBestsellers = $this->subResponse->filter('td ul.markers li img')->each(
            function ($node) {
                /** @var Crawler $node */
                if ($node->attr('alt') == 'Бестселлеры') {
                    return $node->attr('alt');
                }
                return '';
            }
        );

        return empty(reset($showcasesBestsellers)) ? false : true;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesCompliment()
    {
        $article = '';

        return $article;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesExclusive()
    {
        $showcasesExclusive = $this->subResponse->filter('td ul.markers li img')->each(
            function ($node) {
                /** @var Crawler $node */
                if ($node->attr('alt') == "Только в Л'Этуаль") {
                    return $node->attr('alt');
                }
                return '';
            }
        );

        return empty(reset($showcasesExclusive)) ? false : true;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesExpertiza()
    {
        $article = '';

        return $article;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesLimit()
    {
        $showcasesLimit = $this->subResponse->filter('td ul.markers li img')->each(
            function ($node) {
                /** @var Crawler $node */
                if ($node->attr('alt') == 'Лимитированные издания') {
                    return $node->attr('alt');
                }
                return '';
            }
        );

        return empty(reset($showcasesLimit)) ? false : true;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesOffer()
    {
        $article = '';

        return $article;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesSale()
    {
        $article = '';

        return $article;
    }

    /**
     * Метод получения Цен
     *
     * @return number
     */
    public function getPrice()
    {
        $price = $this->subResponse->filter('td.price')->each(function ($node) {
            /** @var Crawler $node */
            $oldPrice = $node->filter('p.old_price')->each(function ($subsNode) {
                /** @var Crawler $subsNode */
                return $subsNode->text();
            });

            $priceNoDiscount = $node->filter('p.price_no_discount')->each(function ($subsNode) {
                /** @var Crawler $subsNode */
                return $subsNode->text();
            });
            $newPrice = $node->filter('p.new_price')->each(function ($subsNode) {
                /** @var Crawler $subsNode */
                return $subsNode->text();
            });

            $newPrice = trim(reset($newPrice));
            $newPrice = str_replace(' ', '', $newPrice);
            $newPrice = str_replace('*', '', $newPrice);
            $newPrice = str_replace('\r', '', $newPrice);
            $newPrice = str_replace('\n', '', $newPrice);

            $oldPrice = trim(reset($oldPrice));
            $oldPrice = str_replace(' ', '', $oldPrice);
            $oldPrice = str_replace('*', '', $oldPrice);
            $oldPrice = str_replace('\r', '', $oldPrice);
            $oldPrice = str_replace('\n', '', $oldPrice);

            if (empty($oldPrice)) {
                $oldPrice = $priceNoDiscount;

                $oldPrice = trim(reset($oldPrice));
                $oldPrice = str_replace(' ', '', $oldPrice);
                $oldPrice = str_replace('*', '', $oldPrice);
                $oldPrice = str_replace('\r', '', $oldPrice);
                $oldPrice = str_replace('\n', '', $oldPrice);
            }

            return [
                'oldPrice' => $oldPrice,
                'newPrice' => $newPrice,
            ];
        });

        return reset($price);
    }

    /**
     * Метод получения Списка цветов или объемов
     *
     * @return array
     */
    public function getUrls()
    {
        return [];
    }

    /**
     * В рамках получения брендов пытаемся устранить косяки с заполнением
     * и наименование брендов
     *
     * @param string $brand
     *
     * @return string
     */
    private function clearBrand($brand)
    {
        switch ($brand) {
            case 'DOLCE&GABBANA':
                $brand = 'DOLCE & GABBANA';
                break;
            case 'DOLCE & GABBANA MAKE UP':
                $brand = 'DOLCE & GABBANA';
                break;
            case 'Dolce&Gabbana':
                $brand = 'DOLCE & GABBANA';
                break;
            case "L`OREAL PARIS":
                $brand = 'LOREAL';
                break;
            case "YES TO...":
                $brand = 'YES TO';
                break;
            case "DSQUARED2":
                $brand = 'DSQUARED';
                break;
            case "COLOR MASK":
                $brand = 'SCHWARZKOPF';
                break;
            case "GLISS KUR":
                $brand = 'SCHWARZKOPF';
                break;
            case "GOT2B":
                $brand = 'SCHWARZKOPF';
                break;
            case "MILLION COLOR":
                $brand = 'SCHWARZKOPF';
                break;
            case "PALETTE":
                $brand = 'SCHWARZKOPF';
                break;
            case "PERFECT MOUSSE":
                $brand = 'SCHWARZKOPF';
                break;
            case "TAFT":
                $brand = 'SCHWARZKOPF';
                break;
            case "TSUBAKI":
                $brand = 'SHISEIDO';
                break;
        }

        $brand = strtoupper($brand);
        return $brand;
    }
}
