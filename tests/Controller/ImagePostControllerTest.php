<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImagePostControllerTest extends WebTestCase
{
    public function testCreate()
    {
        // Create client
        $client = static::createClient();

        $uploadedFile = new UploadedFile(
            __DIR__.'/../fixtures/navi.jpg',
            'navi,jpg'
        );
        $client->request('POST', '/api/images', [], [
            'file' => $uploadedFile
        ]);

        $this->assertResponseIsSuccessful();
        $transport = self::$container->get('messenger.transport.async_priority_high');

        $this->assertCount(1, $transport->get());
    }
}