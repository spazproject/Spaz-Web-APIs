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
		
		$timeobj = $resp['body'];
		
		$this->assertNotNull($timeobj->time);
		$this->assertNotNull($timeobj->time->tz);
		$this->assertNotNull($timeobj->time->datetime);
		$this->assertNotNull($timeobj->time->hour);
		$this->assertNotNull($timeobj->time->minute);
		$this->assertNotNull($timeobj->time->second);
		
		
		$resp = $rest->get(
			self::API_URL."/time/",
			array('tz'=>'America/Indianapolis')
		);
		
		$timeobj = $resp['body'];
		$this->assertTrue($timeobj->time->tz === 'America/Indianapolis');
		
	}
	
	
	public function testOfUrlInfo() {
		$info_url = "http://getspaz.com";
		
		$rest = new Resty();
		
		$resp = $rest->get(
			self::API_URL."/url/info",
			array('url'=>$info_url)
		);
		
		$url_info = $resp['body'];
		$headers  = $resp['headers'];

		$this->assertEqual($url_info->resolved_url, $info_url);
		$this->assertEqual($url_info->content_type, "text/html; charset=UTF-8");
		$this->assertEqual($url_info->http_code, 200);

		$this->assertEqual($headers['Content-Type'], 'application/json; charset=utf-8');
	}

	
	public function testOfUrlTitle() {
		$info_url = "http://getspaz.com";
		
		$rest = new Resty();
		
		$resp = $rest->get(
			self::API_URL."/url/title",
			array('url'=>$info_url)
		);
		
		$url_info = $resp['body'];
		$headers  = $resp['headers'];

		$this->assertEqual($url_info->url, "http://getspaz.com");
		$this->assertEqual(strpos($url_info->title, "Spaz: A Twitter, Identi.ca and StatusNet client"), 0);
	}
	
	
	public function testOfUrlResolve() {
		$rest = new Resty();
		
		$resp = $rest->get(
			self::API_URL."/url/resolve",
			array('url'=>'http://j.mp/spazwebosbeta')
		);
		
		$resolved = $resp['body'];

		$this->assertEqual($resolved->passed_url, "http://j.mp/spazwebosbeta");
		$this->assertEqual($resolved->final_url, "https://developer.palm.com/appredirect/?packageid=com.funkatron.app.spaz-beta&applicationid=4928");
		$this->assertTrue($resolved->redirects >= 1);
	}
	
}
