<?php

class UtilsTest extends PHPUnit_Framework_TestCase
{
    public function testArrayGet()
    {
        $array = array(1, 2, 3);
        $assoc = array('a' => 1, 'b' => 2, 'c' => 3);
        $this->assertSame(2, array_get($array, 1));
        $this->assertSame(null, array_get($array, -1));
        $this->assertSame('wat', array_get($array, -1, 'wat'));
        $this->assertSame(3, array_get($assoc, 'c'));
    }

    public function testTruncate()
    {
        $this->assertSame('fo...', truncate('foo', 2));
        $this->assertSame('fo', truncate('foo', 2, ''));
        $this->assertSame('', truncate('', 12));
        $this->assertSame('bé', truncate('bébé', 2, ''));
    }

    public function testSlugify()
    {
        $this->assertSame('hello-world', slugify('Hello World'));
        $this->assertSame('bebe', slugify('bébé', 'UTF-8'));
        $this->assertSame('cote-divoire', slugify("Côte d'îvoire", 'UTF-8'));
        $this->assertSame('is-c-really-a-language-', slugify('Is C# really a language ?'));
        $this->assertSame('underscore-is-not-the-answer', slugify('Underscore_is_not_the_answer'));
    }

    public function testSessionFlash()
    {
        $_SESSION = array();
        $this->assertSame('', session_flash('notice'));
        $this->assertSame('a', session_flash('notice', 'a'));
        $this->assertSame('a', session_flash('notice'));
        $this->assertSame('', session_flash('notice'));
    }

    public function testUrl()
    {
        $_SERVER = array(
            'HTTPS' => 'on',
            'SERVER_PORT' => 443,
            'HTTP_HOST' => 'example.com',
            'SERVER_NAME' => 'example.com',
            'REQUEST_URI' => '/a/b',
        );
        $this->assertSame('https://example.com/a/b', url());
        $this->assertSame('https://example.com/', url('/'));
        $this->assertSame('https://example.com/?q=yes', url('/', array('q' => 'yes')));
        $this->assertSame('https://example.com/?q=yes&h=12', url('/', array('q' => 'yes', 'h' => '12')));
        // $this->assertSame('https://example.com/a', url('..'));
    }
}
