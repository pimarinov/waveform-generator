<?php
declare(strict_types=1);

namespace Pimarinov\WaveformGenerator\Cli;

use Pimarinov\WaveformGenerator\Data\Waveform;
use Pimarinov\WaveformGenerator\SpeakerTalkTimes;
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

        $userSilenceRaw = file_get_contents($this->userFile);

        $customerSilenceRaw = file_get_contents($this->customerFile);

        $userTalkTimes = (new SpeakerTalkTimes($userSilenceRaw))
            ->getTalkTimes();

        $customerTalkTimes = (new SpeakerTalkTimes($customerSilenceRaw))
            ->getTalkTimes();

        $generator = new WaveformGenerator($userTalkTimes, $customerTalkTimes);

        return $generator->getWaveform();
    }
}
