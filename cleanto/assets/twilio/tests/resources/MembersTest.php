<?php 

use \Mockery as m;

class MembersTest extends PHPUnit_Framework_TestCase {

    protected $formHeaders = array('Content-Type' => 'application/x-www-form-urlencoded');

    function testFront() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Queues/QQ123/Members/Front.json')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('call_sid' => 'CA123', 'position' => 0))
            ));
        $client = new Services_Twilio('AC123', '123', '2010-04-01', $https);
        $queue = $client->account->queues->get('QQ123');
        $firstMember = $queue->members->front();
        $this->assertSame($firstMember->call_sid, 'CA123');
    }

    function testDequeueFront() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('post')->once()
            ->with('/2010-04-01/Accounts/AC123/Queues/QQ123/Members/Front.json',
                $this->formHeaders, 'Url=https%3A%2F%2Ffoo.com&Method=POST')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('call_sid' => 'CA123', 'position' => 0))
            ));
        $client = new Services_Twilio('AC123', '123', '2010-04-01', $https);
        $queue = $client->account->queues->get('QQ123');
        $firstMember = $queue->members->front();
        $firstMember->dequeue('https://foo.com');
    }

    function testDequeueSid() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('post')->once()
            ->with('/2010-04-01/Accounts/AC123/Queues/QQ123/Members/CA123.json',
                $this->formHeaders, 'Url=https%3A%2F%2Ffoo.com&Method=GET')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('call_sid' => 'CA123', 'position' => 0))
            ));
        $client = new Services_Twilio('AC123', '123', '2010-04-01', $https);
        $queue = $client->account->queues->get('QQ123');
        $firstMember = $queue->members->get('CA123');
        $firstMember->dequeue('https://foo.com', 'GET');
    }

    function testMemberIterate() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $resp = json_encode(
                    array(
                        'queue_members' => array(
                            array('call_sid' => 'CA123', 'wait_time' => 30)
                        ),
                        'end' => 1,
                    )
                );
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Queues/QQ123/Members.json?Page=0&PageSize=50')
            ->andReturn(array(200, array('Content-Type' => 'application/json'), $resp
            ));
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Queues/QQ123/Members.json?Page=1&PageSize=50')
            ->andReturn(array(400, array('Content-Type' => 'application/json'),
                '{"status":400,"message":"foo", "code": "20006"}'
            ));
        $client = new Services_Twilio('AC123', '123', '2010-04-01', $https);
        $queue = $client->account->queues->get('QQ123');
        foreach($queue->members as $member) {
            $this->assertSame($member->call_sid, 'CA123');
            $this->assertSame($member->wait_time, 30);
        }
    }

    function tearDown() {
        m::close();
    }

}


