<?php

namespace AbdulmatinSanni\LaravelOneTimePasscode\Support\PasscodeGenerators;

use Random\RandomException;

class AlphaNumericOneTimePasscodeGenerator extends OneTimePasscodeGenerator
{
    /**
     * @throws RandomException
     */
    public function generate(): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);

        return implode('', array_map(
            fn ($byte) => $characters[ord($byte) % $charactersLength],
            str_split(random_bytes($this->length))
        ));
    }
}
