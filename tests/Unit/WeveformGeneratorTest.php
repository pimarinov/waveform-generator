<?php

declare(strict_types=1);

namespace Pimarinov\WaveformGenerator\Test\Unit;

use PHPUnit\Framework\TestCase;
use Pimarinov\WaveformGenerator\Data\Waveform;
use Pimarinov\WaveformGenerator\SpeakerSilenceToTalkTimes;
use Pimarinov\WaveformGenerator\WaveformGenerator;

class WeveformGeneratorTest extends TestCase
{
    public function test_waveform_succeeded(): void
    {
        $userFile = __DIR__ . '/../dummy-raw-silence/user.dummy-raw-silence.txt';
        $customerFile = __DIR__ . '/../dummy-raw-silence/customer.dummy-raw-silence.txt';

        $userRaw = file_get_contents($userFile);
        $customerRaw = file_get_contents($customerFile);

        $userTalkTimes = (new SpeakerSilenceToTalkTimes($userRaw))
            ->getTalkTimes();

        $customerTalkTimes = (new SpeakerSilenceToTalkTimes($customerRaw))
            ->getTalkTimes();

        $generator = new WaveformGenerator($userTalkTimes, $customerTalkTimes);

        $result = $generator->getWaveform();

        $this->assertInstanceOf(Waveform::class, $result);

        $expectedJson = '{"longest_user_monologue":2,"longest_customer_monologue":2,' .
            '"user_talk_percentage":50,"user":[[0,0],[2,4],[6,8]],' .
            '"customer":[[0,2],[4,6]]}';

        $this->assertEquals($expectedJson, $result->json());
    }

    public function test_waveform_call_with_wrong_user_arguments(): void
    {
        $customerTalkTimes = (new SpeakerSilenceToTalkTimes(''))
            ->getTalkTimes();

        $this->expectException(\TypeError::class);
        new WaveformGenerator(new \stdClass(), $customerTalkTimes);
    }

    public function test_waveform_call_with_wrong_customer_arguments(): void
    {
        $userTalkTimes = (new SpeakerSilenceToTalkTimes(''))
            ->getTalkTimes();

        $this->expectException(\TypeError::class);
        new WaveformGenerator($userTalkTimes, new \stdClass());
    }

    public function test_waveform_cli_handler_missing_args(): void
    {
        $this->expectException(\ArgumentCountError::class);
        new WaveformGenerator();
    }

    public function test_waveform_cli_handler_missing_second_arg(): void
    {
        $userTalkTimes = (new SpeakerSilenceToTalkTimes(''))
            ->getTalkTimes();

        $this->expectException(\ArgumentCountError::class);
        new WaveformGenerator($userTalkTimes);
    }

    public function test_waveform_call_with_empty_silence_raws(): void
    {
        $userTalkTimes = (new SpeakerSilenceToTalkTimes(''))
            ->getTalkTimes();

        $customerTalkTimes = (new SpeakerSilenceToTalkTimes(''))
            ->getTalkTimes();

        $generator = new WaveformGenerator($userTalkTimes, $customerTalkTimes);

        $result = $generator->getWaveform();

        $this->assertEquals(new Waveform(0.00, 0.00, 0.00, [], []), $result);

        $expectedJson = '{"longest_user_monologue":0,"longest_customer_monologue":0,' .
            '"user_talk_percentage":0,"user":[],"customer":[]}';

        $this->assertEquals($expectedJson, $result->json());
    }
}
