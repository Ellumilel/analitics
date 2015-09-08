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
            [['l_title','l_article','l_desc','l_old_price','l_new_price'], 'safe'],
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
            ->andFilterWhere(['like', 'group', $this->group])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'sub_category', $this->sub_category])
            ->andFilterWhere(['like', 'detail', $this->detail])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'sub_brand', $this->sub_brand])
            ->andFilterWhere(['like', 'line', $this->line])
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
        $query->joinWith(['l']);

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
        $query->andFilterWhere(['like', 'podruzka_product.article', $this->article])
            ->andFilterWhere(['like', 'podruzka_product.title', $this->title])
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
            ->andWhere('l_id is not null');

        return $dataProvider;
    }
}
