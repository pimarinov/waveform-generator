<?php
declare(strict_types = 1);

namespace Pimarinov\WaveformGenerator;

use Pimarinov\WaveformGenerator\Data\TalkTimes;

class SpeakerTalkTimes
{

    public function __construct(private string $raw)
    {

    }

    public function getTalkTimes(): TalkTimes
    {
        $talkTimes = new TalkTimes();

        foreach ($this->getPeriods() as $period)
        {
            list($start, $end) = $period;

            $talkTimes->addTimeBySilance($start, $end);
        }

        return $talkTimes;
    }

    private function getPeriods(): array
    {
        if (false === $this->silenceAnnotationsExistsInRaw())
        {
            return [];
        }

        $starts = $ends = [];

        $rows = explode("\n", $this->raw);

        foreach ($rows as $key => $row)
        {
            if (empty($row))
            {
                continue;
            }

            if ($key % 2 == 0)
            {
                $starts[] = explode('_start: ', $row)[1];
            } else
            {
                $splitted = explode('_end: ', $row)[1];

                $ends[] = explode(' ', $splitted)[0];
            }
        }

        $periods = [];

        foreach ($starts as $k => $v)
        {
            $periods[] = [(float) $v, (float) $ends[$k]];
        }

        return $periods;
    }

    private function silenceAnnotationsExistsInRaw(): bool
    {
        return false !== strpos($this->raw, 'silence_start:') 
            && false !== strpos($this->raw, 'silence_end:');
    }
}
