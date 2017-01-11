<?php

namespace AppBundle\Exception;

use Ramsey\Uuid\UuidInterface;

final class AdherentBannedException extends AdherentException
{
    public function __construct(UuidInterface $adherent, \Exception $previous = null)
    {
        parent::__construct(
            $adherent,
            sprintf('Adherent "%s" is banned.', $adherent),
            $previous
        );
    }
}
