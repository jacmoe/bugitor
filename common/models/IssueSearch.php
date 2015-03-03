<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Issue;

/**
 * IssueSearch represents the model behind the search form about Issue.
 */
class IssueSearch extends Model
{
	public $id;
	public $tracker_id;
	public $project_id;
	public $subject;
	public $description;
	public $issue_category_id;
	public $user_id;
	public $issue_priority_id;
	public $version_id;
	public $assigned_to;
	public $created;
	public $modified;
	public $done_ratio;
	public $status;
	public $closed;
	public $pre_done_ratio;
	public $updated_by;
	public $last_comment;

	public function rules()
	{
		return [
			[['id', 'tracker_id', 'project_id', 'issue_category_id', 'user_id', 'issue_priority_id', 'version_id', 'assigned_to', 'done_ratio', 'closed', 'pre_done_ratio', 'updated_by', 'last_comment'], 'integer'],
			[['subject', 'description', 'created', 'modified', 'status'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'tracker_id' => 'Tracker ID',
			'project_id' => 'Project ID',
			'subject' => 'Subject',
			'description' => 'Description',
			'issue_category_id' => 'Issue Category ID',
			'user_id' => 'User ID',
			'issue_priority_id' => 'Issue Priority ID',
			'version_id' => 'Version ID',
			'assigned_to' => 'Assigned To',
			'created' => 'Created',
			'modified' => 'Modified',
			'done_ratio' => 'Done Ratio',
			'status' => 'Status',
			'closed' => 'Closed',
			'pre_done_ratio' => 'Pre Done Ratio',
			'updated_by' => 'Updated By',
			'last_comment' => 'Last Comment',
		];
	}

	public function search($params)
	{
		$query = Issue::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$query->andFilterWhere([
            'id' => $this->id,
            'tracker_id' => $this->tracker_id,
            'project_id' => $this->project_id,
            'issue_category_id' => $this->issue_category_id,
            'user_id' => $this->user_id,
            'issue_priority_id' => $this->issue_priority_id,
            'version_id' => $this->version_id,
            'assigned_to' => $this->assigned_to,
            'created' => $this->created,
            'modified' => $this->modified,
            'done_ratio' => $this->done_ratio,
            'closed' => $this->closed,
            'pre_done_ratio' => $this->pre_done_ratio,
            'updated_by' => $this->updated_by,
            'last_comment' => $this->last_comment,
        ]);

		$query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'status', $this->status]);

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
