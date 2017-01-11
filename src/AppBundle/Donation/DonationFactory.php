<?php

namespace AppBundle\Donation;

use AppBundle\Entity\Adherent;
use AppBundle\Entity\Donation;
use libphonenumber\PhoneNumber;
use Symfony\Component\HttpFoundation\Request;

class DonationFactory
{
    public function createDonationFromRequest(Request $request): Donation
    {
        if ($adherent = $request->getSession()->remove('tmp_adherent')) {
            return $this->createDonationFromAdherent($adherent);
        }

        $donation = new Donation();

        if ($amount = $request->query->getInt('montant')) {
            $donation->setAmount($amount);
        }

        if (($gender = $request->query->get('ge')) && in_array($gender, ['male', 'female'], true)) {
            $donation->setGender($gender);
        }

        if ($lastName = $request->query->get('ln')) {
            $donation->setLastName($lastName);
        }

        if ($firstName = $request->query->get('fn')) {
            $donation->setFirstName($firstName);
        }

        if ($email = $request->query->get('em')) {
            $donation->setEmail(urldecode($email));
        }

        if ($country = $request->query->get('co')) {
            $donation->setCountry($country);
        }

        if ($postalCode = $request->query->get('pc')) {
            $donation->setPostalCode($postalCode);
        }

        if ($city = $request->query->get('ci')) {
            $donation->setCity($city);
        }

        if ($address = $request->query->get('ad')) {
            $donation->setAddress(urldecode($address));
        }

        if (($phoneCode = $request->query->get('phc')) && ($phoneNumber = $request->query->get('phn'))) {
            $phone = new PhoneNumber();
            $phone->setCountryCode($phoneCode);
            $phone->setNationalNumber($phoneNumber);

            $donation->setPhone($phone);
        }

        return $donation;
    }

    private function createDonationFromAdherent(Adherent $adherent)
    {
        return (new Donation())
            ->setGender($adherent->getGender())
            ->setFirstName($adherent->getFirstName())
            ->setLastName($adherent->getLastName())
            ->setEmail($adherent->getEmail())
            ->setAddress($adherent->getAddress())
            ->setPostalCode($adherent->getPostalCode())
            ->setCity($adherent->getCity())
            ->setPhone($adherent->getPhone())
        ;
    }
}
