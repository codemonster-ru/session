<?php

use Codemonster\Session\Session;
use Codemonster\Session\Handlers\ArraySessionHandler;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    protected function setUp(): void
    {
        Session::destroy();
    }

    public function testSessionStartsAndStoresData(): void
    {
        Session::start('array');
        Session::put('user', 'Vasya');

        $this->assertSame('Vasya', Session::get('user'));
        $this->assertArrayHasKey('user', Session::all());
    }

    public function testForgetRemovesKey(): void
    {
        Session::start('array');
        Session::put('foo', 'bar');
        Session::forget('foo');

        $this->assertNull(Session::get('foo'));
    }

    public function testAllReturnsAllData(): void
    {
        Session::start('array');
        Session::put('a', 1);
        Session::put('b', 2);

        $all = Session::all();

        $this->assertCount(2, $all);
        $this->assertSame(['a' => 1, 'b' => 2], $all);
    }

    public function testDestroyClearsAllData(): void
    {
        Session::start('array');
        Session::put('key', 'value');
        Session::destroy();

        $this->assertSame([], Session::all());
    }

    public function testCustomHandlerCanBeUsed(): void
    {
        $handler = new ArraySessionHandler();
        $store = new \Codemonster\Session\Store($handler);
        $store->start();
        $store->put('x', 10);

        $this->assertSame(10, $store->get('x'));
    }
}
