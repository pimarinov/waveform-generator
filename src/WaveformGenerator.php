<?php
declare(strict_types = 1);

namespace Pimarinov\WaveformGenerator;

use Pimarinov\WaveformGenerator\Data\TalkTimesOfParticipant;
use Pimarinov\WaveformGenerator\Data\Waveform;

class WaveformGenerator
{

    public function __construct(
        private TalkTimesOfParticipant $user, private TalkTimesOfParticipant $customer
    )
    {

    }

    public function generate(): Waveform
    {
        return new Waveform(
            $this->user->longest, $this->customer->longest,
            $this->getUserTalkPercentage(), $this->user->times, $this->customer->times,
        );
    }

    private function getUserTalkPercentage(): float
    {
        $participantsTotal = ($this->user->total + $this->customer->total);

        $persentage = ($this->user->total / $participantsTotal) * 100;

        return (float) number_format($persentage, 2);
    }
}
