<?php

namespace Tests\Validator;

use BpDailyMenu\Validator\DateIntervalValidator;
use PHPUnit\Framework\TestCase;

class DateIntervalValidatorTest extends TestCase {

    /**
     * @test
     */
    public function invoke_givenValidInterval_returnsEmptyArray() {
        $error = (new DateIntervalValidator)('2018-10-01', '2018-10-03');
        $this->assertNull($error);
    }

    /**
     * @test
     */
    public function invoke_givenInvalidFromDate_containsError() {
        $error = (new DateIntervalValidator)('2018-1001', '2018-10-03');
        $this->assertNotNull($error);
    }

    /**
     * @test
     */
    public function invoke_givenInvalidToDate_containsError() {
        $error = (new DateIntervalValidator)('2018-10-01', 'fdg');
        $this->assertNotNull($error);
    }
}