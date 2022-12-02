<?php
declare(strict_types=1);

namespace Pimarinov\WaveformGenerator\Data;

interface TalkTimeable
{
    public function addTimeBySilance(float $silanceStart, float $silanceStop): void;
}
