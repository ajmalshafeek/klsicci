<?php 

use \Mockery as m;

class UsageRecordsTest extends PHPUnit_Framework_TestCase {

    function testGetBaseRecord() {

        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Records.json?Page=0&PageSize=50')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('usage_records' => array(
                    array(
                        'category' => 'sms',
                        'count' => 5,
                        'end_date' => '2012-08-01',
                    ),
                    array(
                        'category' => 'calleridlookups',
                        'count' => 5,
                        'end_date' => '2012-08-01',
                    ))
                ))
            ));
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Records.json?Page=1&PageSize=50')
            ->andReturn(array(400, array('Content-Type' => 'application/json'),
                '{"status":400,"message":"foo", "code": "20006"}'
            ));

        $client = new Services_Twilio('AC123', '456bef', '2010-04-01', $https);
        foreach ($client->account->usage_records as $record) {
            $this->assertSame(5, $record->count);
        }
    }

    function testUsageRecordSubresource() {

        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Records/LastMonth.json?Page=0&PageSize=50')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('usage_records' => array(
                    array(
                        'category' => 'sms',
                        'count' => 4,
                        'end_date' => '2012-08-01',
                    ),
                    array(
                        'category' => 'calleridlookups',
                        'count' => 4,
                        'end_date' => '2012-08-01',
                    ))
                ))
            ));
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Records/LastMonth.json?Page=1&PageSize=50')
            ->andReturn(array(400, array('Content-Type' => 'application/json'),
                '{"status":400,"message":"foo", "code": "20006"}'
            ));

        $client = new Services_Twilio('AC123', '456bef', '2010-04-01', $https);
        foreach ($client->account->usage_records->last_month as $record) {
            $this->assertSame('2012-08-01', $record->end_date);
        }
    }

    function testGetCategory() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Records.json?Page=0&PageSize=1&Category=calls')
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('usage_records' => array(
                    array(
                        'category' => 'calls',
                        'count' => 4,
                        'price' => '100.30',
                        'end_date' => '2012-08-01',
                    )),
                ))
            ));
        $client = new Services_Twilio('AC123', '456bef', '2010-04-01', $https);
        $callRecord = $client->account->usage_records->getCategory('calls');
        $this->assertSame('100.30', $callRecord->price);
    }

    function testFilterUsageRecords() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $params = 'Page=0&PageSize=50&StartDate=2012-08-01&EndDate=2012-08-31';
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Records.json?' . $params)
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('usage_records' => array(
                    array(
                        'category' => 'sms',
                        'count' => 4,
                        'price' => '300.30',
                    ),
                    array(
                        'category' => 'calls',
                        'count' => 4,
                        'price' => '100.30',
                    )),
                ))
            ));
        $params = 'Page=1&PageSize=50&StartDate=2012-08-01&EndDate=2012-08-31';
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Records.json?' . $params)
            ->andReturn(array(400, array('Content-Type' => 'application/json'),
                '{"status":400,"message":"foo", "code": "20006"}'
            ));
        $client = new Services_Twilio('AC123', '456bef', '2010-04-01', $https);
        foreach ($client->account->usage_records->getIterator(0, 50, array(
            'StartDate' => '2012-08-01',
            'EndDate'   => '2012-08-31',
        )) as $record) {
            $this->assertSame(4, $record->count);
        }
    }

    function testGetCategoryOnSubresource() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $params = 'Page=0&PageSize=1&Category=sms';
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Records/Today.json?' . $params)
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('usage_records' => array(
                    array(
                        'category' => 'sms',
                        'count' => 4,
                        'price' => '100.30',
                        'end_date' => '2012-08-30'
                    )),
                ))
            ));
        $client = new Services_Twilio('AC123', '456bef', '2010-04-01', $https);
        $smsRecord = $client->account->usage_records->today->getCategory('sms');
        $this->assertSame($smsRecord->end_date, '2012-08-30');
    }

    function testTimeSeriesFilters() {
        $https = m::mock(new Services_Twilio_TinyHttp);
        $params = 'Page=0&PageSize=50&StartDate=2012-08-01&EndDate=2012-08-31&Category=recordings';
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Records/Daily.json?' . $params)
            ->andReturn(array(200, array('Content-Type' => 'application/json'),
                json_encode(array('usage_records' => array(
                    array(
                        'category' => 'recordings',
                        'count' => 4,
                        'price' => '100.30',
                        'end_date' => '2012-08-31'
                    ),
                    array(
                        'category' => 'recordings',
                        'count' => 4,
                        'price' => '100.30',
                        'end_date' => '2012-08-30'
                    )),
                ))
            ));
        $params = 'Page=1&PageSize=50&StartDate=2012-08-01&EndDate=2012-08-31&Category=recordings';
        $https->shouldReceive('get')->once()
            ->with('/2010-04-01/Accounts/AC123/Usage/Records/Daily.json?' . $params)
            ->andReturn(array(400, array('Content-Type' => 'application/json'),
                '{"status":400,"message":"foo", "code": "20006"}'
            ));
        $client = new Services_Twilio('AC123', '456bef', '2010-04-01', $https);
        foreach ($client->account->usage_records->daily->getIterator(0, 50, array(
            'StartDate' => '2012-08-01',
            'EndDate'   => '2012-08-31',
            'Category'  => 'recordings',
        )) as $record) {
            $this->assertSame($record->category, 'recordings');
            $this->assertSame($record->price, '100.30');
        }
    }
}

