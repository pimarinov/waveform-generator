<?php
declare(strict_types=1);

namespace Pimarinov\WaveformGenerator\Data;

class TalkTimes implements TalkTimeable
{
    public array $times = [];
    public float $total = 0;
    public float $longest = 0;
    private float $recentStartTalk = 0;

    public function addTimeBySilance(float $silanceStart, float $silanceStop): void
    {
        $stopTalk = $silanceStart;

        $this->times[] = [$this->recentStartTalk, $stopTalk];

        $talkPeriod = ($stopTalk - $this->recentStartTalk);

        $this->recentStartTalk = $silanceStop;

        $this->total += $talkPeriod;

        if ($talkPeriod > $this->longest)
        {
            $this->longest = $talkPeriod;
        }
    }
}
