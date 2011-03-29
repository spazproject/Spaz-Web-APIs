<?php
include 'simpletest/autorun.php';
include 'lib/Resty.php';

/**
* 
*/
class SpazApiTests extends UnitTestCase
{
	
	const API_URL = "http://dev-api.getspaz.com";	
	
	public function testOfTime() {
		
		$rest = new Resty();
		
		$resp = $rest->get(self::API_URL."/time");
		
		$timeobj = json_decode($resp['body']);
		
		$this->assertNotNull($timeobj->time);
		$this->assertNotNull($timeobj->time->tz);
		$this->assertNotNull($timeobj->time->datetime);
		$this->assertNotNull($timeobj->time->hour);
		$this->assertNotNull($timeobj->time->minute);
		$this->assertNotNull($timeobj->time->second);
		
		
		$resp = $rest->get(self::API_URL."/time/?tz=America/Indianapolis");
		$timeobj = json_decode($resp['body']);
		$this->assertTrue($timeobj->time->tz === 'America/Indianapolis');
		
	}
	
}
