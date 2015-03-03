<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Project;

/**
 * ProjectSearch represents the model behind the search form about Project.
 */
class ProjectSearch extends Model
{
	public $id;
	public $name;
	public $description;
	public $homepage;
	public $public;
	public $created;
	public $modified;
	public $identifier;

	public function rules()
	{
		return [
			[['id', 'public'], 'integer'],
			[['name', 'description', 'homepage', 'created', 'modified', 'identifier'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'homepage' => 'Homepage',
			'public' => 'Public',
			'created' => 'Created',
			'modified' => 'Modified',
			'identifier' => 'Identifier',
		];
	}

	public function search($params)
	{
		$query = Project::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([
            'id' => $this->id,
            'public' => $this->public,
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

		$query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'homepage', $this->homepage])
            ->andFilterWhere(['like', 'identifier', $this->identifier]);

		return $dataProvider;
	}

	protected function addCondition($query, $attribute, $partialMatch = false)
	{
		$value = $this->$attribute;
		if (trim($value) === '') {
			return;
		}
		if ($partialMatch) {
			$value = '%' . strtr($value, ['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']) . '%';
			$query->andWhere(['like', $attribute, $value]);
		} else {
			$query->andWhere([$attribute => $value]);
		}
	}
}
