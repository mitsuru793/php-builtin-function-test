<?php
declare(strict_types=1);

namespace Php;

use PHPUnit\Framework\TestCase;

final class CastTest extends TestCase
{
    public function testSelectNotPublicProperty()
    {
        $o = new class {
            public $pub;
            protected $pro;
            private $pri;
        };
        $this->assertSame(['pub' => null], get_object_vars($o));

        // objectをararyにキャストすると非publicなプロパティも抽出される。
        // 無名クラスだと、非publicのkey値にprefixがつく
        //   "pub"
        //   "\x00*\x00pro"
        //   "\x00class@anonymous\x00/Users/mitsuru/code/project/php/github.com/mitsuru793/php-builtin-function-test/tests/CastTest.php0x10f692121\x00pri"
        $keys = array_keys((array)$o);
        foreach ($keys as $key) {
            $this->assertRegExp('/pub|.+pro$|.+pri$/', $key);
        }

        // 無名クラスではなくとも、非publicにはprefixが付く
        $keys = array_keys((array)new DummyClass());
        foreach ($keys as $key) {
            $this->assertRegExp('/pub|.+pro$|.+pri$/', $key);
        }
    }
}

class DummyClass
{
    public $pub;
    protected $pro;
    private $pri;
}
