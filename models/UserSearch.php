<?php

namespace app\models;

use Faker\Provider\th_TH\Internet;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 *
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    public $first_name;

    public $last_name;

    public $full_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['email', 'username', 'first_name', 'last_name', 'full_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
     * @param $id
     * @return ActiveDataProvider
     */
    public function search($params, $id = null)
    {
        if ($id) {
            $query = User::findOne($id)->getFollowers();
        } else {
            $query = User::find();
        }

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
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'username', $this->username]);

        $query->joinWith('profile')
            ->andFilterWhere(['like', 'profile.first_name', $this->first_name])
            ->andFilterWhere(['like', 'profile.last_name', $this->last_name]);

        $query->andFilterWhere(['OR',
            ['like', 'profile.first_name', $this->full_name],
            ['like', 'profile.last_name', $this->full_name],
            ['like', 'user.username', $this->full_name],
        ]);

        return $dataProvider;
    }
}
