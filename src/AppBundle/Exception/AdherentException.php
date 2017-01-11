<?php

namespace AppBundle\Exception;

use AppBundle\Entity\Adherent;
use Ramsey\Uuid\UuidInterface;

class AdherentException extends RuntimeException
{
    private $adherent;

    public function __construct(UuidInterface $adherent, $message = '', \Exception $previous = null)
    {
        parent::__construct($message, $previous);

        $this->adherent = $adherent;
    }

    public function getAdherent(): Adherent
    {
        return $this->adherent;
    }
}
