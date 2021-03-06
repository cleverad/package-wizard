<?php

namespace Helldar\PackageWizard\Steps;

use Composer\Package\Version\VersionParser;
use Helldar\Support\Facades\Helpers\Str;

class Dependencies extends BaseStep
{
    protected $question = 'Would you like to define your dependencies (require) [<comment>Y/n</comment>]?';

    protected $sub_question = 'Search for a package';

    protected $ask_many = true;

    protected function input(): ?string
    {
        return $this->getIO()->askAndValidate($this->question($this->sub_question), function ($value) {
            if (preg_match('{^\s*(?P<name>[\S/]+)(?:\s+(?P<version>\S+))?\s*$}', $value, $package_matches)) {
                if (isset($package_matches['version'])) {
                    $this->versionParser()->parseConstraints($package_matches['version']);

                    return $package_matches['name'] . ' ' . $package_matches['version'];
                }

                return $package_matches['name'] . ' ^1.0';
            }

            return null;
        });
    }

    protected function post($result): array
    {
        $items = [];

        foreach ($result as $value) {
            $split = explode(' ', $value, 2);

            $name = trim($split[0]);

            $items[$name] = $this->fixVersion($split[1]);
        }

        return $items;
    }

    protected function skip(): bool
    {
        return ! $this->getIO()->askConfirmation($this->question());
    }

    protected function versionParser(): VersionParser
    {
        return new VersionParser();
    }

    protected function fixVersion(string $value): string
    {
        if ($value === '*') {
            return $value;
        }

        if (! Str::contains($value, '.')) {
            $value .= '.0';
        }

        return Str::start($value, '^');
    }
}
