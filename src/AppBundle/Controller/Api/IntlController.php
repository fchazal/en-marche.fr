<?php

namespace AppBundle\Controller\Api;

use AppBundle\Cloudflare\Cloudflare;
use AppBundle\Intl\FranceCitiesBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api")
 */
class IntlController extends Controller
{
    /**
     * @Route("/postal-code/{postalCode}", name="api_postal_code", options={"expose"="true"})
     * @Method("GET")
     */
    public function indexAction($postalCode)
    {
        return Cloudflare::cacheIndefinitely(
            new JsonResponse(FranceCitiesBundle::getPostalCodeCities($postalCode)),
            ['api-postal-codes', 'api-postal-code-'.$postalCode]
        );
    }
}
