<?php

namespace app\modules\main\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\main\models\Visitor;

/**
 * SearchVisitor represents the model behind the search form about `app\modules\main\models\Visitor`.
 */
class SearchVisitor extends Visitor
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'renter_id', 'status'], 'integer'],
            [['fname', 'mname', 'lname', 'image', 'car_type', 'car_num', 'doc_type', 'doc_series', 'doc_num', 'created_at', 'updated_at'], 'safe'],
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
        $query = Visitor::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'renter_id' => $this->renter_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'fname', $this->fname])
            ->andFilterWhere(['like', 'mname', $this->mname])
            ->andFilterWhere(['like', 'lname', $this->lname])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'car_type', $this->car_type])
            ->andFilterWhere(['like', 'car_num', $this->car_num])
            ->andFilterWhere(['like', 'doc_type', $this->doc_type])
            ->andFilterWhere(['like', 'doc_series', $this->doc_series])
            ->andFilterWhere(['like', 'doc_num', $this->doc_num]);

        return $dataProvider;
    }
}
