<?php

namespace app\modules\main\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * RenterSearch represents the model behind the search form about `app\modules\main\models\Renter`.
 */
class InetSearch extends Inet
{
    public function attributes()
    {
        // делаем поле зависимости доступным для поиска
        return array_merge(parent::attributes(), ['renter.title']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['renter_id'], 'integer'],
            [['connect', 'disconnect', 'created_at', 'updated_at'], 'safe'],
            [['ip'], 'string', 'max' => 7],
            [['comment'], 'string', 'max' => 200],
            [['renter.title'], 'safe'],
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
        $query = Inet::find(); //->where(['status' => 1]);

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
            //$query->joinWith(['division']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'renter_id' => $this->renter_id,
            'connect' => $this->connect,
            'disconnect' => $this->disconnect,
            'ip' => $this->ip,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['LIKE', 'renter.title', $this->getAttribute('renter.title')]);

        // Фильтр по арендатору
        $query->joinWith(['renter' => function ($q) {
            $q->where('renter.title LIKE "%' . $this->renterName . '%"');
        }]);

        return $dataProvider;
    }
}
