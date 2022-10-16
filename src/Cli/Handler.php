<?php
declare(strict_types = 1);

namespace Pimarinov\WaveformGenerator\Cli;

use Pimarinov\WaveformGenerator\Data\CliHandlerArgs;
use Pimarinov\WaveformGenerator\Data\Waveform;
use Pimarinov\WaveformGenerator\SpeakerTalkTimes;
use Pimarinov\WaveformGenerator\WaveformGenerator;

class Handler
{

    public function __construct(private CliHandlerArgs $args)
    {

    }

    public function execute(): Waveform
    {
        list($userSilenceRaw, $customerSilenceRaw) = $this->getSilenceRawFromFiles();

        $userTalkTimes = (new SpeakerTalkTimes($userSilenceRaw))
            ->getTalkTimes();

        $customerTalkTimes = (new SpeakerTalkTimes($customerSilenceRaw))
            ->getTalkTimes();

        $generator = new WaveformGenerator($userTalkTimes, $customerTalkTimes);

        return $generator->getWaveform();
    }

    private function getSilenceRawFromFiles(): array
    {
        if (!file_exists($this->args->user))
        {
            throw new \Exception('FILE ERROR (user): No such file.');
        }

        if (!file_exists($this->args->customer))
        {
            throw new \Exception('FILE ERROR (customer): No such file.');
        }

        $userFileContent = file_get_contents($this->args->user);
        $customerFileContent = file_get_contents($this->args->customer);

        return [$userFileContent, $customerFileContent];
    }
}
