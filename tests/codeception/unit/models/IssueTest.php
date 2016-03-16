<?php
namespace tests\codeception\unit\models;

use yii\codeception\DbTestCase;
use app\models\Issue;
use tests\codeception\unit\fixtures\IssueFixture;

use Codeception\Util\Debug;

class IssueTest extends DbTestCase
{
    public function fixtures()
    {
        return [
            'issue' => IssueFixture::className(),
        ];
    }

    public function testCreate()
    {
        $issue = new Issue();
        $issue->tracker_id = 1;
        $issue->project_id = 1;
        $issue->subject = 'Stuffisodfj';
        $issue->description = 'description here sdafa sadf saf';
        $issue->user_id = 1;
        $issue->issue_priority_id = 1;
        //$issue->version_id = 1;
        $issue->assigned_to = 1;
        $issue->created = time();
        $issue->modified = time();
        $issue->done_ratio = 0;
        $issue->status = 'ok';
        $issue->closed = 0;
        $issue->pre_done_ratio = 0;
        $issue->updated_by = 1;
        $issue->last_comment = null;
        $this->assertTrue($issue->validate(), 'Issue should validate');
        Debug::debug($issue->errors);
        $this->assertTrue($issue->save(), 'Issue should save');
        $id = $issue->id;
        $issue->delete();
        $this->assertNull(Issue::findOne($id), 'Issue should not exists anymore');
    }

}
