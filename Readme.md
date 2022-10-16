
# Waveform Generator - Jiminny [Backend Task](jiminny-backend-task.md)

The goal of this package is to make Waveform object from the silence raw data
of both conversation participants (User & Customer).

## Installation

Install via Composer:

```bash
composer require pimarinov/waveform-generator
```

## Usage

You may extend the `Pimarinov\WaveformGenerator\WaveformGenerator`. Then pass both callers
talk times (inverted by `Pimarinov\WaveformGenerator\SpeakerTalkTimes` from silence raw).

```php
use Pimarinov\WaveformGenerator\SpeakerTalkTimes;
use Pimarinov\WaveformGenerator\WaveformGenerator;

(function ($userRawSilenceFilePath, $customerRawSilenceFilePath) {

    $userRaw = file_get_contents($userRawSilenceFilePath);
    $customerRaw = file_get_contents($customerRawSilenceFilePath);

    $userTalkTimes = (new SpeakerTalkTimes($userRaw))
        ->getTalkTimes();

    $customerTalkTimes = (new SpeakerTalkTimes($customerRaw))
        ->getTalkTimes();

    return (new WaveformGenerator($userTalkTimes, $customerTalkTimes))
            ->getWaveform();

})(): Waveform

```

Alternative way of a custom `MyWaveformGenerator`:

```php
use Pimarinov\WaveformGenerator\SpeakerTalkTimes;
use Pimarinov\WaveformGenerator\WaveformGenerator;

class MyWaveformGenerator
{
    private string $userRaw;
    private string $customerRaw;

    public function __construct(private string $userRawFile, private string $customerRawFile)
    {
        $this->userRaw = file_get_contents($this->userRawFile);
        $this->customerRaw = file_get_contents($this->customerRawFile);
    }

    public function getWaveform(): \Pimarinov\WaveformGenerator\Data\Waveform
    {
        $userTalkTimes = (new SpeakerTalkTimes($this->userRaw))
            ->getTalkTimes();

        $customerTalkTimes = (new SpeakerTalkTimes($this->customerRaw))
            ->getTalkTimes();

        return (new WaveformGenerator($user, $customer))
            ->getWaveform();
    }
}
```

![Laravel](laravel-call-screenshot.png)

## CLI

This package has built in cli handler, allowing calls via Console:

```bash
php bin/waveform.php --user tests/dummy-raw-silence/user.dummy-raw-silence.txt --customer tests/dummy-raw-silence/customer.dummy-raw-silence.txt
```

Output:

```bash
Pimarinov\WaveformGenerator\Data\Waveform Object
(
    [longest_user_monologue] => 2
    [longest_customer_monologue] => 2
    [user_talk_percentage] => 50
    [user] => Array
        (
            [0] => Array
                (
                    [0] => 0
                    [1] => 0
                )

            [1] => Array
                (
                    [0] => 2
                    [1] => 4
                )

            [2] => Array
                (
                    [0] => 6
                    [1] => 8
                )

        )

    [customer] => Array
        (
            [0] => Array
                (
                    [0] => 0
                    [1] => 2
                )

            [1] => Array
                (
                    [0] => 4
                    [1] => 6
                )

        )

)
```

## Testing

```bash
vendor/bin/phpunit --coverage-text
```

Output
```bash
PHPUnit 10.0-gce5b6af0c by Sebastian Bergmann and contributors.

Runtime:       PHP 8.1.9 with Xdebug 3.1.3
Configuration: C:\dev\waveform-generator\phpunit.xml.dist

....                                                                4 / 4 (100%)

Time: 00:00.246, Memory: 10.00 MB

OK (4 tests, 6 assertions)

Generating code coverage report in Clover XML format ... done [00:00.004]

Generating code coverage report in HTML format ... done [00:00.085]


Code Coverage Report:
  2022-10-16 13:01:52

 Summary:
  Classes: 100.00% (6/6)
  Methods: 100.00% (13/13)
  Lines:   100.00% (50/50)

Pimarinov\WaveformGenerator\Cli\Handler
  Methods: 100.00% ( 3/ 3)   Lines: 100.00% ( 15/ 15)
Pimarinov\WaveformGenerator\Data\CliHandlerArgs
  Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  1/  1)
Pimarinov\WaveformGenerator\Data\TalkTimes
  Methods: 100.00% ( 2/ 2)   Lines: 100.00% (  7/  7)
Pimarinov\WaveformGenerator\Data\Waveform
  Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  1/  1)
Pimarinov\WaveformGenerator\SpeakerTalkTimes
  Methods: 100.00% ( 3/ 3)   Lines: 100.00% ( 19/ 19)
Pimarinov\WaveformGenerator\WaveformGenerator
  Methods: 100.00% ( 3/ 3)   Lines: 100.00% (  7/  7)

```

![test-output](phpunit-test-output.png)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
