<?php

namespace Tests\Validator;

use BpDailyMenu\Validator\DateIntervalValidator;
use PHPUnit\Framework\TestCase;

class DateIntervalValidatorTest extends TestCase {

    /**
     * @test
     */
    public function invoke_givenValidInterval_returnsEmptyArray() {
        $errors = (new DateIntervalValidator)('2018-10-01', '2018-10-03');
        $this->assertEquals([], $errors);
    }
}