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

        if (!empty($price['new_price'])) {
            $result->setNewPrice($price['new_price']);
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
        $title = $this->response->filter('div.product-card div.product-card__name h1')->each(function ($node) {
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

        if (!empty($data[0])) {
            $article = $data[0];
        } else {
            $article = null;
        }

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
        $description = $this->response->filter('div.product-card div.product-card__name div.product-card__cat')->each(
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

        if (!empty($description)) {
            $description = reset($description);
        }

        $descriptionList = $this->response->filter('div.product-card div.product-card__options_items a.active')->each(
            function ($node) {
                /** @var Crawler $node */
                $description = trim($node->attr('title'));

                return $description;
            }
        );

        if (!empty($descriptionList[0])) {
            $description .= '| '.$descriptionList[0];
        }

        return $description;
    }

    /**
     * Метод получения url картинки
     *
     * @return string
     */
    public function getImageLink()
    {
        $imageLink = $this->response->filter('div.product-card a.productImagePrimarySimpleLink img')->each(
            function ($node) {
                /** @var Crawler $node */
                return 'https://shop.rivegauche.ru'.$node->attr('src');
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
        $showcasesNew = $this->response->filter('div.product-card a.showcases_new')->each(function ($node) {
            return $node;
        });

        return empty(reset($showcasesNew)) ? 0 : 1;
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
        $showcasesBestsellers = $this->response->filter('div.product-card a.showcases_bestsellers')->each(
            function ($node) {
                return $node;
            }
        );

        return empty(reset($showcasesBestsellers)) ? 0 : 1;
    }

    /**
     * Метод возвращает наличие акции
     *
     * @return bool
     */
    public function getShowcasesCompliment()
    {
        $showcasesCompliment = $this->response->filter('div.product-card a.showcases_compliment')->each(
            function ($node) {
                return $node;
            }
        );

        return empty(reset($showcasesCompliment)) ? 0 : 1;
    }

    /**
     * Метод возвращает наличие акции
     *
     * @return bool
     */
    public function getShowcasesExclusive()
    {
        $showcasesExclusive = $this->response->filter('div.product-card a.showcases_exclusive')->each(
            function ($node) {
                return $node;
            }
        );

        return empty(reset($showcasesExclusive)) ? 0 : 1;
    }

    /**
     * Метод возвращает наличие акции
     *
     * @return bool
     */
    public function getShowcasesExpertiza()
    {
        $showcasesExpertiza = $this->response->filter('div.product-card a.showcases_expertiza')->each(
            function ($node) {
                return $node;
            }
        );

        return empty(reset($showcasesExpertiza)) ? 0 : 1;
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
        $showcasesOffer = $this->response->filter('div.product-card a.showcases_offer')->each(
            function ($node) {
                return $node;
            }
        );

        return empty(reset($showcasesOffer)) ? 0 : 1;
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
        $urls = $this->response->filter('div.product-card div.product-card__options_items a')->each(function ($node) {
            /** @var Crawler $node */
            return 'https://shop.rivegauche.ru'.$node->attr('href');
        });

        if (empty($urls)) {
            $urls = $this->response->filter('div.product-card div.all-colors ul a')->each(function ($node) {
                /** @var Crawler $node */
                return 'https://shop.rivegauche.ru'.$node->attr('href');
            });
        }

        if (empty($urls)) {
            $urls = $this->response->filter('div.product-card div.es_right ul a')->each(function ($node) {
                /** @var Crawler $node */
                return 'https://shop.rivegauche.ru'.$node->attr('href');
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
        $price = $this->response->filter('div.product-card div.product-card__info')->each(function ($node) {
            /** @var Crawler $node */
            /*$goldPrice = $node->filter('span.discount-gold_price')->each(function ($subNode) {

                return $this->clearPrice($subNode->text());
            });

            $bluePrice = $node->filter('span.discount-standard_price')->each(function ($subNode) {

                return $this->clearPrice($subNode->text());
            });*/
            $price = $node->filter('span.product-card__price-box_price')->each(function ($subNode) {
                /** @var Crawler $subNode */
                return $this->clearPrice($subNode->text());
            });


            return [
                'gold_price' => !empty($price[0]) ? $price[0] : null,
                'blue_price' => !empty($price[1]) ? $price[1] : null,
                'price' => !empty($price[2]) ? $price[2] : null,
            ];
        });

        $priceBase = reset($price);

        if (empty($priceBase['price'])) {
            $price = $this->response->filter('div.product-card div.product-card__info')->each(function ($node) {
                /** @var Crawler $node */
                $price = $node->filter('div.product-card__price-box div.product-card__price-box_price')->each(function ($subNode) {
                    /** @var Crawler $subNode */
                    return $this->clearPrice($subNode->text());
                });

                $priceOld = $node->filter('div.product-card__price-box div.product-card__price-box_old-price')->each(function ($subNode) {
                    /** @var Crawler $subNode */
                    return $this->clearPrice($subNode->text());
                });

                return [
                    'gold_price' => 0,
                    'blue_price' => 0,
                    'price' => reset($priceOld),
                    'new_price' => reset($price),
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
