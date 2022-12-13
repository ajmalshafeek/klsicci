<?php 

use \Mockery as m;

class OutgoingCallerIdsTest extends PHPUnit_Framework_TestCase {
    protected $formHeaders = array('Content-Type' => 'application/x-www-form-urlencoded');
    function testPost() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('post')->once()
            ->with('/2010-04-01/Accounts/AC123/OutgoingCallerIds.json',
                $this->formHeaders, 'PhoneNumber=%2B14158675309&FriendlyName=My+Home+Phone+Number')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array(
                    'account_sid' => 'AC123',
                    'phone_number' => '+14158675309',
                    'friendly_name' => 'My Home Phone Number',
                    'validation_code' => 123456,
                ))
            ));
        $client = new Services_Twilio('AC123', '123', '2010-04-01', $https);
        $request = $client->account->outgoing_caller_ids->create('+14158675309', array(
            'FriendlyName' => 'My Home Phone Number',
        ));
        $this->assertEquals(123456, $request->validation_code);
    }

    function tearDown() {
        m::close();
    }
}
