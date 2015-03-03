<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Issue;

/**
 * IssueSearch represents the model behind the search form about `common\models\Issue`.
 */
class IssueSearch extends Issue
{
    /**
     * @inheritdoc
     */
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
        $query = Issue::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
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
}
