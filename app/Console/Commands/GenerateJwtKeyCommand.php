<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Str;

class GenerateJwtKeyCommand extends Command
{
    use ConfirmableTrait;

    public const ENV_KEY_NAME = 'APP_JWT_SECRET';

    public const CONFIG_KEY_NAME = 'app.jwt.secret';

    protected $signature = <<<'EOD'
        generate:jwt-key
                                {--force : Force the operation to run when in production}
        EOD;

    protected $description = 'Generates a new JWT key, prints it and stores the hashed key in the .env file.';

    protected static $defaultName = 'generate:jwt-key';

    public function handle(): void
    {
        $key = Str::random(64);

        if (! $this->setKeyInEnvironmentFile($key)) {
            return;
        }

        config()->set(static::CONFIG_KEY_NAME, $key);

        $this->components->info("JWT key set successfully to {$key}");
    }

    protected function setKeyInEnvironmentFile(string $key): bool
    {
        /** @var ?string $currentSaltedKey */
        $currentSaltedKey = config(static::CONFIG_KEY_NAME);

        if ((strlen($currentSaltedKey) !== 0) && (! $this->confirmToProceed())) {
            return false;
        }

        if (! $this->writeNewEnvironmentFileWith($key)) {
            return false;
        }

        return true;
    }

    protected function writeNewEnvironmentFileWith(string $saltedKey): bool
    {
        $saltedKey = str_replace('$', '\\$', $saltedKey);
        $replaced = preg_replace(
            $this->keyReplacementPatterns(),
            static::ENV_KEY_NAME . "='{$saltedKey}'",
            $input = file_get_contents($this->laravel->environmentFilePath()),
        );

        if ($replaced === $input || $replaced === null) {
            $this->error('Unable to set application key. No ' . static::ENV_KEY_NAME . ' variable was found in the .env file.');

            return false;
        }

        file_put_contents($this->laravel->environmentFilePath(), $replaced);

        return true;
    }

    protected function keyReplacementPatterns(): array
    {
        $currentKey = config(static::CONFIG_KEY_NAME);

        return [
            '/^' . static::ENV_KEY_NAME . '=$/m',
            '/^' . static::ENV_KEY_NAME . preg_quote("='{$currentKey}'", '/') . '$/m',
            '/^' . static::ENV_KEY_NAME . preg_quote("=\"{$currentKey}\"", '/') . '$/m',
        ];
    }
}
