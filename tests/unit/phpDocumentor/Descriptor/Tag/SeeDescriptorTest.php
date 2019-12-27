<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link http://phpdoc.org
 */

namespace phpDocumentor\Descriptor\Tag;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Fqsen as FqsenReference;
use phpDocumentor\Reflection\Fqsen;

/**
 * Tests the functionality for the SeeDescriptor class.
 */
class SeeDescriptorTest extends MockeryTestCase
{
    /** @var SeeDescriptor $fixture */
    protected $fixture;

    /**
     * Creates a new fixture object.
     */
    protected function setUp() : void
    {
        $this->fixture = new SeeDescriptor('name');
    }

    /**
     * @covers \phpDocumentor\Descriptor\Tag\SeeDescriptor::setReference
     * @covers \phpDocumentor\Descriptor\Tag\SeeDescriptor::getReference
     */
    public function testSetAndGetReference() : void
    {
        $refrence = new FqsenReference(new Fqsen('\someFunction()'));
        $this->fixture->setReference($refrence);
        $result = $this->fixture->getReference();

        $this->assertSame($refrence, $result);
    }
}
