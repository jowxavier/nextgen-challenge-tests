<?php

use Headers\DisplayHeaders;
use Headers\Response\Cookie;
use Headers\Response\Expires;

class DisplayHeadersIntegrateTest extends PHPUnit\Framework\TestCase
{
    public function testDisplayHeadersShouldIntegrateWithCookieClass()
    {
        $expiresMock = \Mockery::mock(Expires::class);
        $expiresMock->expects()->get()->andReturn('2 hours 20 min 38 seconds');        
        $expiresMock->shouldReceive('hours->minutes')->andReturn($expiresMock);

        $cookie1 = new Cookie('name1', 'value123456');
        $cookie2 = new Cookie('name2', 'value678912332', new DateTimeImmutable('2023-02-06 13:00:00'));
        $cookie2->setExpires($expiresMock->hours()->minutes());

        $displayHeaders = new DisplayHeaders();
        $displayHeaders->add($cookie1);
        $displayHeaders->add($cookie2);

        $result = $displayHeaders->getHeaderString();

        $this->assertEquals(<<<HEADERS
Set-Cookie: name1=value123456
Set-Cookie: name2=value678912332; Expires=Mon, 06 Feb 2023 15:20:38 GMT
HEADERS, $result);
    }

    public function testDisplayHeadersShouldIntegrateWithCookieClassWithExpires()
    {
        $expiresMock1 = \Mockery::mock(Expires::class);
        $expiresMock1->expects()->get()->andReturn('1 day 2 hours 42 minutes 29 seconds');        
        $expiresMock1->shouldReceive('hours->minutes')->andReturn($expiresMock1);

        $expiresMock2 = \Mockery::mock(Expires::class);
        $expiresMock2->expects()->get()->andReturn('2 hours 20 min 38 seconds');        
        $expiresMock2->shouldReceive('hours->minutes')->andReturn($expiresMock2);

        $cookie1 = new Cookie('name1', 'value123456', new DateTimeImmutable('2023-02-06 13:00:00'));
        $cookie1->setExpires($expiresMock1->hours()->minutes());

        $cookie2 = new Cookie('name2', 'value678912332', new DateTimeImmutable('2023-02-06 13:00:00'));
        $cookie2->setExpires($expiresMock2->hours()->minutes());

        $displayHeaders = new DisplayHeaders();
        $displayHeaders->add($cookie1);
        $displayHeaders->add($cookie2);

        $result = $displayHeaders->getHeaderString();

        $this->assertEquals(<<<HEADERS
Set-Cookie: name1=value123456; Expires=Tue, 07 Feb 2023 15:42:29 GMT
Set-Cookie: name2=value678912332; Expires=Mon, 06 Feb 2023 15:20:38 GMT
HEADERS, $result);
    }

    public function testDisplayHeadersShouldIntegrateWith3CookieClassWithExpires()
    {
        $expiresMock1 = \Mockery::mock(Expires::class);
        $expiresMock1->expects()->get()->andReturn('1 day 2 hours 42 minutes 29 seconds');        
        $expiresMock1->shouldReceive('hours->minutes')->andReturn($expiresMock1);

        $expiresMock2 = \Mockery::mock(Expires::class);
        $expiresMock2->expects()->get()->andReturn('2 hours 20 min 38 seconds');        
        $expiresMock2->shouldReceive('hours->minutes')->andReturn($expiresMock2);

        $expiresMock3 = \Mockery::mock(Expires::class);
        $expiresMock3->expects()->get()->andReturn('1 hours 30 min 22 seconds');        
        $expiresMock3->shouldReceive('hours->minutes')->andReturn($expiresMock3);

        $cookie1 = new Cookie('name1', 'value123456', new DateTimeImmutable('2023-02-06 13:00:00'));
        $cookie1->setExpires($expiresMock1->hours()->minutes());

        $cookie2 = new Cookie('name2', 'value678912332', new DateTimeImmutable('2023-02-06 13:00:00'));
        $cookie2->setExpires($expiresMock2->hours()->minutes());

        $cookie3 = new Cookie('name3', 'valueqwee12334', new DateTimeImmutable('2023-02-06 13:00:00'));
        $cookie3->setExpires($expiresMock3->hours()->minutes());

        $displayHeaders = new DisplayHeaders();
        $displayHeaders->add($cookie1);
        $displayHeaders->add($cookie2);
        $displayHeaders->add($cookie3);

        $result = $displayHeaders->getHeaderString();

        $this->assertEquals(<<<HEADERS
Set-Cookie: name1=value123456; Expires=Tue, 07 Feb 2023 15:42:29 GMT
Set-Cookie: name2=value678912332; Expires=Mon, 06 Feb 2023 15:20:38 GMT
Set-Cookie: name3=valueqwee12334; Expires=Mon, 06 Feb 2023 14:30:22 GMT
HEADERS, $result);
    }

    public function testDisplayHeadersWithoutHeadersShouldThrowAnException()
    {
        $this->expectException(\Exception::class);
        $this->getExpectedExceptionMessage('There is no headers to display');
        
        $displayHeaders = new DisplayHeaders();
        $displayHeaders->getHeaderString();
    }
}