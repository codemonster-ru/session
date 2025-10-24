<?php

use Codemonster\Session\Handlers\ArraySessionHandler;
use Codemonster\Session\Store;
use PHPUnit\Framework\TestCase;

class ArraySessionHandlerTest extends TestCase
{
    public function testReadWriteAndDestroy(): void
    {
        $handler = new ArraySessionHandler();
        $store = new Store($handler);
        $store->start();

        $store->put('token', '123');
        $this->assertSame('123', $store->get('token'));

        $store->forget('token');
        $this->assertNull($store->get('token'));
    }

    public function testHandlerStoresJsonEncodedData(): void
    {
        $handler = new ArraySessionHandler();
        $handler->write('abc', json_encode(['x' => 1, 'y' => 2]));
        $json = $handler->read('abc');

        $this->assertStringContainsString('"x":1', $json);
        $this->assertStringContainsString('"y":2', $json);
    }
}
