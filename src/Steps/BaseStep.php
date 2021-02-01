<?php

namespace Helldar\PackageWizard\Steps;

use Composer\IO\IOInterface;
use Helldar\PackageWizard\Contracts\Stepable;
use Helldar\PackageWizard\Services\Output;
use Helldar\Support\Concerns\Makeable;

abstract class BaseStep implements Stepable
{
    use Makeable;

    /** @var \Symfony\Component\Console\Output\OutputInterface */
    protected $io;

    /** @var string */
    protected $question;

    protected $result;

    /** @var \Helldar\PackageWizard\Services\Output */
    protected $output;

    protected $ask_many = false;

    public function __construct(IOInterface $io, Output $output)
    {
        $this->io     = $io;
        $this->output = $output;
    }

    public function question(string $question): Stepable
    {
        $this->question = $question;

        return $this;
    }

    public function get()
    {
        return $this->ask_many ? $this->getMany() : $this->getOnce();
    }

    abstract protected function input();

    protected function getOnce()
    {
        return $this->input();
    }

    protected function getMany(): array
    {
        if (! is_array($this->result)) {
            $this->result = [];
        }

        do {
            $this->result[] = $this->getOnce();
        } while ($this->askAgain());

        return $this->result;
    }

    protected function askAgain(): bool
    {
        return $this->io->askConfirmation('Want to add another value (Y/n)?', false);
    }
}
