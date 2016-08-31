<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Computation;

/**
 * ComputationSearch represents the model behind the search form about `app\models\Computation`.
 */
class ComputationSearch extends Computation
{
    public $codesString;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'codesString'], 'integer'],
            [['title', 'text', 'codesString'], 'safe'],
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
        $query = Computation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $codesOperator = $this->getCodesOperator($this->codesString);
        $this->codesString = str_replace($codesOperator, '', $this->codesString);
        $codesOperand = $this->codesString;

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        // Фильтр по строке кодов
        if ($this->codesString) {
            $query->joinWith('codes')->where('code.code '.$codesOperator.' '. $this->codesString)->all();
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text]);

        if($codesOperand) $this->codesString = $codesOperator . $codesOperand;
        return $dataProvider;
    }

    private function getCodesOperator($qryString)
    {
        switch ($qryString) {
            case strpos($qryString,'>=') === 0:
                $operator = '>='; 
            break;
            case strpos($qryString,'>') === 0:
                $operator = '>';
                break;
            case strpos($qryString,'<=') === 0:
                $operator = '<=';
                break;
            case strpos($qryString,'<') === 0:
                $operator = '<';
                break;
            default:
                $operator = '=';
                break;
        }
        return $operator;
    }
}
