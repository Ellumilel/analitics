<?php

namespace app\src\Parser\Response\Product;

use app\src\Parser\Response\ParserInterface;
use app\src\Parser\Response\Response;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 */
class IledebeauteParser implements ParserInterface
{
    /** @var Crawler $response */
    private $response;
    /** @var Crawler $subResponse */
    private $subResponse;
    /** @var Crawler $subItems */
    private $subItems;
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
        $result = $this->response->filter('div.i-container div.i-wrapper')->each(
            function ($node) {
                $this->subResponse = $node;

                $subData[] = $this->subResponse->filter('tr.b-showcase__item')->each(
                    function ($node) {
                        $this->subItems = $node;
                        $result = (new Response())
                            ->setArticle($this->getArticle())
                            ->setBrand($this->getBrand())
                            ->setDescription($this->getDescription())
                            ->setTitle($this->getTitle())
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
                            ->setLink($this->link);

                        $price = $this->getPrice();
                        $result->setPrice($price['oldPrice']);
                        $result->setNewPrice($price['newPrice']);

                        return $result;
                    }
                );

                if (empty(reset($subData))) {
                    $subData = [];
                    $subData[] = $this->subResponse->filter('tr.b-cart__showcase__item')->each(
                        function ($node) {
                            $this->subItems = $node;
                            $result = (new Response())
                                ->setArticle($this->getArticle())
                                ->setBrand($this->getBrand())
                                ->setDescription($this->getDescription())
                                ->setTitle($this->getTitle())
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
                                ->setLink($this->link);

                            $price = $this->getPrice();
                            $result->setPrice($price['oldPrice']);
                            $result->setNewPrice($price['newPrice']);

                            return $result;
                        }
                    );
                }

                if (!empty(reset($subData))) {
                    return reset($subData);
                } else {
                    \Yii::error(
                        sprintf('Ошибка обработки Result Article пустой: %s ', $this->link),
                        'cron'
                    );

                    return [];
                }
            }
        );

        return reset($result);
    }

    /**
     * Метод получения Title
     *
     * @return string
     */
    public function getTitle()
    {
        $title = $this->subResponse->filter('div.b-product__item__promo__title h1')->each(
            function ($node) {
                /** @var Crawler $node */
                return $this->clearTitle($node->text());
            }
        );

        if (empty($title)) {
            $title = $this->subResponse->filter('div.b-product__item__item__title h1')->each(
                function ($node) {
                    /** @var Crawler $node */
                    return $this->clearTitle($node->text());
                }
            );
        }

        if (empty($title)) {
            $title = $this->subResponse->filter('p.title')->each(
                function ($node) {
                    /** @var Crawler $node */
                    return $this->clearTitle($node->text());
                }
            );
        }

        return (is_array($title)) ? reset($title) : '';
    }

    /**
     * Метод получения Артикула
     *
     * @return string
     */
    public function getArticle()
    {
        $article = $this->subItems->filter('td.dummy a')->each(
            function ($node) {
                /** @var Crawler $node */
                $article = trim($node->attr('name'));
                $article = preg_replace("/[^0-9]/", "", $article);

                return $article;
            }
        );

        if (empty($article)) {
            $article = $this->subItems->filter('td.b-cart__showcase__item__img a')->each(
                function ($node) {
                    /** @var Crawler $node */
                    $article = trim($node->attr('name'));
                    $article = preg_replace("/[^0-9]/", "", $article);

                    return $article;
                }
            );
        }

        return (is_array($article)) ? reset($article) : '';
    }

    /**
     * Метод получения Бренда
     *
     * @return string
     */
    public function getBrand()
    {
        $brand = $this->subResponse->filter('div.b-product__item__promo__logo a')->each(
            function ($node) {
                /** @var Crawler $node */
                $brand = $node->attr('href');
                $brand = str_replace('/shop/brands/', '', $brand);
                $brand = str_replace('/', '', $brand);

                return $brand;
            }
        );
        if (empty($brand)) {
            $brand = $this->subResponse->filter('div.b-product__item__item__logo a')->each(
                function ($node) {
                    /** @var Crawler $node */
                    $brand = $node->attr('href');
                    $brand = str_replace('/shop/brands/', '', $brand);
                    $brand = str_replace('/', '', $brand);

                    return $brand;
                }
            );
        }

        if (empty($brand)) {
            $brand = $this->subResponse->filter('a.title__brand')->each(
                function ($node) {
                    /** @var Crawler $node */
                    $brand = $node->text();
                    $brand = trim($brand);

                    return $this->clearBrand($brand);
                }
            );
        }

        return (is_array($brand)) ? strtoupper(reset($brand)) : '';
    }

    /**
     * Метод получения Описания
     *
     * @return string
     */
    public function getDescription()
    {
        $description = $this->subItems->filter('td.b-showcase__item__descr p.b-showcase__item__link')->each(
            function ($node) {
                /** @var Crawler $node */
                $description = trim($node->text());
                //$description = str_replace(' ', '', $description);
                $description = str_replace('*', '', $description);
                $description = str_replace('\r', '', $description);
                $description = str_replace('\n', '', $description);
                $description = str_replace('	', '', $description);
                $description = nl2br($description);
                $description = str_replace('<br />', '', $description);
                $description = str_replace(["\r\n", "\r", "\n", "\t"], '', $description);

                return (string)$description;
            }
        );

        if (empty($description)) {
            $description = $this->subItems->filter(
                'tr.b-cart__showcase__item p.b-cart__showcase__item__brand'
            )->each(
                function ($node) {
                    /** @var Crawler $node */
                    $description = trim($node->text());
                    //$description = str_replace(' ', '', $description);
                    $description = str_replace('*', '', $description);
                    $description = str_replace('\r', '', $description);
                    $description = str_replace('\n', '', $description);
                    $description = str_replace('	', '', $description);
                    $description = nl2br($description);
                    $description = str_replace('<br />', '', $description);
                    $description = str_replace(["\r\n", "\r", "\n", "\t"], '', $description);

                    return (string)$description;
                }
            );
        }

        return (is_array($description)) ? reset($description) : '';
    }

    /**
     * Метод получения Ссылки на картинку
     *
     * @return string
     */
    public function getImageLink()
    {
        $image = $this->subItems->filter('td.b-showcase__item__img img')->each(
            function ($node) {
                /** @var Crawler $node */
                return 'http:'.$node->attr('src');
            }
        );

        if (empty($image)) {
            $image = $this->subItems->filter('td.b-cart__showcase__item__img img')->each(
                function ($node) {
                    /** @var Crawler $node */
                    return 'http:'.$node->attr('src');
                }
            );
        }

        return (is_array($image)) ? reset($image) : '';
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesNew()
    {
        $showcasesNew = $this->subItems->filter('td.b-showcase__item__tags span.new')->each(
            function ($node) {
                /** @var Crawler $node */
                return $node->text();
            }
        );
        if (empty($showcasesNew)) {
            $showcasesNew = $this->subItems->filter('p.b-showcase__item__tags span.new')->each(
                function ($node) {
                    /** @var Crawler $node */
                    return $node->text();
                }
            );
        }

        return empty(reset($showcasesNew)) ? false : true;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesBest()
    {
        $showcasesBest = $this->subItems->filter('td.b-showcase__item__tags span.best')->each(
            function ($node) {
                /** @var Crawler $node */
                return $node->text();
            }
        );
        if (empty($showcasesBest)) {
            $showcasesBest = $this->subItems->filter('p.b-showcase__item__tags span.best')->each(
                function ($node) {
                    /** @var Crawler $node */
                    return $node->text();
                }
            );
        }

        return empty(reset($showcasesBest)) ? false : true;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesBestsellers()
    {
        $showcasesBestseller = $this->subItems->filter('td.b-showcase__item__tags span.bestseller')->each(
            function ($node) {
                /** @var Crawler $node */
                return $node->text();
            }
        );
        if (empty($showcasesBestseller)) {
            $showcasesBestseller = $this->subItems->filter('p.b-showcase__item__tags span.bestseller')->each(
                function ($node) {
                    /** @var Crawler $node */
                    return $node->text();
                }
            );
        }

        return empty(reset($showcasesBestseller)) ? false : true;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesCompliment()
    {
        $showcasesBest = $this->subItems->filter('td.b-showcase__item__tags span.compiment')->each(
            function ($node) {
                /** @var Crawler $node */
                return $node->text();
            }
        );

        return empty(reset($showcasesBest)) ? false : true;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesExclusive()
    {
        $showcasesExclusive = $this->subItems->filter('td.b-showcase__item__tags span.exclusive')->each(
            function ($node) {
                /** @var Crawler $node */
                return $node->text();
            }
        );
        if (empty($showcasesExclusive)) {
            $showcasesExclusive = $this->subItems->filter('p.b-showcase__item__tags span.exclusive')->each(
                function ($node) {
                    /** @var Crawler $node */
                    return $node->text();
                }
            );
        }

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
        $showcasesLimit = $this->subItems->filter('td.b-showcase__item__tags span.limit')->each(
            function ($node) {
                /** @var Crawler $node */
                return $node->text();
            }
        );

        if (empty($showcasesLimit)) {
            $showcasesLimit = $this->subItems->filter('p.b-showcase__item__tags span.limit')->each(
                function ($node) {
                    /** @var Crawler $node */
                    return $node->text();
                }
            );
        }

        return empty(reset($showcasesLimit)) ? false : true;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesOffer()
    {
        return false;
    }

    /**
     * Метод получения Описания
     *
     * @return bool
     */
    public function getShowcasesSale()
    {
        $showcasesSale = $this->subItems->filter('td.b-showcase__item__tags span.sale')->each(
            function ($node) {
                /** @var Crawler $node */
                return $node->text();
            }
        );
        if (empty($showcasesSale)) {
            $showcasesSale = $this->subItems->filter('p.b-showcase__item__tags span.sale')->each(
                function ($node) {
                    /** @var Crawler $node */
                    return $node->text();
                }
            );
        }

        return empty(reset($showcasesSale)) ? false : true;
    }

    /**
     * Метод получения Цен
     *
     * @return number
     */
    public function getPrice()
    {
        $price = $this->subItems->filter('td.b-showcase__item__price')->each(
            function ($node) {
                /** @var Crawler $node */
                $oldPrice = $node->filter('span.old')->each(
                    function ($sub) {
                        /** @var Crawler $sub */
                        return $sub->text();
                    }
                );
                $newPrice = $node->filter('span.new')->each(
                    function ($sub) {
                        /** @var Crawler $sub */
                        return $sub->text();
                    }
                );

                if (empty(reset($oldPrice)) && empty(reset($newPrice))) {
                    $newPrice = $node->filter('div.vip_price span.def')->each(
                        function ($sub) {
                            /** @var Crawler $sub */
                            return $sub->text();
                        }
                    );
                    $oldPrice = $node->filter('div.full_price span.def')->each(
                        function ($sub) {
                            /** @var Crawler $sub */
                            return $sub->text();
                        }
                    );
                }

                $newPrice = $this->clearPrice($newPrice);
                $oldPrice = $this->clearPrice($oldPrice);

                return [
                    'oldPrice' => (empty($oldPrice)) ? $newPrice : $oldPrice,
                    'newPrice' => $newPrice,
                ];
            }
        );

        if (empty($price)) {
            $price = $this->subItems->filter('td.b-cart__showcase__item__price')->each(
                function ($node) {
                    /** @var Crawler $node */
                    $oldPrice = $node->filter('span.old')->each(
                        function ($sub) {
                            /** @var Crawler $sub */
                            return $sub->text();
                        }
                    );
                    $newPrice = $node->filter('span.new')->each(
                        function ($sub) {
                            /** @var Crawler $sub */
                            return $sub->text();
                        }
                    );

                    if (empty(reset($oldPrice)) && empty(reset($newPrice))) {
                        $newPrice = $node->filter('div.vip_price span.def')->each(
                            function ($sub) {
                                /** @var Crawler $sub */
                                return $sub->text();
                            }
                        );
                        $oldPrice = $node->filter('div.full_price span.def')->each(
                            function ($sub) {
                                /** @var Crawler $sub */
                                return $sub->text();
                            }
                        );
                    }

                    $newPrice = $this->clearPrice($newPrice);
                    $oldPrice = $this->clearPrice($oldPrice);

                    return [
                        'oldPrice' => (empty($oldPrice)) ? $newPrice : $oldPrice,
                        'newPrice' => $newPrice,
                    ];
                }
            );
        }

        return reset($price);
    }

    /**
     * Чистит цену для РивГош
     *
     * @param $price
     *
     * @return int
     */
    private function clearPrice($price)
    {
        $price = reset($price);
        $price = str_replace(' руб.', '', $price);
        $price = str_replace(' ', '', $price);
        $price = trim($price);
        $price = str_replace(' ', '', $price);
        $price = str_replace('*', '', $price);
        $price = str_replace('\r', '', $price);
        $price = str_replace('\n', '', $price);
        $price = nl2br($price);
        $price = str_replace('<br />', '', $price);
        $price = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $price);
        $price = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $price);

        return (int)$price;
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

    /**
     * Чистит заголовок
     *
     * @param array $title
     *
     * @return string
     */
    private function clearTitle($title)
    {
        if (is_array($title)) {
            $title = reset($title);
        }
        $title = str_replace('\r', '', $title);
        $title = str_replace('\n', '', $title);
        $title = str_replace(' ', '', $title);
        $title = str_replace('</span>', '', $title);
        $title = str_replace('<span>', '', $title);
        $title = str_replace('*', '', $title);
        $title = nl2br($title);
        $title = str_replace('<br />', '', $title);
        $title = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $title);
        $title = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $title);
        $title = trim($title);

        return (string)$title;
    }
}
