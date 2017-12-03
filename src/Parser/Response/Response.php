<?php

namespace app\src\Parser\Response;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 */
class Response
{
    /** @var string */
    private $title;
    /** @var string */
    private $article;
    /** @var string */
    private $article_new;
    /** @var string */
    private $link;
    /** @var string */
    private $group;
    /** @var string */
    private $category;
    /** @var string */
    private $subCategory;
    /** @var string */
    private $brand;
    /** @var string */
    private $description;
    /** @var string */
    private $imageLink;
    /** @var array */
    private $urls;

    /** @var bool */
    private $showcasesNew = false;
    /** @var bool */
    private $showcasesCompliment = false;
    /** @var bool */
    private $showcasesOffer = false;
    /** @var bool */
    private $showcasesExclusive = false;
    /** @var bool */
    private $showcasesBestsellers = false;
    /** @var bool */
    private $showcasesExpertiza = false;
    /** @var bool */
    private $showcasesLimit = false;
    /** @var bool */
    private $showcasesSale = false;
    /** @var bool */
    private $showcasesBest = false;
    /** @var string */
    private $showcasesPromotext;

    /** @var number */
    private $goldPrice;
    /** @var number */
    private $bluePrice;
    /** @var number */
    private $newPrice;
    /** @var number */
    private $price;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param string $article
     *
     * @return $this
     */
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     *
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $group
     *
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     *
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubCategory()
    {
        return $this->subCategory;
    }

    /**
     * @param string $subCategory
     *
     * @return $this
     */
    public function setSubCategory($subCategory)
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     *
     * @return $this
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageLink()
    {
        return $this->imageLink;
    }

    /**
     * @param string $imageLink
     *
     * @return $this
     */
    public function setImageLink($imageLink)
    {
        $this->imageLink = $imageLink;

        return $this;
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * @param array $urls
     *
     * @return $this
     */
    public function setUrls(array $urls)
    {
        $this->urls = $urls;

        return $this;
    }

    /**
     * @return bool
     */
    public function getShowcasesNew()
    {
        return $this->showcasesNew;
    }

    /**
     * @param bool $showcasesNew
     *
     * @return $this
     */
    public function setShowcasesNew($showcasesNew)
    {
        $this->showcasesNew = $showcasesNew;

        return $this;
    }

    /**
     * @return bool
     */
    public function getShowcasesCompliment()
    {
        return $this->showcasesCompliment;
    }

    /**
     * @param bool $showcasesCompliment
     *
     * @return $this
     */
    public function setShowcasesCompliment($showcasesCompliment)
    {
        $this->showcasesCompliment = $showcasesCompliment;

        return $this;
    }

    /**
     * @return bool
     */
    public function getShowcasesOffer()
    {
        return $this->showcasesOffer;
    }

    /**
     * @param bool $showcasesOffer
     *
     * @return $this
     */
    public function setShowcasesOffer($showcasesOffer)
    {
        $this->showcasesOffer = $showcasesOffer;

        return $this;
    }

    /**
     * @return bool
     */
    public function getShowcasesExclusive()
    {
        return $this->showcasesExclusive;
    }

    /**
     * @param bool $showcasesExclusive
     *
     * @return $this
     */
    public function setShowcasesExclusive($showcasesExclusive)
    {
        $this->showcasesExclusive = $showcasesExclusive;

        return $this;
    }

    /**
     * @return bool
     */
    public function getShowcasesBestsellers()
    {
        return $this->showcasesBestsellers;
    }

    /**
     * @param bool $showcasesBestsellers
     *
     * @return $this
     */
    public function setShowcasesBestsellers($showcasesBestsellers)
    {
        $this->showcasesBestsellers = $showcasesBestsellers;

        return $this;
    }

    /**
     * @return bool
     */
    public function getShowcasesExpertiza()
    {
        return $this->showcasesExpertiza;
    }

    /**
     * @param bool $showcasesExpertiza
     *
     * @return $this
     */
    public function setShowcasesExpertiza($showcasesExpertiza)
    {
        $this->showcasesExpertiza = $showcasesExpertiza;

        return $this;
    }

    /**
     * @return bool
     */
    public function getShowcasesLimit()
    {
        return $this->showcasesLimit;
    }

    /**
     * @param bool $showcasesLimit
     *
     * @return $this
     */
    public function setShowcasesLimit($showcasesLimit)
    {
        $this->showcasesLimit = $showcasesLimit;

        return $this;
    }

    /**
     * @return bool
     */
    public function getShowcasesSale()
    {
        return $this->showcasesSale;
    }

    /**
     * @param bool $showcasesSale
     *
     * @return $this
     */
    public function setShowcasesSale($showcasesSale)
    {
        $this->showcasesSale = $showcasesSale;

        return $this;
    }

    /**
     * @return bool
     */
    public function getShowcasesBest()
    {
        return $this->showcasesBest;
    }

    /**
     * @param string $showcasesPromotext
     *
     * @return $this
     */
    public function setShowcasesPromotext($showcasesPromotext)
    {
        $this->showcasesPromotext = $showcasesPromotext;

        return $this;
    }

    /**
     * @return string
     */
    public function getShowcasesPromotext()
    {
        return $this->showcasesPromotext;
    }

    /**
     * @param bool $showcasesBest
     *
     * @return $this
     */
    public function setShowcasesBest($showcasesBest)
    {
        $this->showcasesBest = $showcasesBest;

        return $this;
    }

    /**
     * @return number
     */
    public function getGoldPrice()
    {
        return $this->goldPrice;
    }

    /**
     * @param number $goldPrice
     *
     * @return $this
     */
    public function setGoldPrice($goldPrice)
    {
        $this->goldPrice = $goldPrice;

        return $this;
    }

    /**
     * @return number
     */
    public function getBluePrice()
    {
        return $this->bluePrice;
    }

    /**
     * @param number $bluePrice
     *
     * @return $this
     */
    public function setBluePrice($bluePrice)
    {
        $this->bluePrice = $bluePrice;

        return $this;
    }

    /**
     * @return number
     */
    public function getNewPrice()
    {
        return $this->newPrice;
    }

    /**
     * @param number $newPrice
     *
     * @return $this
     */
    public function setNewPrice($newPrice)
    {
        $this->newPrice = $newPrice;

        return $this;
    }

    /**
     * @return number
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param number $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function toArray()
    {
        $result = [
            'title' => $this->getTitle(),
            'article' => $this->getArticle(),
            'link' => $this->getLink(),
            'group' => $this->getGroup(),
            'category' => $this->getCategory(),
            'sub_category' => $this->getSubCategory(),
            'showcases_new' => (int)$this->getShowcasesNew(),
            'showcases_compliment' => (int)$this->getShowcasesCompliment(),
            'showcases_offer' => (int)$this->getShowcasesOffer(),
            'showcases_exclusive' => (int)$this->getShowcasesExclusive(),
            'showcases_bestsellers' => (int)$this->getShowcasesBestsellers(),
            'showcases_expertiza' => (int)$this->getShowcasesExpertiza(),
        ];

        if (!empty($this->getImageLink())) {
            $result['image_link'] = $this->getImageLink();
        }
        if (!empty($this->getDescription())) {
            $result['description'] = $this->getDescription();
        }
        if (!empty($this->getBrand())) {
            $result['brand'] = $this->getBrand();
        }
        if (!empty($this->getGoldPrice())) {
            $result['gold_price'] = $this->getGoldPrice();
        }

        if (!empty($this->getBluePrice())) {
            $result['blue_price'] = $this->getBluePrice();
        }

        if (!empty($this->getNewPrice())) {
            $result['new_price'] = $this->getNewPrice();
        }

        if (!empty($this->getPrice())) {
            $result['price'] = $this->getPrice();
        }

        return $result;
    }
}
