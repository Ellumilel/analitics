<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RivegaucheProduct;

/**
 * RivegaucheProductSearch represents the model behind the search form about `app\models\RivegaucheProduct`.
 */
class RivegaucheProductSearch extends RivegaucheProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'showcases_new',
                    'showcases_compliment',
                    'showcases_offer',
                    'showcases_exclusive',
                    'showcases_bestsellers',
                    'showcases_expertiza',
                ],
                'integer',
            ],
            [
                [
                    'article',
                    'link',
                    'group',
                    'category',
                    'sub_category',
                    'brand',
                    'title',
                    'description',
                    'image_link',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
                'safe',
            ],
            [['gold_price', 'blue_price', 'price'], 'number'],
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
        $query = RivegaucheProduct::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'showcases_new' => $this->showcases_new,
            'showcases_compliment' => $this->showcases_compliment,
            'showcases_offer' => $this->showcases_offer,
            'showcases_exclusive' => $this->showcases_exclusive,
            'showcases_bestsellers' => $this->showcases_bestsellers,
            'showcases_expertiza' => $this->showcases_expertiza,
            'gold_price' => $this->gold_price,
            'blue_price' => $this->blue_price,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'group', $this->group])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'sub_category', $this->sub_category])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image_link', $this->image_link]);

        return $dataProvider;
    }

    public function searchNewProduct($params)
    {
        $query = RivegaucheProduct::find();
        // print_r($params);die;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
        ]);
        $this->load($params);

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        if (!empty($params['date'])) {
            $query->where('DATE_FORMAT(created_at,  "%Y-%m-%d") = "'.$params['date'].'"');
        }


        $query->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'group', $this->group])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'sub_category', $this->sub_category])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'price', $this->price])
            ->andFilterWhere(['like', 'gold_price', $this->gold_price])
            ->andFilterWhere(['like', 'blue_price', $this->blue_price])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image_link', $this->image_link]);

        return $dataProvider;
    }

    public function searchEmptyBrand($params)
    {
        $query = RivegaucheProduct::find();
        $dataProvider = new ActiveDataProvider(['query' => $query, 'pagination' => ['pageSize' => 50]]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->where('brand is null or brand =""');

        $query->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'group', $this->group])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'sub_category', $this->sub_category])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'price', $this->price])
            ->andFilterWhere(['like', 'gold_price', $this->gold_price])
            ->andFilterWhere(['like', 'blue_price', $this->blue_price])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image_link', $this->image_link]);
        return $dataProvider;
    }
}
