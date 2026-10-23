<?php

namespace App\Tests\Double;

use Psr\Log\AbstractLogger;

/**
 * Journal de test : garde le niveau et le message de chaque enregistrement.
 */
final class LoggerEspion extends AbstractLogger
{
    /** @var list<array{niveau: mixed, message: string}> */
    public array $enregistrements = [];

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->enregistrements[] = ['niveau' => $level, 'message' => (string) $message];
    }

    /**
     * @return list<mixed>
     */
    public function niveaux(): array
    {
        return array_column($this->enregistrements, 'niveau');
    }
}
