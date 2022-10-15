<?php

namespace Pimarinov\WaveformGenerator\Data;

class Waveform
{

    public function __construct(
        public float $longest_user_monologue, public float $longest_customer_monologue,
        public float $user_talk_percentage, public array $user, public array $customer,
    )
    {

    }
}
