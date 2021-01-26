<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
    * @dataProvider urlProvider
    */
    public function testPageIsSuccessful($url,$expectedStatusCode)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }

    public function urlProvider()
    {
        yield ['/', Response::HTTP_OK];
        yield ['/trick/1-Nose-Grab-1', Response::HTTP_OK];
        yield ['/trick/199-Nose-Grab-199', Response::HTTP_NOT_FOUND];
        yield ['/login', Response::HTTP_OK];
        yield ['/register', Response::HTTP_OK];
        yield ['/editrick/1', Response::HTTP_FOUND];
    }
}
