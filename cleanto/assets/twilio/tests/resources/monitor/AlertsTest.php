<?php 

use \Mockery as m;

class MonitorAlertsTest extends PHPUnit_Framework_TestCase
{

    function testGet() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('get')->once()
            ->with('/v1/Alerts/NO123')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('sid' => 'NO123', 'alert_text' => 'Test'))
            ));
        $monitorClient = new Monitor_Services_Twilio('AC123', '123', 'v1', $https);
        $alert = $monitorClient->alerts->get('NO123');
        $this->assertNotNull($alert);
        $this->assertEquals('Test', $alert->alert_text);
    }

    function testGetList()
    {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('get')->once()
            ->with('/v1/Alerts?Page=0&PageSize=50')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array(
                    'meta' => array('key' => 'alerts', 'next_page_url' => null),
                    'alerts' => array(array('sid' => 'NO123'))
                ))
            ));
        $monitorClient = new Monitor_Services_Twilio('AC123', '123', 'v1', $https);
        foreach ($monitorClient->alerts->getIterator(0, 50) as $alert) {
            $this->assertEquals('NO123', $alert->sid);
        }
    }

    function tearDown()
    {
        m::close();
    }
}
