<?php

use CodeIgniter\I18n\Time;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class TimeFactoryTest extends CIUnitTestCase
{
    public function testCreateFromTimestampPreservesFractionalSeconds(): void
    {
        $time = Time::createFromTimestamp(0.5);

        $this->assertSame('1970-01-01 00:00:00.500000', $time->format('Y-m-d H:i:s.u'));
    }

    public function testCreateFromTimestampPreservesNegativeFractionsAndTimezone(): void
    {
        $time = Time::createFromTimestamp(-0.5, 'Asia/Jakarta');

        $this->assertSame('1970-01-01 06:59:59.500000', $time->format('Y-m-d H:i:s.u'));
    }
}
