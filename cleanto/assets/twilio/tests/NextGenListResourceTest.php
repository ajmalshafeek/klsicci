<?php 

use \Mockery as m;
require_once 'Twilio.php';

class Services_Twilio_Rest_Foo extends Services_Twilio_InstanceResource {
}

class Services_Twilio_Rest_Foos extends Services_Twilio_NextGenListResource {
}

class NextGenListResourceTest extends PHPUnit_Framework_TestCase {

	protected $https;
	protected $client;

	public function setUp() {
		$this->https = m::mock(new Services_Twilio_TinyHttp);
		$this->client = new Services_Twilio('AC123', 'foobar', '2010-04-01', $this->https);
		$this->client->foos = new Services_Twilio_Rest_Foos($this->client, "/Foos");
	}

	public function testGetPage() {
		$this->https->shouldReceive('get')->once()
			->with('/Foos.json?Page=0&PageSize=50')
			->andReturn(array(200, array('Content-Type' => 'application/json'),
						json_encode(array(
							'meta' => array('key' => 'foos', 'next_page_url' => null),
							'foos' => array(array('sid' => 'FO123'))
		))));
		
		$foos = $this->client->foos->getPage();
		$foosItems = $foos->getItems();
		$this->assertNotNull($foos);
		$this->assertEquals('FO123', $foosItems[0]->sid);
	}

	public function testIterator() {
		$this->https->shouldReceive('get')->once()
			->with('/Foos.json?Page=0&PageSize=50')
			->andReturn(array(200, array('Content-Type' => 'application/json'),
							json_encode(array(
											'meta' => array('key' => 'foos', 'next_page_url' => 'https://api.twilio.com/Foos.json?PageToken=NEXT'),
											'foos' => array(array('sid' => 'FO123'))
		))));
		$this->https->shouldReceive('get')->once()
			->with('https://api.twilio.com/Foos.json?PageToken=NEXT')
			->andReturn(array(200, array('Content-Type' => 'application/json'),
						json_encode(array(
							'meta' => array('key' => 'foos', 'next_page_url' => null),
							'foos' => array(array('sid' => 'FO456'))
		))));
		$iter = $this->client->foos->getIterator();
		$this->assertNotNull($iter);
		$this->assertTrue($iter->valid());
		$foo = $iter->current();
		$this->assertNotNull($foo);
		$this->assertEquals('FO123', $foo->sid);
		$iter->next();
		$iter->valid();
		$foo = $iter->current();
		$this->assertNotNull($foo);
		$this->assertEquals('FO456', $foo->sid);
	}
}
