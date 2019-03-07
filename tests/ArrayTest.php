<?php
declare(strict_types=1);

namespace Php;

use Helper\TestBase;

final class ArrayTest extends TestBase
{
    public function testArrayMerge()
    {
        $user1 = [
            'id' => 1,
            'user' => [
                'name' => 'Mike',
                'age' => 20,
            ],
        ];
        $user2 = [
            'user' => [
                'name' => 'Jane',
                'from' => 'America',
            ],
        ];

        $merged = array_merge($user1, $user2);
        $expected = [
            'id' => 1,
            'user' => [
                'name' => 'Jane',
                // 階層1の要素を丸ごと上書きするので、arg1の要素は残らない。
                // 要素(配列)を入れ替えていると考えると良い。
                // 'age' => 20,
                'from' => 'America',
            ],

        ];
        $this->assertSame($expected, $merged);

        $merged = array_merge_recursive($user1, $user2);
        $expected = [
            'id' => 1,
            'user' => [
                // 同じkeyの値が存在する場合は、配列になる。
                'name' => ['Mike', 'Jane'],
                'age' => 20,
                'from' => 'America',
            ],

        ];
        $this->assertSame($expected, $merged);
    }

    public function testArrayDiffKey()
    {
        $mike = [
            'name' => 'mike',
            'age' => 20,
        ];
        $jane = [
            'name' => 'Jane',
            'from' => 'America',
        ];

        // arg1から引いていくため、arg2にしかないkeyは戻り値に残らない。
        $this->assertSame(['age' => 20], array_diff_key($mike, $jane));
        $this->assertSame(['from' => 'America'], array_diff_key($jane, $mike));

        // 再帰的にkeyのチェックはしない。1階層までのチェック。
        $user1['user'] = $mike;
        $user2['user'] = $jane;
        $this->assertEmpty(array_diff_key($user1, $user2));
        $this->assertEmpty(array_diff_key($user2, $user1));
    }
}
