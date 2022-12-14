<?php 

use \Mockery as m;

class NotificationTest extends PHPUnit_Framework_TestCase {
    function testDelete() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('delete')->once()
            ->with('/2010-04-01/Accounts/AC123/Notifications/NO123.json')
            ->andReturn(array(204, array(), ''));
        $client = new Services_Twilio('AC123', '123', '2010-04-01', $https);
        $client->account->notifications->delete('NO123');
    }

    function tearDown()
    {
        m::close();
    }
}

