<?php
namespace tests\codeception\unit\models;

use yii\codeception\DbTestCase;
use app\models\Issue;
use tests\codeception\unit\fixtures\IssueFixture;

use Codeception\Util\Debug;

class IssueTest extends DbTestCase
{
    use \Codeception\Specify;

    private $issue;
    private $issue_id;

    public function fixtures()
    {
        return [
            'issue' => IssueFixture::className(),
        ];
    }

    public function testCreateDelete()
    {
        $this->issue = new Issue();

        $this->specify("Issue can validate and delete", function() {
            $this->issue->tracker_id = 1;
            $this->issue->project_id = 1;
            $this->issue->subject = 'Stuffisodfj';
            $this->issue->description = 'description here sdafa sadf saf';
            $this->issue->user_id = 1;
            $this->issue->issue_priority_id = 1;
            //$this->issue->version_id = 1;
            $this->issue->assigned_to = 1;
            $this->issue->created = time();
            $this->issue->modified = time();
            $this->issue->done_ratio = 0;
            $this->issue->status = 'ok';
            $this->issue->closed = 0;
            $this->issue->pre_done_ratio = 0;
            $this->issue->updated_by = 1;
            $this->issue->last_comment = null;
            $this->assertTrue($this->issue->validate(), 'Issue should validate');
            $this->assertTrue($this->issue->save(), 'Issue should save');
            $this->issue_id = $this->issue->id;
            $this->issue->delete();
            $this->assertNull(Issue::findOne($this->issue_id), 'Issue should not exists anymore');
        });

    }

}
