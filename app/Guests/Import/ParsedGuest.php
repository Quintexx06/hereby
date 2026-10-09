<?php

namespace App\Guests\Import;

/**
 * One person read from an imported guest list, before it is saved.
 */
final readonly class ParsedGuest
{
    public function __construct(
        public string $firstName,
        public ?string $lastName = null,
        public bool $isChild = false,
    ) {}

    public function fullName(): string
    {
        return trim($this->firstName.' '.$this->lastName);
    }

    /**
     * @return array{first_name: string, last_name: string|null, is_child: bool}
     */
    public function toArray(): array
    {
        return ['first_name' => $this->firstName, 'last_name' => $this->lastName, 'is_child' => $this->isChild];
    }
}
