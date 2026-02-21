<?php

namespace AbdulmatinSanni\LaravelOneTimePasscode\Support\PasscodeGenerators;

abstract class OneTimePasscodeGenerator
{
    public function __construct(protected ?int $length)
    {
        $this->length = $length ?? config('one-time-passcode.length');
    }

    public function length(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    abstract public function generate(): string;
}
