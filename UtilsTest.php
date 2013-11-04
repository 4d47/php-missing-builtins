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

    public function testIsCli()
    {
        $this->assertTrue(is_cli());
    }

    public function testPrFromCli()
    {
        $this->expectOutputString('cats <and> dogs');
        pr('cats <and> dogs');
    }

    public function testPrFromNonCli()
    {
        if (!extension_loaded('runkit')) {
            $this->markTestSkipped('runkit extension unavailable');
            return;
        }
        runkit_function_redefine('is_cli', '', 'return false;');
        $this->expectOutputString('<pre>cats &lt;and&gt; dogs</pre>');
        pr('cats <and> dogs');
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

    public function testFlash()
    {
        $this->assertSame('', flash('notice'));
        $this->assertSame('a', flash('notice', 'a'));
        $this->assertSame('a', flash('notice'));
        $this->assertSame('', flash('notice'));
    }
}
