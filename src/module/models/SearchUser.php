<?php

namespace Yiiman\ModuleUser\module\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yiiman\ModuleUser\module\models\User;

/**
 * SearchUser represents the model behind the search form of `Yiiman\ModuleUser\module\models\User`.
 */
class SearchUser extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'credit'], 'integer'],
            [['username', 'mobile', 'auth_key', 'password_hash', 'password_reset_token', 'created_at', 'updated_at', 'fullname', 'verification', 'name', 'family', 'birthday', 'created_by', 'updated_by', 'deleted_by', 'restored_by', 'nation_code', 'bank_card'], 'safe'],
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
     * @return ActiveDataProvider
     */
    public function search($params)
{
    $query = User::find();

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
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        'status' => $this->status,
        'birthday' => $this->birthday,
        'updated_by' => $this->updated_by,
        'credit' => $this->credit,
    ]);

    $query->andFilterWhere(['like', 'username', $this->username])
        ->andFilterWhere(['like', 'mobile', $this->mobile])
        ->andFilterWhere(['like', 'auth_key', $this->auth_key])
        ->andFilterWhere(['like', 'password_hash', $this->password_hash])
        ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
        ->andFilterWhere(['like', 'fullname', $this->fullname])
        ->andFilterWhere(['like', 'verification', $this->verification])
        ->andFilterWhere(['like', 'name', $this->name])
        ->andFilterWhere(['like', 'family', $this->family])
        ->andFilterWhere(['like', 'created_by', $this->created_by])
        ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
        ->andFilterWhere(['like', 'restored_by', $this->restored_by])
        ->andFilterWhere(['like', 'nation_code', $this->nation_code])
        ->andFilterWhere(['like', 'bank_card', $this->bank_card]);

    return $dataProvider;
}

    public function searchJobStatus($params)
    {
        $query = User::find()->where(['status_job' => User::STATUS_JOB_SEND_ATTACHED]);

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'birthday' => $this->birthday,
            'updated_by' => $this->updated_by,
            'credit' => $this->credit,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'verification', $this->verification])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'family', $this->family])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'restored_by', $this->restored_by])
            ->andFilterWhere(['like', 'nation_code', $this->nation_code])
            ->andFilterWhere(['like', 'bank_card', $this->bank_card]);

        return $dataProvider;
    }
}
