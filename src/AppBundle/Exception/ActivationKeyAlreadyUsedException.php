<?php

namespace AppBundle\Exception;

use AppBundle\Entity\ActivationKey;

final class ActivationKeyAlreadyUsedException extends ActivationKeyException
{
    public function __construct(ActivationKey $key, \Exception $previous = null)
    {
        $message = sprintf('Activation key %s was already used on %s.', $key->getToken(), $key->getUsageDate());

        parent::__construct($key, $message, $previous);
    }
}
