<?php
declare(strict_types=1);

namespace Pimarinov\WaveformGenerator\Cli;

use Pimarinov\WaveformGenerator\Data\Waveform;
use Pimarinov\WaveformGenerator\SpeakerSilenceToTalkTimes;
use Pimarinov\WaveformGenerator\WaveformGenerator;

class Handler
{
    public function __construct(private string $userFile, private string $customerFile)
    {

    }

    public function execute(): Waveform
    {
        if (!file_exists($this->userFile))
        {
            throw new \Exception('FILE ERROR (user): No such file.');
        }

        if (!file_exists($this->customerFile))
        {
            throw new \Exception('FILE ERROR (customer): No such file.');
        }

        if ($userStream = fopen($this->userFile, 'r')) {
            $userSilenceRaw = stream_get_contents($userStream);
            fclose($userStream);
        }
        if ($customerStream = fopen($this->customerFile, 'r')) {
            $customerSilenceRaw = stream_get_contents($customerStream);
            fclose($customerStream);
        }

        $userTalkTimes = (new SpeakerSilenceToTalkTimes($userSilenceRaw))
            ->getTalkTimes();

        $customerTalkTimes = (new SpeakerSilenceToTalkTimes($customerSilenceRaw))
            ->getTalkTimes();

        $generator = new WaveformGenerator($userTalkTimes, $customerTalkTimes);

        return $generator->getWaveform();
    }
}
