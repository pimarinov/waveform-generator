<?php
declare(strict_types = 1);

namespace Pimarinov\WaveformGenerator;

use Pimarinov\WaveformGenerator\Data\TalkTimes;
use Pimarinov\WaveformGenerator\Data\Waveform;

class WaveformGenerator
{

    public function __construct(
        private TalkTimes $user, private TalkTimes $customer
    )
    {

    }

    public function getWaveform(): Waveform
    {
        return new Waveform(
            $this->user->longest,
            $this->customer->longest,
            $this->getUserTalkPercentage(),
            $this->user->times,
            $this->customer->times,
        );
    }

    private function getUserTalkPercentage(): float
    {
        $speakersTotal = ($this->user->total + $this->customer->total);

        if (empty($speakersTotal))
        {
            return 0.00;
        }

        $persentage = ($this->user->total / $speakersTotal) * 100;

        return (float) number_format($persentage, 2);
    }
}
