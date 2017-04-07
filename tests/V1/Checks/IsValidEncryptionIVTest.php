<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-present Ganbaro Digital Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   MessagingPipeline/Checks
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-messaging-pipeline
 */

namespace GanbaroDigitalTest\MessagingPipeline\V1\Checks;

use GanbaroDigital\MessagingPipeline\V1\Checks\IsValidEncryptionIV;
use GanbaroDigital\MissingBits\Checks\Check;
use GanbaroDigital\MissingBits\Checks\ListCheck;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass GanbaroDigital\MessagingPipeline\V1\Checks\IsValidEncryptionIV
 */
class IsValidEncryptionIVTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function test_can_instantiate()
    {
        // ----------------------------------------------------------------
        // setup your test
        //
        // the list of valid ciphers depends upon the version of OpenSSL
        // that we are using when these tests are run
        //
        // the safest way to make sure this test always has a valid cipher
        // is to ask OpenSSL which ciphers it supports at runtime

        $cipher = openssl_get_cipher_methods()[0];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new IsValidEncryptionIV($cipher);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(IsValidEncryptionIV::class, $unit);
    }

    /**
     * @covers ::check
     */
    public function test_returns_TRUE_if_correct_IV_for_encryption_cipher()
    {
        // ----------------------------------------------------------------
        // setup your test
        //
        // the list of valid ciphers depends upon the version of OpenSSL
        // that we are using when these tests are run
        //
        // the safest way to make sure this test always has a valid cipher
        // is to ask OpenSSL which ciphers it supports at runtime
        //
        // once we have a valid cipher, we need OpenSSL's support to generate
        // an IV that will be valid for that cipher

        $cipher = openssl_get_cipher_methods()[0];
        $ivLen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivLen);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = IsValidEncryptionIV::check($cipher, $iv);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    /**
     * @covers ::check
     */
    public function test_returns_FALSE_if_cipher_is_not_supported()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = IsValidEncryptionIV::check('never a cipher', 'never an IV');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }

    /**
     * @coversNothing
     */
    public function test_is_Check()
    {
        // ----------------------------------------------------------------
        // setup your test
        //
        // the list of valid ciphers depends upon the version of OpenSSL
        // that we are using when these tests are run
        //
        // the safest way to make sure this test always has a valid cipher
        // is to ask OpenSSL which ciphers it supports at runtime

        $cipher = openssl_get_cipher_methods()[0];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new IsValidEncryptionIV($cipher);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(Check::class, $unit);
    }

    /**
     * @covers ::__construct
     * @covers ::inspect
     */
    public function test_can_use_as_Check()
    {
        // ----------------------------------------------------------------
        // setup your test
        //
        // the list of valid ciphers depends upon the version of OpenSSL
        // that we are using when these tests are run
        //
        // the safest way to make sure this test always has a valid cipher
        // is to ask OpenSSL which ciphers it supports at runtime
        //
        // once we have a valid cipher, we need OpenSSL's support to generate
        // an IV that will be valid for that cipher

        $cipher = openssl_get_cipher_methods()[0];
        $ivLen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivLen);

        $unit = new IsValidEncryptionIV($cipher);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult1 = $unit->inspect($iv);
        $actualResult2 = $unit->inspect('n');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult1);
        $this->assertFalse($actualResult2);
    }

    /**
     * @coversNothing
     */
    public function test_is_ListCheck()
    {
        // ----------------------------------------------------------------
        // setup your test
        //
        // the list of valid ciphers depends upon the version of OpenSSL
        // that we are using when these tests are run
        //
        // the safest way to make sure this test always has a valid cipher
        // is to ask OpenSSL which ciphers it supports at runtime

        $cipher = openssl_get_cipher_methods()[0];

        // ----------------------------------------------------------------
        // perform the change

        $unit = new IsValidEncryptionIV($cipher);

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ListCheck::class, $unit);
    }

    /**
     * @covers ::checkList
     * @covers ::inspectList
     */
    public function test_can_use_as_ListCheck()
    {
        // ----------------------------------------------------------------
        // setup your test
        //
        // the list of valid ciphers depends upon the version of OpenSSL
        // that we are using when these tests are run
        //
        // the safest way to make sure this test always has a valid cipher
        // is to ask OpenSSL which ciphers it supports at runtime
        //
        // once we have a valid cipher, we need OpenSSL's support to generate
        // an IV that will be valid for that cipher

        $cipher = openssl_get_cipher_methods()[0];
        $ivLen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivLen);

        $list1 = [
            $iv,
        ];

        $list2 = [
            'n',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult1 = IsValidEncryptionIV::checkList($cipher, $list1);
        $actualResult2 = IsValidEncryptionIV::checkList($cipher, $list2);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult1);
        $this->assertFalse($actualResult2);
    }

    /**
     * @covers ::check
     * @dataProvider provideNonStrings
     * @expectedException TypeError
     */
    public function test_rejects_non_strings_in_strict_typing($input)
    {
        // ----------------------------------------------------------------
        // setup your test
        //
        // the list of valid ciphers depends upon the version of OpenSSL
        // that we are using when these tests are run
        //
        // the safest way to make sure this test always has a valid cipher
        // is to ask OpenSSL which ciphers it supports at runtime

        $cipher = openssl_get_cipher_methods()[0];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = IsValidEncryptionIV::check($cipher, $input);

        // ----------------------------------------------------------------
        // test the results
        //
        // at this point, the PHP engine should have thrown a TypeError
    }

    public function provideNonStrings()
    {
        return [
            'null' => [ null ],
            'array(empty)' => [ [] ],
            'array(simple)' => [ [ 1, 2, 3, 4 ] ],
            'bool(true)' => [ true ],
            'bool(false)' => [ false ],
            'callable(returns string)' => [ function() { return ''; } ],
            'double(0)' => [ 0.0 ],
            'double(negative)' => [ -100.01 ],
            'double(positive)' => [ 3.1415927 ],
            'int(0)' => [ 0 ],
            'int(negative)' => [ -100 ],
            'int(positive)' => [ 100 ],
            'object(empty)' => [ (object)[] ],
            'resource' => [STDIN],
        ];
    }
}