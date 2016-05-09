<?php

namespace app\src\Parser\Response\Product;

use app\src\Parser\Response\ParserInterface;
use app\src\Parser\Response\Response;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 */

class RivegaucheParser implements ParserInterface
{
    /** @var Crawler $response */
    private $response;
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
     * @return mixed
     */
    public function getReason()
    {
        $errorReason = $this->response->filter('div.productIsDeleted')->each(function ($node) {
            /** @var Crawler $node */
            return $node->text();
        });

        return reset($errorReason);
    }

    /**
     * Метод получения объекта Response с данными извлеченными после парсинга
     *
     * @return Response
     */
    public function getResponse()
    {
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

        if (!empty($price['gold_price'])) {
            $result->setGoldPrice($price['gold_price']);
        }

        if (!empty($price['blue_price'])) {
            $result->setBluePrice($price['blue_price']);
        }

        if (!empty($price['price'])) {
            $result->setPrice($price['price']);
        }

        return $result;
    }

    /**
     * Метод получения Title
     *
     * @return string
     */
    public function getTitle()
    {

        $title = $this->response->filter('div.es_product div.es_right_full_name h1')->each(function ($node) {
            /** @var Crawler $node */
            return $node->text();
        });

        if (empty(reset($title))) {
            $title_name = $this->response->filter('div.es_product div.es_right h1')->each(function ($node) {
                /** @var Crawler $node */
                return $node->text();
            });

            $title_desc = $this->response->filter('div.es_product div.es_right_full_name')->each(function ($node) {
                /** @var Crawler $node */
                return $node->text();
            });

            $title = trim(sprintf('%s %s', reset($title_name), reset($title_desc)));

            if (!empty(trim($title))) {
                return $title;
            } else {
                $title = [];
            }
        }

        if (empty(reset($title))) {
            $title = $this->response->filter('div.es_product div.product-name h1')->each(
                function ($node) {
                    /** @var Crawler $node */
                    return $node->text();
                }
            );
        }
        if (empty(reset($title))) {
            $title = $this->response->filter('div.es_personal_body h1')->each(
                function ($node) {
                    /** @var Crawler $node */
                    return $node->text();
                }
            );
        }

        return reset($title);
    }

    /**
     * Метод получения Артикула
     *
     * @return string
     */
    public function getArticle()
    {
        preg_match('/[0-9]+$/i', $this->link, $data);
        $article = $data[0];

        return $article;
    }

    /**
     * Метод получения Бренда может быть пустым т.к. не все заполняется на сайтах
     *
     * @return string
     */
    public function getBrand()
    {
        $brand = $this->response->filter('div.es_product div.es_right_lable img')->each(function ($node) {
            /** @var Crawler $node */
            $brand = $node->attr('alt');
            $brand = str_replace(' Logo Image', '', $brand);
            $brand = trim($brand);

            return $brand;
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
        $description = $this->response->filter('div.es_product div.es_right_param div.es_right_price_type')->each(
            function ($node) {
                /** @var Crawler $node */
                $node->text();
                $description = trim($node->text());
                $description = str_replace(' ', '', $description);
                $description = str_replace('*', '', $description);
                $description = nl2br($description);
                $description = str_replace('<br />', '', $description);
                $description = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $description);
                $description = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $description);

                return $description;
            }
        );

        if (empty(reset($description))) {
            $description = $this->response->filter('div.es_product div.prod_add_to_cart td.leftalign')->each(
                function ($node) {
                    /** @var Crawler $node */
                    $node->text();
                    $description = trim($node->text());
                    $description = str_replace(' ', '', $description);
                    $description = str_replace('*', '', $description);
                    $description = nl2br($description);
                    $description = str_replace('<br />', '', $description);
                    $description = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $description);
                    $description = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $description);

                    return $description;
                }
            );
        }

        if (empty(reset($description))) {
            $description = $this->response->filter('div.es_product div.es_right_price div.es_right_price_type')->each(
                function ($node) {
                    /** @var Crawler $node */
                    $node->text();
                    $description = trim($node->text());
                    $description = str_replace(' ', '', $description);
                    $description = str_replace('*', '', $description);
                    $description = nl2br($description);
                    $description = str_replace('<br />', '', $description);
                    $description = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $description);
                    $description = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $description);

                    return $description;
                }
            );
        }

        if (empty(reset($description))) {
            $description = $this->response->filter('div.es_product div.product-col1 div.product-name')->each(
                function ($node) {
                    /** @var Crawler $node */
                    $node->text();
                    $description = trim($node->text());
                    $description = str_replace(' ', '', $description);
                    $description = str_replace('*', '', $description);
                    $description = nl2br($description);
                    $description = str_replace('<br />', '', $description);
                    $description = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $description);
                    $description = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $description);

                    return $description;
                }
            );
        }

        return reset($description);
    }

    /**
     * Метод получения url картинки
     *
     * @return string
     */
    public function getImageLink()
    {
        $imageLink = $this->response->filter('div.es_product div#primary_image img')->each(
            function ($node) {
                /** @var Crawler $node */
                return $node->attr('src');
            }
        );

        return reset($imageLink);
    }
    /**
     * Метод возвращает наличие акции
     *
     * @return bool
     */
    public function getShowcasesNew()
    {
        $showcasesNew = $this->response->filter('div.es_product div.showcases_new')->each(function ($node) {
            return $node;
        });

        return empty(reset($showcasesNew)) ? false : true;
    }

    /**
     * Метод возвращает наличие акции
     *
     * @return bool
     */
    public function getShowcasesBest()
    {
        return false;
    }

    /**
     * Метод возвращает наличие акции
     *
     * @return bool
     */
    public function getShowcasesBestsellers()
    {
        $showcasesBestsellers = $this->response->filter('div.es_product div.showcases_bestsellers')->each(
            function ($node) {
                return $node;
            }
        );

        return empty(reset($showcasesBestsellers)) ? false : true;
    }

    /**
     * Метод возвращает наличие акции
     *
     * @return bool
     */
    public function getShowcasesCompliment()
    {
        $showcasesCompliment = $this->response->filter('div.es_product div.showcases_compliment')->each(
            function ($node) {
                return $node;
            }
        );

        return empty(reset($showcasesCompliment)) ? false : true;
    }

    /**
     * Метод возвращает наличие акции
     *
     * @return bool
     */
    public function getShowcasesExclusive()
    {
        $showcasesExclusive = $this->response->filter('div.es_product div.showcases_exclusive')->each(
            function ($node) {
                return $node;
            }
        );

        return empty(reset($showcasesExclusive)) ? false : true;
    }

    /**
     * Метод возвращает наличие акции
     *
     * @return bool
     */
    public function getShowcasesExpertiza()
    {
        $showcasesExpertiza = $this->response->filter('div.es_product div.showcases_expertiza')->each(
            function ($node) {
                return $node;
            }
        );

        return empty(reset($showcasesExpertiza)) ? false : true;
    }

    /**
     * Метод возвращает наличие акции
     *
     * @return bool
     */
    public function getShowcasesLimit()
    {
        return false;
    }

    /**
     * Метод возвращает наличие акции
     *
     * @return bool
     */
    public function getShowcasesOffer()
    {
        $showcasesOffer = $this->response->filter('div.es_product div.showcases_offer')->each(
            function ($node) {
                return $node;
            }
        );

        return empty(reset($showcasesOffer)) ? false : true;
    }

    /**
     * Метод возвращает наличие акции
     *
     * @return bool
     */
    public function getShowcasesSale()
    {
        return false;
    }

    /**
     * Метод получения Списка цветов или объемов
     *
     * @return array
     */
    public function getUrls()
    {
        $urls = $this->response->filter('div.es_product div.es_right_price ul a')->each(function ($node) {
            /** @var Crawler $node */
            return 'http://shop.rivegauche.ru'.$node->attr('href');
        });

        if (empty($urls)) {
            $urls = $this->response->filter('div.es_product div.all-colors ul a')->each(function ($node) {
                /** @var Crawler $node */
                return 'http://shop.rivegauche.ru'.$node->attr('href');
            });
        }

        if (empty($urls)) {
            $urls = $this->response->filter('div.es_product div.es_right ul a')->each(function ($node) {
                /** @var Crawler $node */
                return 'http://shop.rivegauche.ru'.$node->attr('href');
            });
        }

        return $urls;
    }

    /**
     * Метод получения Цен
     *
     * @return array
     */
    public function getPrice()
    {
        //print_r(123);die;
        $price = $this->response->filter('div.es_product div.prod_add_to_cart')->each(function ($node) {
            /** @var Crawler $node */
            $goldPrice = $node->filter('span.gold_price')->each(function ($subNode) {
                /** @var Crawler $subNode */
                return $this->clearPrice($subNode->text());
            });
            $bluePrice = $node->filter('span.blue_price')->each(function ($subNode) {
                /** @var Crawler $subNode */
                return $this->clearPrice($subNode->text());
            });
            $price = $node->filter('div.card-price span.price')->each(function ($subNode) {
                /** @var Crawler $subNode */
                return $this->clearPrice($subNode->text());
            });
            $fixPrice = $node->filter('div.fix-price')->each(function ($subNode) {
                /** @var Crawler $subNode */
                return $this->clearPrice($subNode->text());
            });

            if (empty(reset($price))) {
                $price = $node->filter('span.price')->each(function ($subNode) {
                    /** @var Crawler $subNode */
                    return $this->clearPrice($subNode->text());
                });
            }

            return [
                'gold_price' => reset($goldPrice),
                'blue_price' => reset($bluePrice),
                'price' => (!empty(reset($price))) ? reset($price) : reset($fixPrice),
            ];
        });

        $priceBase = reset($price);

        if (empty($priceBase['price'])) {
            $price = $this->response->filter('div.es_product div.product-price')->each(function ($node) {
                /** @var Crawler $node */
                $goldPrice = $node->filter('span.gold_price')->each(function ($subNode) {
                    /** @var Crawler $subNode */
                    return $this->clearPrice($subNode->text());
                });
                $bluePrice = $node->filter('span.blue_price')->each(function ($subNode) {
                    /** @var Crawler $subNode */
                    return $this->clearPrice($subNode->text());
                });
                $price = $node->filter('div.card-price span.price')->each(function ($subNode) {
                    /** @var Crawler $subNode */
                    return $this->clearPrice($subNode->text());
                });

                if (empty(reset($price))) {
                    $price = $node->filter('span.price')->each(function ($subNode) {
                        /** @var Crawler $subNode */
                        return $this->clearPrice($subNode->text());
                    });
                }
                /*
                if (empty(reset($price))) {

                    $price = str_replace('Цена:', '', $node->text());
                    $price = trim($price);
                    $price = $this->clearPrice($price);
                }

                if (empty($price) || (is_array($price) && empty(reset($price)))) {
                    $fixPrice = $node->filter('div.fix-price')->each(
                        function ($subNode) {

                            return $this->clearPrice($subNode->text());
                        }
                    );
                    $goldPrice = $node->filter('div.base-price')->each(function ($subNode) {

                        return $this->clearPrice($subNode->text());
                    });
                }*/

                return [
                    'gold_price' => reset($goldPrice),
                    'blue_price' => reset($bluePrice),
                    'price' => reset($price),
                    //'price' => (!empty(reset($price))) ? reset($price) : reset($fixPrice),
                ];
            });
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
    private function clearPrice($price = 0)
    {
        $price = str_replace(' Р', '', $price);
        $price = str_replace(' ', '', $price);
        $price = trim($price);
        $price = str_replace(' ', '', $price);
        $price = str_replace('*', '', $price);
        $price = str_replace('\r', '', $price);
        $price = str_replace('\n', '', $price);
        $price = nl2br($price);
        $price = str_replace('<br />', '', $price);
        $price = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $price);
        $price = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $price);

        return (int)$price;
    }
}
