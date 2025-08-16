<?php

namespace AbdulmatinSanni\LaravelOneTimePasscode\Support\PasscodeGenerators;

use Random\RandomException;

class NumericOneTimePasscodeGenerator extends OneTimePasscodeGenerator
{
    /**
     * @throws RandomException
     */
    public function generate(): string
    {
        $randomNumber = random_int(0, (10 ** $this->length) - 1);

        return str_pad((string) $randomNumber, $this->length, '0', STR_PAD_LEFT);
    }
}
