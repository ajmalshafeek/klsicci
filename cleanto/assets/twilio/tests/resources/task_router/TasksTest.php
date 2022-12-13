<?php 

use \Mockery as m;

class TasksTest extends PHPUnit_Framework_TestCase
{
    function testCreate()
    {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('post')->once()
            ->with('/v1/Workspaces/WS123/Tasks',
                array('Content-Type' => 'application/x-www-form-urlencoded'),
                'Timeout=60&Attributes=attribute&WorkflowSid=WF123')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('sid' => 'WT123'))
            ));
        $taskrouterClient = new TaskRouter_Services_Twilio('AC123', '123', 'WS123', 'v1', $https);
        $activity = $taskrouterClient->workspace->tasks->create('attribute', 'WF123',array(
            'Timeout' => 60,
        ));
        $this->assertNotNull($activity);
    }

    function testGet() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('get')->once()
            ->with('/v1/Workspaces/WS123/Tasks/WT123')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('sid' => 'WT123', 'workflow_sid' => 'WF123'))
            ));
        $taskrouterClient = new TaskRouter_Services_Twilio('AC123', '123', 'WS123', 'v1', $https);
        $task = $taskrouterClient->workspace->tasks->get('WT123');
        $this->assertNotNull($task);
        $this->assertEquals('WF123', $task->workflow_sid);
    }

	function testGetPage() {
		$https = m::mock(new Services_Twilio_TinyHttp);
		$https->shouldReceive('get')->once()
			->with('/v1/Workspaces/WS123/Tasks?Page=0&PageSize=50')
			->andReturn(array(200, array('Content-Type' => 'application/json'),
							json_encode(array(
											'meta' => array('key' => 'tasks', 'next_page_url' => null),
											'tasks' => array(array('sid' => 'WT123', 'workflow_sid' => 'WF123'))
						))));
		$taskrouterClient = new TaskRouter_Services_Twilio('AC123', '123', 'WS123', 'v1', $https);
		$tasks = $taskrouterClient->workspace->tasks->getPage();
		$tasksItems = $tasks->getItems();
		$this->assertNotNull($tasks);
		$this->assertEquals('WF123', $tasksItems[0]->workflow_sid);
	}

    function tearDown()
    {
        m::close();
    }
}
