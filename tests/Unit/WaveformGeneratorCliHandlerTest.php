<?php

declare(strict_types=1);

namespace Pimarinov\WaveformGenerator\Test\Unit;

use Pimarinov\WaveformGenerator\Cli\Handler;
use Pimarinov\WaveformGenerator\Data\Waveform;
use Pimarinov\WaveformGenerator\Data\CliHandlerArgs;
use PHPUnit\Framework\TestCase;

class WaveformGeneratorCliHandlerTest extends TestCase
{
    /** @test */
    public function waveform_cli_generate_succeeded(): void
    {
        $userFile = __DIR__ . '/../dummy-raw-silence/user.dummy-raw-silence.txt';
        $customerFile = __DIR__ . '/../dummy-raw-silence/customer.dummy-raw-silence.txt';

        $args = new CliHandlerArgs($userFile, $customerFile);

        $handler = new Handler($args);

        $result = $handler->execute();

        $this->assertInstanceOf(Waveform::class, $result);
    }

    /** @test */
    public function waveform_cli_generate_missing_user_file(): void
    {
        $userFile = __DIR__ . 'missing.txt';
        $customerFile = __DIR__ . '/../dummy-raw-silence/customer.dummy-raw-silence.txt';

        $args = new CliHandlerArgs($userFile, $customerFile);

        $handler = new Handler($args);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('FILE ERROR (user): No such file.');
        $result = $handler->execute();
    }

    /** @test */
    public function waveform_cli_generate_missing_customer_file(): void
    {
        $userFile = __DIR__ . '/../dummy-raw-silence/user.dummy-raw-silence.txt';
        $customerFile = __DIR__ . 'missing.txt';

        $args = new CliHandlerArgs($userFile, $customerFile);

        $handler = new Handler($args);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('FILE ERROR (customer): No such file.');
        $result = $handler->execute();
    }
}
