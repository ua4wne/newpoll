<?php

namespace app\modules\main\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\main\models\Renter;

/**
 * RenterSearch represents the model behind the search form about `app\modules\main\models\Renter`.
 */
class RenterSearch extends \app\modules\main\models\Renter
{
    public function attributes()
    {
        // делаем поле зависимости доступным для поиска
        return array_merge(parent::attributes(), ['division.name','place.name']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'place_id', 'status', 'division_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'area', 'agent', 'phone1', 'phone2', 'encounter'], 'safe'],
            [['koeff'], 'number'],
            [['division.name','place.name'], 'safe'],
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
        $query = Renter::find(); //->where(['status' => 1]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
        ]);
        /*$dataProvider->setSort([
            'attributes' => [
                'divisionName' => [
                    'asc' => ['division.name' => SORT_ASC],
                    'desc' => ['division.name' => SORT_DESC],
                    'label' => 'Закреплен за'
                ],
                'placeName' => [
                    'asc' => ['place.name' => SORT_ASC],
                    'desc' => ['place.name' => SORT_DESC],
                    'label' => 'Территория'
                ]
            ]]);*/

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            /**
             * Жадная загрузка данных модели Страны
             * для работы сортировки.
             */
            $query->joinWith(['division']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'koeff' => $this->koeff,
            'place_id' => $this->place_id,
            'status' => $this->status,
            'division_id' => $this->division_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'agent', $this->agent])
            ->andFilterWhere(['like', 'phone1', $this->phone1])
            ->andFilterWhere(['like', 'phone2', $this->phone2])
            ->andFilterWhere(['like', 'encounter', $this->encounter]);
        $query->andFilterWhere(['LIKE', 'division.name', $this->getAttribute('division.name')]);
        $query->andFilterWhere(['LIKE', 'place.name', $this->getAttribute('place.name')]);
        // Фильтр по подразделению
        $query->joinWith(['division' => function ($q) {
            $q->where('division.name LIKE "%' . $this->divisionName . '%"');
        }]);
        // Фильтр по территории
        $query->joinWith(['place' => function ($q) {
            $q->where('place.name LIKE "%' . $this->placeName . '%"');
        }]);

        return $dataProvider;
    }
}
