
# Waveform Generator - Jiminny [Backend Task](jiminny-backend-task.md) by Plamen Marinov

The goal of this package is to make waveform object from 2 participants (user & customer)
raw silence data.

## Installation

You can install the package via composer:

```bash
composer require pimarinov/waveform-generator
```

## Usage

You can extend the `Pimarinov\WaveformGenerator\WaveformGenerator` passing 2 participants
data (inverted by `Pimarinov\WaveformGenerator\RawSilenceToTalkTimesInverter`). The
built in `->generate()` method will give you the waveform object.

```php
use Pimarinov\WaveformGenerator\RawSilenceToTalkTimesInverter;
use Pimarinov\WaveformGenerator\WaveformGenerator;

(function ($userRawSilenceFilePath, $customerRawSilenceFilePath) {

    $userRaw = file_get_contents($userRawSilenceFilePath);
    $customerRaw = file_get_contents($customerRawSilenceFilePath);

    $userTalkTimes = (new RawSilenceToTalkTimesInverter($userRaw))
        ->collect();

    $customerTalkTimes = (new RawSilenceToTalkTimesInverter($customerRaw))
        ->collect();

    return (new WaveformGenerator($userTalkTimes, $customerTalkTimes))
            ->generate();

})(): WaveformGenerator

```

Alternative way of a custom `MyWaveformGenerator`:

```php
use Pimarinov\WaveformGenerator\RawSilenceToTalkTimesInverter;
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

    public function generate(): \Pimarinov\WaveformGenerator\Data\Waveform
    {
        $userTalkTimes = (new RawSilenceToTalkTimesInverter($this->userRaw))
            ->collect();

        $customerTalkTimes = (new RawSilenceToTalkTimesInverter($this->customerRaw))
            ->collect();

        return (new WaveformGenerator($user, $customer))
            ->generate();
    }
}
```

![Laravel](laravel-call-screenshot.png)

## CLI

This package has built in cli handler, allowing calls via console:

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
PHPUnit 10.0-gc529d6b0d by Sebastian Bergmann and contributors.

Runtime:       PHP 8.1.9 with Xdebug 3.1.3
Configuration: C:\dev\waveform-generator\phpunit.xml.dist

....                                                                4 / 4 (100%)

Time: 00:00.983, Memory: 16.00 MB

OK (4 tests, 6 assertions)

Generating code coverage report in Clover XML format ... done [00:00.005]

Generating code coverage report in HTML format ... done [00:00.095]


Code Coverage Report:
  2022-10-15 13:25:20

 Summary:
  Classes: 100.00% (6/6)
  Methods: 100.00% (13/13)
  Lines:   100.00% (50/50)

Pimarinov\WaveformGenerator\Cli\Handler
  Methods: 100.00% ( 3/ 3)   Lines: 100.00% ( 15/ 15)
Pimarinov\WaveformGenerator\Data\TalkTimesOfParticipant
  Methods: 100.00% ( 2/ 2)   Lines: 100.00% (  7/  7)
Pimarinov\WaveformGenerator\Data\Waveform
  Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  1/  1)
Pimarinov\WaveformGenerator\Data\WaveformCliArgs
  Methods: 100.00% ( 1/ 1)   Lines: 100.00% (  1/  1)
Pimarinov\WaveformGenerator\RawSilenceToTalkTimesInverter
  Methods: 100.00% ( 3/ 3)   Lines: 100.00% ( 19/ 19)
Pimarinov\WaveformGenerator\WaveformGenerator
  Methods: 100.00% ( 3/ 3)   Lines: 100.00% (  7/  7)

```

![test-output](phpunit-test-output.png)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
