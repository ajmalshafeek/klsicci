<?php 

use \Mockery as m;

class MessagesTest extends PHPUnit_Framework_TestCase
{
    protected $formHeaders = array('Content-Type' => 'application/x-www-form-urlencoded');

    function testCreateMessage() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('post')->once()
            ->with('/2010-04-01/Accounts/AC123/Messages.json', $this->formHeaders,
                'From=%2B1222&To=%2B44123&Body=Hi+there')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('sid' => 'SM123'))
            ));
        $client = new Services_Twilio('AC123', '123', '2010-04-01', $https);
        $msg = $client->account->messages->sendMessage('+1222', '+44123', 'Hi there');
        $this->assertSame('SM123', $msg->sid);
    }

    function testCreateMessageWithMedia() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('post')->once()
            ->with('/2010-04-01/Accounts/AC123/Messages.json', $this->formHeaders,
                'From=%2B1222&To=%2B44123&MediaUrl=https%3A%2F%2Fexample.com%2Fimage1')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('sid' => 'SM123'))
            ));
        $client = new Services_Twilio('AC123', '123', '2010-04-01', $https);
        $msg = $client->account->messages->sendMessage('+1222', '+44123', null,
            array('https://example.com/image1'));
        $this->assertSame('SM123', $msg->sid);
    }

    function testCreateMessageWithMediaAndBody() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('post')->once()
            ->with('/2010-04-01/Accounts/AC123/Messages.json', $this->formHeaders,
                'From=%2B1222&To=%2B44123&MediaUrl=https%3A%2F%2Fexample.com%2Fimage1&Body=Hi+there')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('sid' => 'SM123'))
            ));
        $client = new Services_Twilio('AC123', '123', '2010-04-01', $https);
        $msg = $client->account->messages->sendMessage('+1222', '+44123', 'Hi there',
            array('https://example.com/image1')
        );
        $this->assertSame('SM123', $msg->sid);
    }

    function testCreateMessageWithMultipleMedia() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('post')->once()
            ->with('/2010-04-01/Accounts/AC123/Messages.json', $this->formHeaders,
                'From=%2B1222&To=%2B44123&MediaUrl=https%3A%2F%2Fexample.com%2Fimage1&MediaUrl=https%3A%2F%2Fexample.com%2Fimage2')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('sid' => 'SM123'))
            ));
        $client = new Services_Twilio('AC123', '123', '2010-04-01', $https);
        $msg = $client->account->messages->sendMessage('+1222', '+44123', null,
            array('https://example.com/image1', 'https://example.com/image2'));
        $this->assertSame('SM123', $msg->sid);
    }

    function testBadMessageThrowsException() {
        $this->setExpectedException('Services_Twilio_RestException');
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('post')->once()
            ->with('/2010-04-01/Accounts/AC123/Messages.json', $this->formHeaders,
                'From=%2B1222&To=%2B44123&Body=' . str_repeat('hi', 801))
            ->andReturn(array(400, array('Content-Type' => 'application/json'),
                json_encode(array(
                    'status' => '400',
                    'message' => 'Too long',
                ))
            ));
        $client = new Services_Twilio('AC123', '123', null, $https);
        $msg = $client->account->messages->sendMessage('+1222', '+44123', str_repeat('hi', 801));
    }

    function testRawCreate() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('post')->once()
            ->with('/2010-04-01/Accounts/AC123/Messages.json', $this->formHeaders,
                'From=%2B1222&To=%2B44123&MediaUrl=https%3A%2F%2Fexample.com%2Fimage1&MediaUrl=https%3A%2F%2Fexample.com%2Fimage2')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('sid' => 'SM123'))
            ));
        $client = new Services_Twilio('AC123', '123', '2010-04-01', $https);
        $msg = $client->account->messages->create(array(
            'From' => '+1222',
            'To' => '+44123',
            'MediaUrl' => array('https://example.com/image1', 'https://example.com/image2')
        ));
        $this->assertSame('SM123', $msg->sid);
    }

    function testDeleteMessage() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('delete')->once()
            ->with('/2010-04-01/Accounts/AC123/Messages/ME123.json')
            ->andReturn(array(204, array('Content-Type' => 'application/json'), ''
        ));
        $client = new Services_Twilio('AC123', '123', null, $https);
        $client->account->messages->delete('ME123');
    }

    function testNewline() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('post')->once()
            ->with('/2010-04-01/Accounts/AC123/Messages.json', $this->formHeaders,
                'Body=Hello%0A%0AHello')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('sid' => 'SM123'))
            ));
        $client = new Services_Twilio('AC123', '123', '2010-04-01', $https);
        $msg = $client->account->messages->create(array(
            'Body' => "Hello\n\nHello"
        ));
        $this->assertSame('SM123', $msg->sid);
    }
}

