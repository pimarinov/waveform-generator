<?php
declare(strict_types = 1);

namespace Pimarinov\WaveformGenerator\Cli;

use Pimarinov\WaveformGenerator\Data\WaveformCliArgs;
use Pimarinov\WaveformGenerator\Data\Waveform;
use Pimarinov\WaveformGenerator\RawSilenceToTalkTimesInverter;
use Pimarinov\WaveformGenerator\WaveformGenerator;

class Handler
{

    public function __construct(private WaveformCliArgs $args)
    {

    }

    public function execute(): Waveform
    {
        list($userRaw, $customerRaw) = $this->getRawFromFiles();

        $userConverter = new RawSilenceToTalkTimesInverter($userRaw);
        $user = $userConverter->collect();

        $customerConverter = new RawSilenceToTalkTimesInverter($customerRaw);
        $customer = $customerConverter->collect();

        $generator = new WaveformGenerator($user, $customer);

        return $generator->generate();
    }

    private function getRawFromFiles(): array
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
