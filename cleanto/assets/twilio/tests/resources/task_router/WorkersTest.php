<?php 

use \Mockery as m;

class WorkersTest extends PHPUnit_Framework_TestCase
{

    function testCreate()
    {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('post')->once()
            ->with('/v1/Workspaces/WS123/Workers',
                array('Content-Type' => 'application/x-www-form-urlencoded'),
                'FriendlyName=Test+Worker')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('sid' => 'WK123'))
            ));
        $taskrouterClient = new TaskRouter_Services_Twilio('AC123', '123', 'WS123', 'v1', $https);
        $worker = $taskrouterClient->workspace->workers->create('Test Worker');
        $this->assertNotNull($worker);
    }

    function testGet() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('get')->once()
            ->with('/v1/Workspaces/WS123/Workers/WK123')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('sid' => 'WQ123', 'friendly_name' => 'Test Worker'))
            ));
        $taskrouterClient = new TaskRouter_Services_Twilio('AC123', '123', 'WS123', 'v1', $https);
        $worker = $taskrouterClient->workspace->workers->get('WK123');
        $this->assertNotNull($worker);
        $this->assertEquals('Test Worker', $worker->friendly_name);
    }

    function tearDown()
    {
        m::close();
    }
}
