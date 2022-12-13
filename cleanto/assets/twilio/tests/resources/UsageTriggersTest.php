<?php 

use \Mockery as m;

class UsageTriggersTest extends PHPUnit_Framework_TestCase {
    function testRetrieveTrigger() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Triggers/UT123.json')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array(
                    'sid' => 'UT123',
                    'date_created' => 'Tue, 09 Oct 2012 19:27:24 +0000',
                    'recurring' => null,
                    'usage_category' => 'totalprice',
                ))
            ));
        $client = new Services_Twilio('AC123', '456bef', '2010-04-01', $https);
        $usageSid = 'UT123';
        $usageTrigger = $client->account->usage_triggers->get($usageSid);
        $this->assertSame('totalprice', $usageTrigger->usage_category);
    }

    protected $formHeaders = array('Content-Type' => 'application/x-www-form-urlencoded');

    function testUpdateTrigger() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $usageSid = 'UT123';
        $https->shouldReceive('post')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Triggers/UT123.json',
                $this->formHeaders, 'FriendlyName=new')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array(
                    'friendly_name' => 'new',
                    'sid' => 'UT123',
                    'uri' => '/2010-04-01/Accounts/AC123/Usage/Triggers/UT123.json'
                ))
            ));
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Triggers/UT123.json')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array(
                    'sid' => 'UT123',
                    'friendly_name' => 'new',
                ))
            ));
        $client = new Services_Twilio('AC123', '456bef', '2010-04-01', $https);
        $usageTrigger = $client->account->usage_triggers->get($usageSid);
        $usageTrigger->update(array(
            'FriendlyName' => 'new',
        ));
        $usageTrigger2 = $client->account->usage_triggers->get($usageSid);
        $this->assertSame('new', $usageTrigger2->friendly_name);
    }

    function testFilterTriggerList() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $params = 'Page=0&PageSize=50&UsageCategory=sms';
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Triggers.json?' . $params)
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('usage_triggers' => array(
                    array(
                        'usage_category' => 'sms',
                        'current_value' => '4',
                        'trigger_value' => '100.30',
                    ),
                    array(
                        'usage_category' => 'sms',
                        'current_value' => '4',
                        'trigger_value' => '400.30',
                    )),
                    'next_page_uri' => '/2010-04-01/Accounts/AC123/Usage/Triggers.json?UsageCategory=sms&Page=1&PageSize=50',
                ))
            ));
        $params = 'UsageCategory=sms&Page=1&PageSize=50';
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Triggers.json?' . $params)
            ->andReturn(array(400, array('Content-Type' => 'application/json'),
                '{"status":400,"message":"foo", "code": "20006"}'
            ));
        $client = new Services_Twilio('AC123', '456bef', '2010-04-01', $https);
        foreach ($client->account->usage_triggers->getIterator(
            0, 50, array(
                'UsageCategory' => 'sms',
            )) as $trigger
        ) {
            $this->assertSame($trigger->current_value, "4");
        }
    }

    function testCreateTrigger() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $params = 'UsageCategory=sms&TriggerValue=100&CallbackUrl=foo';
        $https->shouldReceive('post')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Triggers.json',
                $this->formHeaders, $params)
            ->andReturn(array(201, array('Content-Type' => 'application/json'),
                json_encode(array(
                    'usage_category' => 'sms',
                    'sid' => 'UT123',
                    'uri' => '/2010-04-01/Accounts/AC123/Usage/Triggers/UT123.json'
                ))
            ));
        $client = new Services_Twilio('AC123', '456bef', '2010-04-01', $https);
        $trigger = $client->account->usage_triggers->create(
            'sms',
            '100',
            'foo'
        );
        $this->assertSame('sms', $trigger->usage_category);
    }
}

