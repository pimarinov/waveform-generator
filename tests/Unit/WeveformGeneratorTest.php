<?php

declare(strict_types=1);

namespace Pimarinov\WaveformGenerator\Test\Unit;

use Pimarinov\WaveformGenerator\Data\Waveform;
use Pimarinov\WaveformGenerator\WaveformGenerator;
use Pimarinov\WaveformGenerator\RawSilenceToTalkTimesInverter;
use PHPUnit\Framework\TestCase;

class WeveformGeneratorTest extends TestCase
{
    /** @test */
    public function waveform_generate_succeeded(): void
    {
        $userFile = __DIR__ . '/../dummy-raw-silence/user.dummy-raw-silence.txt';
        $customerFile = __DIR__ . '/../dummy-raw-silence/customer.dummy-raw-silence.txt';

        $userRaw = file_get_contents($userFile);
        $customerRaw = file_get_contents($customerFile);

        $userTalkTimes = (new RawSilenceToTalkTimesInverter($userRaw))
            ->collect();
        
        $customerTalkTimes = (new RawSilenceToTalkTimesInverter($customerRaw))
            ->collect();

        $generator = new WaveformGenerator($userTalkTimes, $customerTalkTimes);

        $result = $generator->generate();

        $this->assertInstanceOf(Waveform::class, $result);
    }
}
