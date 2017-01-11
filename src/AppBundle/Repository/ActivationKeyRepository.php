<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ActivationKey;
use Doctrine\ORM\EntityRepository;

class ActivationKeyRepository extends EntityRepository
{
    /**
     * Finds an ActivationKey instance by its token unique value.
     *
     * @param string $token
     *
     * @return ActivationKey|null
     */
    public function findByToken(string $token)
    {
        return $this->findOneBy(['token' => $token]);
    }
}
