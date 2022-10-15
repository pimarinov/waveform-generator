<?php
declare(strict_types = 1);

namespace Pimarinov\WaveformGenerator;

use Pimarinov\WaveformGenerator\Data\TalkTimesOfParticipant;

class RawSilenceToTalkTimesInverter
{

    public function __construct(private string $raw)
    {

    }

    public function collect(): TalkTimesOfParticipant
    {
        $collection = new TalkTimesOfParticipant();

        foreach ($this->getPeriods() as $period)
        {
            list($start, $end) = $period;

            $collection->addTimeBySilance($start, $end);
        }

        return $collection;
    }

    private function getPeriods(): array
    {
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
}
