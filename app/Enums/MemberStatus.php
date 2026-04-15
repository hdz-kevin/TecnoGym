<?php

namespace App\Enums;

enum MemberStatus: int
{
    /** With at least one active membership */
    case ACTIVE = 1;
    /** With all memberships expired */
    case EXPIRED = 2;
    /** Without any membership */
    case NO_MEMBERSHIP = 3;

    /**
     * Get the label for the member status
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Activo',
            self::EXPIRED => 'Vencido',
            self::NO_MEMBERSHIP => 'Sin membresía',
        };
    }
}
