<?php

class UtilsTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $GLOBALS['_SESSION'] = array();
    }

    public function testArrayGet()
    {
        $array = array(1, 2, 3);
        $assoc = array('a' => 1, 'b' => 2, 'c' => 3);
        $this->assertSame(2, array_get($array, 1));
        $this->assertSame(null, array_get($array, -1));
        $this->assertSame('wat', array_get($array, -1, 'wat'));
        $this->assertSame(3, array_get($assoc, 'c'));
    }

    public function testSessionFlash()
    {
        $this->assertSame('', session_flash('notice'));
        $this->assertSame('a', session_flash('notice', 'a'));
        $this->assertSame('a', session_flash('notice'));
        $this->assertSame('', session_flash('notice'));
    }

    public function testUrl()
    {
        $_SERVER['SERVER_PORT'] = '80';
        $_SERVER['SERVER_NAME'] = 'example.com';
        $_SERVER['HTTP_HOST'] = 'example.com';
        $_SERVER['REQUEST_URI'] = '/foo/bar?q=yes';

        $this->assertSame(
            'http://example.com/foo/bar?q=yes',
            url()
        );
        $this->assertSame(
            'http://example.com/foo',
            url('/foo')
        );
        $this->assertSame(
            'http://example.com/foo?q=no',
            url('/foo', array('q' => 'no'))
        );
        $this->assertSame(
            'http://example.com/foo/2',
            url('2')
        );
        $this->assertSame(
            'http://example.com/bar',
            url('../bar')
        );
        $this->assertSame(
            'http://example.com/foo/2?q=n',
            url('2', array('q' => 'n'))
        );
        $this->assertSame(
            'http://google.com/foo',
            url('/foo', 'http://google.com')
        );
        $this->assertSame(
            'http://google.com/?q=wat',
            url('', array('q' => 'wat'), 'http://google.com')
        );
    }
}
