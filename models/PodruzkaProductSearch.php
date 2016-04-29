<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PodruzkaProductSearch represents the model behind the search form about `app\models\PodruzkaProduct`.
 */
class PodruzkaProductSearch extends PodruzkaProduct
{
    public $l_title;
    public $l_article;
    public $l_desc;
    public $l_old_price;
    public $l_new_price;
    public $r_title;
    public $r_article;
    public $r_desc;
    public $r_price;
    public $r_blue_price;
    public $r_gold_price;
    public $i_title;
    public $i_article;
    public $i_desc;
    public $i_old_price;
    public $i_new_price;
    public $e_title;
    public $e_old_price;
    public $e_new_price;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'i_id', 'r_id', 'l_id'], 'integer'],
            [['article', 'title', 'group', 'category', 'sub_category', 'detail', 'brand', 'sub_brand',
                'line', 'arrival', 'ile_id', 'rive_id', 'letu_id', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['price', 'ma_price'], 'number'],
            [[
                'l_title','l_article','l_desc','l_old_price','l_new_price',
                'r_title','r_article','r_desc','r_price','r_blue_price','r_gold_price',
                'i_title','i_article','i_desc','i_old_price','i_new_price','e_title','e_old_price','e_new_price',
            ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PodruzkaProduct::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'arrival', $this->arrival])
            ->andFilterWhere(['=', 'group', $this->group])
            ->andFilterWhere(['=', 'category', $this->category])
            ->andFilterWhere(['=', 'sub_category', $this->sub_category])
            ->andFilterWhere(['=', 'detail', $this->detail])
            ->andFilterWhere(['=', 'brand', $this->brand])
            ->andFilterWhere(['=', 'sub_brand', $this->sub_brand])
            ->andFilterWhere(['=', 'line', $this->line])
            ->andFilterWhere(['like', 'price', $this->price])
            ->andFilterWhere(['like', 'ma_price', $this->ma_price])
            ;

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchMatching($params)
    {
        $query = PodruzkaProduct::find();
        $query->joinWith('l', true, 'LEFT JOIN');
        $query->joinWith('r', true, 'LEFT JOIN');
        $query->joinWith('i', true, 'LEFT JOIN');
        $query->joinWith('e', true, 'LEFT JOIN');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $dataProvider->sort->attributes['l_article'] = [
            'asc' => ['letual_product.article' => SORT_ASC],
            'desc' => ['letual_product.article' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['l_title'] = [
            'asc' => ['letual_product.title' => SORT_ASC],
            'desc' => ['letual_product.title' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['l_old_price'] = [
            'asc' => ['letual_product.old_price' => SORT_ASC],
            'desc' => ['letual_product.old_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['l_new_price'] = [
            'asc' => ['letual_product.new_price' => SORT_ASC],
            'desc' => ['letual_product.new_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['l_desc'] = [
            'asc' => ['letual_product.description' => SORT_ASC],
            'desc' => ['letual_product.description' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['r_article'] = [
            'asc' => ['rivegauche_product.article' => SORT_ASC],
            'desc' => ['rivegauche_product.article' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['r_title'] = [
            'asc' => ['rivegauche_product.title' => SORT_ASC],
            'desc' => ['rivegauche_product.title' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['r_price'] = [
            'asc' => ['rivegauche_product.price' => SORT_ASC],
            'desc' => ['rivegauche_product.price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['r_blue_price'] = [
            'asc' => ['rivegauche_product.blue_price' => SORT_ASC],
            'desc' => ['rivegauche_product.blue_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['r_gold_price'] = [
            'asc' => ['rivegauche_product.gold_price' => SORT_ASC],
            'desc' => ['rivegauche_product.gold_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['r_desc'] = [
            'asc' => ['rivegauche_product.description' => SORT_ASC],
            'desc' => ['rivegauche_product.description' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['i_article'] = [
            'asc' => ['iledebeaute_product.article' => SORT_ASC],
            'desc' => ['iledebeaute_product.article' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['i_title'] = [
            'asc' => ['iledebeaute_product.title' => SORT_ASC],
            'desc' => ['iledebeaute_product.title' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['i_old_price'] = [
            'asc' => ['iledebeaute_product.old_price' => SORT_ASC],
            'desc' => ['iledebeaute_product.old_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['i_new_price'] = [
            'asc' => ['iledebeaute_product.new_price' => SORT_ASC],
            'desc' => ['iledebeaute_product.new_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['i_desc'] = [
            'asc' => ['iledebeaute_product.description' => SORT_ASC],
            'desc' => ['iledebeaute_product.description' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['e_title'] = [
            'asc' => ['elize_product.title' => SORT_ASC],
            'desc' => ['elize_product.title' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['e_old_price'] = [
            'asc' => ['elize_product.old_price' => SORT_ASC],
            'desc' => ['elize_product.old_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['e_new_price'] = [
            'asc' => ['elize_product.new_price' => SORT_ASC],
            'desc' => ['elize_product.new_price' => SORT_DESC],
        ];

        $query->andFilterWhere(['like', 'podruzka_product.article', $this->article])
            ->andFilterWhere(['like', 'podruzka_product.title', $this->title])
            ->andFilterWhere(['like', 'podruzka_product.arrival', $this->arrival])
            ->andFilterWhere(['like', 'podruzka_product.group', $this->group])
            ->andFilterWhere(['like', 'podruzka_product.category', $this->category])
            ->andFilterWhere(['like', 'podruzka_product.sub_category', $this->sub_category])
            ->andFilterWhere(['like', 'podruzka_product.detail', $this->detail])
            ->andFilterWhere(['like', 'podruzka_product.brand', $this->brand])
            ->andFilterWhere(['like', 'podruzka_product.sub_brand', $this->sub_brand])
            ->andFilterWhere(['like', 'podruzka_product.line', $this->line])
            ->andFilterWhere(['like', 'podruzka_product.price', $this->price])
            ->andFilterWhere(['like', 'podruzka_product.ma_price', $this->ma_price])
            ->andFilterWhere(['like', 'letual_product.title', $this->l_title])
            ->andFilterWhere(['like', 'letual_product.article', $this->l_article])
            ->andFilterWhere(['like', 'letual_product.description', $this->l_desc])
            ->andFilterWhere(['like', 'letual_product.old_price', $this->l_old_price])
            ->andFilterWhere(['like', 'letual_product.new_price', $this->l_new_price])
            ->andFilterWhere(['like', 'rivegauche_product.title', $this->r_title])
            ->andFilterWhere(['like', 'rivegauche_product.article', $this->r_article])
            ->andFilterWhere(['like', 'rivegauche_product.description', $this->r_desc])
            ->andFilterWhere(['like', 'rivegauche_product.price', $this->r_price])
            ->andFilterWhere(['like', 'rivegauche_product.new_price', $this->r_blue_price])
            ->andFilterWhere(['like', 'rivegauche_product.old_price', $this->r_gold_price])
            ->andFilterWhere(['like', 'iledebeaute_product.title', $this->i_title])
            ->andFilterWhere(['like', 'iledebeaute_product.article', $this->i_article])
            ->andFilterWhere(['like', 'iledebeaute_product.description', $this->i_desc])
            ->andFilterWhere(['like', 'iledebeaute_product.old_price', $this->i_old_price])
            ->andFilterWhere(['like', 'iledebeaute_product.new_price', $this->i_new_price])
            ->andFilterWhere(['like', 'elize_product.title', $this->e_title])
            ->andFilterWhere(['like', 'elize_product.old_price', $this->e_old_price])
            ->andFilterWhere(['like', 'elize_product.new_price', $this->e_new_price])
            ->andWhere('l_id is not null or r_id is not null or i_id is not null or e_id is not null');

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchPriceMatching($params)
    {
        $query = PodruzkaProduct::find();
        $query->joinWith('l', true, 'LEFT JOIN');
        $query->joinWith('r', true, 'LEFT JOIN');
        $query->joinWith('i', true, 'LEFT JOIN');
        $query->joinWith('e', true, 'LEFT JOIN');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $dataProvider->sort->attributes['l_article'] = [
            'asc' => ['letual_product.article' => SORT_ASC],
            'desc' => ['letual_product.article' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['l_title'] = [
            'asc' => ['letual_product.title' => SORT_ASC],
            'desc' => ['letual_product.title' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['l_old_price'] = [
            'asc' => ['letual_product.old_price' => SORT_ASC],
            'desc' => ['letual_product.old_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['l_new_price'] = [
            'asc' => ['letual_product.new_price' => SORT_ASC],
            'desc' => ['letual_product.new_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['l_desc'] = [
            'asc' => ['letual_product.description' => SORT_ASC],
            'desc' => ['letual_product.description' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['r_article'] = [
            'asc' => ['rivegauche_product.article' => SORT_ASC],
            'desc' => ['rivegauche_product.article' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['r_title'] = [
            'asc' => ['rivegauche_product.title' => SORT_ASC],
            'desc' => ['rivegauche_product.title' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['r_price'] = [
            'asc' => ['rivegauche_product.price' => SORT_ASC],
            'desc' => ['rivegauche_product.price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['r_blue_price'] = [
            'asc' => ['rivegauche_product.blue_price' => SORT_ASC],
            'desc' => ['rivegauche_product.blue_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['r_gold_price'] = [
            'asc' => ['rivegauche_product.gold_price' => SORT_ASC],
            'desc' => ['rivegauche_product.gold_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['r_desc'] = [
            'asc' => ['rivegauche_product.description' => SORT_ASC],
            'desc' => ['rivegauche_product.description' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['i_article'] = [
            'asc' => ['iledebeaute_product.article' => SORT_ASC],
            'desc' => ['iledebeaute_product.article' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['i_title'] = [
            'asc' => ['iledebeaute_product.title' => SORT_ASC],
            'desc' => ['iledebeaute_product.title' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['i_old_price'] = [
            'asc' => ['iledebeaute_product.old_price' => SORT_ASC],
            'desc' => ['iledebeaute_product.old_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['i_new_price'] = [
            'asc' => ['iledebeaute_product.new_price' => SORT_ASC],
            'desc' => ['iledebeaute_product.new_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['i_desc'] = [
            'asc' => ['iledebeaute_product.description' => SORT_ASC],
            'desc' => ['iledebeaute_product.description' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['e_old_price'] = [
            'asc' => ['elize_product.old_price' => SORT_ASC],
            'desc' => ['elize_product.old_price' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['e_new_price'] = [
            'asc' => ['elize_product.new_price' => SORT_ASC],
            'desc' => ['elize_product.new_price' => SORT_DESC],
        ];
        $query->andFilterWhere(['like', 'podruzka_product.article', $this->article])
            ->andFilterWhere(['like', 'podruzka_product.title', $this->title])
            ->andFilterWhere(['like', 'podruzka_product.arrival', $this->arrival])
            ->andFilterWhere(['like', 'podruzka_product.group', $this->group])
            ->andFilterWhere(['like', 'podruzka_product.category', $this->category])
            ->andFilterWhere(['like', 'podruzka_product.sub_category', $this->sub_category])
            ->andFilterWhere(['like', 'podruzka_product.detail', $this->detail])
            ->andFilterWhere(['like', 'podruzka_product.brand', $this->brand])
            ->andFilterWhere(['like', 'podruzka_product.sub_brand', $this->sub_brand])
            ->andFilterWhere(['like', 'podruzka_product.line', $this->line])
            ->andFilterWhere(['like', 'podruzka_product.price', $this->price])
            ->andFilterWhere(['like', 'podruzka_product.ma_price', $this->ma_price])
            ->andFilterWhere(['like', 'letual_product.title', $this->l_title])
            ->andFilterWhere(['like', 'letual_product.article', $this->l_article])
            ->andFilterWhere(['like', 'letual_product.description', $this->l_desc])
            ->andFilterWhere(['like', 'letual_product.old_price', $this->l_old_price])
            ->andFilterWhere(['like', 'letual_product.new_price', $this->l_new_price])
            ->andFilterWhere(['like', 'rivegauche_product.title', $this->r_title])
            ->andFilterWhere(['like', 'rivegauche_product.article', $this->r_article])
            ->andFilterWhere(['like', 'rivegauche_product.description', $this->r_desc])
            ->andFilterWhere(['like', 'rivegauche_product.price', $this->r_price])
            ->andFilterWhere(['like', 'rivegauche_product.new_price', $this->r_blue_price])
            ->andFilterWhere(['like', 'rivegauche_product.old_price', $this->r_gold_price])
            ->andFilterWhere(['like', 'iledebeaute_product.title', $this->i_title])
            ->andFilterWhere(['like', 'iledebeaute_product.article', $this->i_article])
            ->andFilterWhere(['like', 'iledebeaute_product.description', $this->i_desc])
            ->andFilterWhere(['like', 'iledebeaute_product.old_price', $this->i_old_price])
            ->andFilterWhere(['like', 'iledebeaute_product.new_price', $this->i_new_price])
            ->andFilterWhere(['like', 'elize_product.old_price', $this->e_old_price])
            ->andFilterWhere(['like', 'elize_product.new_price', $this->e_new_price])
            ->andWhere('l_id is not null or r_id is not null or i_id is not null or e_id is not null');

        return $dataProvider;
    }
}
