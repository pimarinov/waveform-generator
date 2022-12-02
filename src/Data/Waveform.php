<?php
declare(strict_types=1);

namespace Pimarinov\WaveformGenerator\Data;

class Waveform
{

    public function __construct(
        public float $longest_user_monologue, public float $longest_customer_monologue,
        public float $user_talk_percentage, public array $user, public array $customer,
    )
    {

    }

    public function json(int $flags = 0): string
    {
        return json_encode($this, $flags);
    }
}
