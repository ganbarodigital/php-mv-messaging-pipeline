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
 * @package   MessagingMiddleware/Requirements
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2017-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://ganbarodigital.github.io/php-mv-messaging-middleware
 */

namespace GanbaroDigitalTest\MessagingMiddleware\V1\Requirements;

use GanbaroDigital\Defensive\V1\Interfaces\Requirement;
use GanbaroDigital\Defensive\V1\Interfaces\ListRequirement;
use GanbaroDigital\MessagingMiddleware\V1\Requirements\RequireValidHmacAlgorithm;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass GanbaroDigital\MessagingMiddleware\V1\Requirements\RequireValidHmacAlgorithm
 */
class RequireValidHmacAlgorithmTest extends TestCase
{
    /**
     * @coversNothing
     */
    public function test_can_instantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $unit = new RequireValidHmacAlgorithm();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(RequireValidHmacAlgorithm::class, $unit);
    }

    /**
     * @covers ::apply
     * @covers ::to
     */
    public function test_returns_nothing_if_algorithm_is_supported()
    {
        // ----------------------------------------------------------------
        // setup your test
        //
        // the list of valid algorithms depends upon the version of PHP
        // that we are using when these tests are run
        //
        // the safest way to make sure this test always has a valid algorithm
        // is to ask PHP which algorithms it supports at runtime

        $hmacAlgo = hash_algos()[0];

        // ----------------------------------------------------------------
        // perform the change

        RequireValidHmacAlgorithm::apply()->to($hmacAlgo);

        // ----------------------------------------------------------------
        // test the results
        //
        // if we get here, then we know that the Requirement has accepted
        // our data
        //
        // we need to do some sort of assert here to keep PHPUnit happy :)

        $this->assertTrue(true);
    }

    /**
     * @covers ::apply
     * @covers ::to
     * @expectedException GanbaroDigital\MessagingMiddleware\V1\Exceptions\UnsupportedHmacAlgorithm
     */
    public function test_throws_UnsupportedHmacAlgorithm_if_cipher_is_not_supported()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequireValidHmacAlgorithm::apply()->to('never a hash algorithm');

        // ----------------------------------------------------------------
        // test the results
    }

    /**
     * @coversNothing
     */
    public function test_is_Requirement()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $unit = new RequireValidHmacAlgorithm();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(Requirement::class, $unit);
    }

    /**
     * @coversNothing
     */
    public function test_is_ListRequirement()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $unit = new RequireValidHmacAlgorithm();

        // ----------------------------------------------------------------
        // test the results

        $this->assertInstanceOf(ListRequirement::class, $unit);
    }

    /**
     * @covers ::apply
     * @covers ::toList
     * @expectedException GanbaroDigital\MessagingMiddleware\V1\Exceptions\UnsupportedHmacAlgorithm
     */
    public function test_can_use_as_ListCheck()
    {
        // ----------------------------------------------------------------
        // setup your test
        //
        // the list of valid algorithms depends upon the version of PHP
        // that we are using when these tests are run
        //
        // the safest way to make sure this test always has a valid algorithm
        // is to ask PHP which algorithms it supports at runtime

        $hmacAlgo = hash_algos()[0];

        $list = [
            $hmacAlgo,
            'never a hash algorithm',
        ];

        // ----------------------------------------------------------------
        // perform the change

        RequireValidHmacAlgorithm::apply()->toList($list);

        // ----------------------------------------------------------------
        // test the results
    }

    /**
     * @covers ::apply
     * @covers ::to
     * @dataProvider provideNonStrings
     * @expectedException TypeError
     */
    public function test_rejects_non_strings_in_strict_typing($input)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RequireValidHmacAlgorithm::apply()->to($input);

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