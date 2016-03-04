<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LetualProduct;
use yii\data\SqlDataProvider;

/**
 * LetualProductSearch represents the model behind the search form about `app\models\LetualProduct`.
 */
class LetualProductSearch extends LetualProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [
                [
                    'article',
                    'link',
                    'group',
                    'category',
                    'sub_category',
                    'brand',
                    'title',
                    'old_price',
                    'new_price',
                    'description',
                    'image_link',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
                'safe',
            ],
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
        $query = LetualProduct::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        //$query->join('INNER JOIN','letual_price','letual_price.article = letual_product.article');

        $query->andFilterWhere([
            'id' => $this->id,
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
            ->andFilterWhere(['like', 'new_price', $this->new_price])
            ->andFilterWhere(['like', 'old_price', $this->old_price])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image_link', $this->image_link]);
        return $dataProvider;
    }

    public function searchNewProduct($params)
    {
        $query = LetualProduct::find();
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
            ->andFilterWhere(['like', 'new_price', $this->new_price])
            ->andFilterWhere(['like', 'old_price', $this->old_price])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image_link', $this->image_link]);

        return $dataProvider;
    }

    /**
     * @return array
     */
    public static function getStatistics()
    {
        $sql = "SELECT count(id) as counts, DATE_FORMAT(created_at,  \"%Y-%m-%d\") as dates from letual_product GROUP BY DATE_FORMAT(created_at,  \"%Y-%m-%d\") ";
        $totalCount = \Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($sql) as a")->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => $sql . ' ORDER BY created_at desc',
            'totalCount' => (int)$totalCount,
            'pagination' => [
                'pageSize' => 8,
            ],
        ]);
        return $dataProvider;
    }
}
