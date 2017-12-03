<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ElizeProduct;
use yii\data\SqlDataProvider;

/**
 * ElizeProductSearch represents the model behind the search form about `app\models\ElizeProduct`.
 */
class ElizeProductSearch extends ElizeProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['id', 'showcases_new', 'showcases_exclusive', 'showcases_limit', 'showcases_sale', 'showcases_best'],
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
            [['new_price', 'old_price'], 'number'],
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
        $query = ElizeProduct::find();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 25],
            ]
        );

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(
            [
                'id' => $this->id,
                'showcases_new' => $this->showcases_new,
                'showcases_exclusive' => $this->showcases_exclusive,
                'showcases_limit' => $this->showcases_limit,
                'showcases_sale' => $this->showcases_sale,
                'showcases_best' => $this->showcases_best,
                'new_price' => $this->new_price,
                'old_price' => $this->old_price,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'deleted_at' => $this->deleted_at,
            ]
        );

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

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function searchDeletedProduct($params)
    {
        $query = ElizeProduct::find();
        // print_r($params);die;
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 20],
            ]
        );
        $this->load($params);

        $query->andFilterWhere(
            [
                'id' => $this->id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'deleted_at' => $this->deleted_at,
            ]
        );

        if (!empty($params['date'])) {
            $query->where('DATE_FORMAT(deleted_at,  "%Y-%m-%d") = "'.$params['date'].'"');
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

    public function searchNewProduct($params)
    {
        $query = ElizeProduct::find();
        // print_r($params);die;
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 20],
            ]
        );
        $this->load($params);

        $query->andFilterWhere(
            [
                'id' => $this->id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'deleted_at' => $this->deleted_at,
            ]
        );

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
        $query = ElizeProduct::find();
        $query->select(['count(id) as counts', 'DATE_FORMAT(created_at,  "%Y-%m-%d") as dates']);
        $query->groupBy(['DATE_FORMAT(created_at,  "%Y-%m-%d")']);
        $query->orderBy('created_at');
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 50],
            ]
        );

        return $dataProvider;
    }

    /**
     * @param bool $pagination
     *
     * @return SqlDataProvider
     */
    public static function getNewStatistics($pagination = true)
    {
        $sql = "SELECT count(id) as counts, DATE_FORMAT(created_at,  \"%Y-%m-%d\") as dates from elize_product GROUP BY DATE_FORMAT(created_at,  \"%Y-%m-%d\") ";
        $totalCount = \Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($sql) as a")->queryScalar();

        if ($pagination) {
            $paging = [
                'pageSize' => 8,
            ];
        } else {
            $paging = false;
        }

        $dataProvider = new SqlDataProvider(
            [
                'sql' => $sql.' ORDER BY created_at desc',
                'totalCount' => (int)$totalCount,
                'pagination' => $paging,
            ]
        );

        return $dataProvider;
    }

    /**
     * @return array
     */
    public static function getStatisticsDeleted()
    {
        $sql = "SELECT count(id) as counts, DATE_FORMAT(deleted_at,  \"%Y-%m-%d\") as dates from elize_product  WHERE deleted_at > 0  GROUP BY DATE_FORMAT(deleted_at,  \"%Y-%m-%d\") ";
        $totalCount = \Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($sql) as a")->queryScalar();

        $dataProvider = new SqlDataProvider(
            [
                'sql' => $sql.' ORDER BY deleted_at desc',
                'totalCount' => (int)$totalCount,
                'pagination' => [
                    'pageSize' => 8,
                ],
            ]
        );

        return $dataProvider;
    }
}
