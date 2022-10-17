<?php
declare(strict_types = 1);

namespace Pimarinov\WaveformGenerator\Test\Unit;

use PHPUnit\Framework\TestCase;
use Pimarinov\WaveformGenerator\Data\TalkTimes;
use Pimarinov\WaveformGenerator\SpeakerTalkTimes;

class SpeakerTalkTimesTest extends TestCase
{

    public function test_speaker_talk_times_with_wrong_content(): void
    {
        $talkTimes = new SpeakerTalkTimes('');

        $this->assertEquals(new TalkTimes(), $talkTimes->getTalkTimes());
    }

    public function test_speaker_talk_times_missing_arg(): void
    {
        $this->expectException(\ArgumentCountError::class);
        new SpeakerTalkTimes();
    }
}
