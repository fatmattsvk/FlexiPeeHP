<?php

namespace Test\FlexiPeeHP;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2017-06-25 at 23:33:48.
 */
class FormatsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Formats
     */
    protected $object;

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers FlexiPeeHP\Formats::byContentType
     */
    public function testByContentType()
    {
        $contentTypes = \FlexiPeeHP\Formats::byContentType();
        $this->assertEquals('application/javascript', key($contentTypes));
    }

    /**
     * @covers FlexiPeeHP\Formats::bySuffix
     */
    public function testBySuffix()
    {
        $suffixes = \FlexiPeeHP\Formats::bySuffix();
        $this->assertEquals('js', key($suffixes));
    }

    /**
     * @covers FlexiPeeHP\Formats::suffixToContentType
     */
    public function testSuffixToContentType()
    {
        $this->assertEquals('application/pdf',
            \FlexiPeeHP\Formats::suffixToContentType('pdf'));
    }

    /**
     * @covers FlexiPeeHP\Formats::contentTypeToSuffix
     */
    public function testContentTypeToSuffix()
    {
        $this->assertEquals('text/csv',
            \FlexiPeeHP\Formats::suffixToContentType('csv'));
    }
}
