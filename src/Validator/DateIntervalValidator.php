<?php

namespace BpDailyMenu\Validator;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class DateIntervalValidator {

    public function __invoke(string $from, string $to): ?string {
        try {
            $this->assertDate($from);
            $this->assertDate($to);
        } catch (NestedValidationException $exception) {
            return $exception->getFullMessage();
        }
        return null;
    }

    private function assertDate(string $date): void {
        Validator::stringType()->date('Y-m-d')->assert($date);
    }
}