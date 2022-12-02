<?php
declare(strict_types=1);

namespace Pimarinov\WaveformGenerator\Test\Unit\Cli;

use PHPUnit\Framework\TestCase;
use Pimarinov\WaveformGenerator\Cli\Handler;
use Pimarinov\WaveformGenerator\Data\Waveform;

class CliHandlerTest extends TestCase
{

    public function test_waveform_cli_generate_succeeded(): void
    {
        $userFile = __DIR__ . '/../dummy-raw-silence/user.dummy-raw-silence.txt';
        $customerFile = __DIR__ . '/../dummy-raw-silence/customer.dummy-raw-silence.txt';

        $handler = new Handler($userFile, $customerFile);

        $result = $handler->execute();

        $this->assertInstanceOf(Waveform::class, $result);
    }

    public function test_waveform_cli_handler_wrong_params(): void
    {
        $this->expectException(\TypeError::class);
        new Handler(null, null);
    }

    public function test_waveform_cli_handler_missing_params(): void
    {
        $this->expectException(\ArgumentCountError::class);
        new Handler();
    }

    public function test_waveform_cli_generate_missing_user_file(): void
    {
        $userFile = __DIR__ . 'missing.txt';
        $customerFile = __DIR__ . '/../dummy-raw-silence/customer.dummy-raw-silence.txt';

        $handler = new Handler($userFile, $customerFile);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('FILE ERROR (user): No such file.');
        $result = $handler->execute();
    }

    public function test_waveform_cli_generate_missing_customer_file(): void
    {
        $userFile = __DIR__ . '/../dummy-raw-silence/user.dummy-raw-silence.txt';
        $customerFile = __DIR__ . 'missing.txt';

        $handler = new Handler($userFile, $customerFile);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('FILE ERROR (customer): No such file.');
        $result = $handler->execute();
    }
}
