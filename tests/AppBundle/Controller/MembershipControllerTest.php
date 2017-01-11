<?php

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadAdherentData;
use AppBundle\Entity\Adherent;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MembershipControllerTest extends AbstractControllerTest
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @dataProvider provideEmailAddress
     */
    public function testCannotCreateMembershipAccountWithSomeoneElseEmailAddress($emailAddress)
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/inscription');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $data = static::createFormData();
        $data['membership_request']['emailAddress'] = $emailAddress;
        $crawler = $this->client->submit($crawler->selectButton('become-adherent')->form(), $data);

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());
        $this->assertSame('Cette adresse e-mail existe déjà.', $crawler->filter('#field-email-address > .form__errors > li')->text());
    }

    /**
     * These data come from the LoadAdherentData fixtures file.
     *
     * @see LoadAdherentData
     */
    public function provideEmailAddress()
    {
        return [
            ['michelle.dufour@example.ch'],
            ['carl999@example.fr'],
        ];
    }

    public function testCannotCreateMembershipAccountIfAdherentIsUnder15YearsOld()
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/inscription');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $data = static::createFormData();
        $data['membership_request']['birthdate'] = date('d/m/Y');
        $crawler = $this->client->submit($crawler->selectButton('become-adherent')->form(), $data);

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());
        $this->assertSame("Vous devez être âgé d'au moins 15 ans pour adhérer.", $crawler->filter('#field-birthdate > .form__errors > li')->text());
    }

    public function testCannotCreateMembershipAccountIfConditionsAreNotAccepted()
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/inscription');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $data = static::createFormData();
        $data['membership_request']['conditions'] = false;
        $crawler = $this->client->submit($crawler->selectButton('become-adherent')->form(), $data);

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());
        $this->assertSame('Vous devez accepter la charte.', $crawler->filter('#field-conditions > .form__errors > li')->text());
    }

    public function testCannotCreateMembershipAccountWithInvalidFrenchAddress()
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/inscription');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $data = static::createFormData();
        $data['membership_request']['postalCode'] = '73100';
        $data['membership_request']['city'] = '73100-73999';
        $crawler = $this->client->submit($crawler->selectButton('become-adherent')->form(), $data);

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());
        $this->assertSame("Cette valeur n'est pas un identifiant valide de ville française.", $crawler->filter('#app-membership > .form__errors > li')->text());
    }

    public function testCreateMembershipAccountForFrenchAdherentIsSuccessful()
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/inscription');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->client->submit($crawler->selectButton('become-adherent')->form(), static::createFormData());

        $this->assertClientIsRedirectedTo('/don', $this->client);
        $this->assertInstanceOf(Adherent::class, $this->client->getRequest()->getSession()->get('tmp_adherent'));
        $this->assertInstanceOf(
            Adherent::class,
            $this->client->getContainer()->get('doctrine')->getRepository(Adherent::class)->findByEmail('paul@dupont.tld')
        );
    }

    public function testCreateMembershipAccountForSwissAdherentIsSuccessful()
    {
        $client = $this->client;
        $client->request(Request::METHOD_GET, '/inscription');

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());

        $data = static::createFormData();
        $data['membership_request']['country'] = 'CH';
        $data['membership_request']['city'] = '';
        $data['membership_request']['postalCode'] = '';
        $data['membership_request']['address'] = '';

        $this->client->submit($this->client->getCrawler()->selectButton('become-adherent')->form(), $data);

        $this->assertClientIsRedirectedTo('/don', $this->client);
        $this->assertInstanceOf(Adherent::class, $this->client->getRequest()->getSession()->get('tmp_adherent'));
        $this->assertInstanceOf(
            Adherent::class,
            $this->client->getContainer()->get('doctrine')->getRepository(Adherent::class)->findByEmail('paul@dupont.tld')
        );

        $this->client->followRedirect();
        $crawler = $this->client->getCrawler();

        $this->assertCount(1, $lastName = $crawler->filter('input[name="app_donation[lastName]"]'));
        $this->assertSame('Dupont', $lastName->attr('value'), 'Adherent\'s name should be pre filled');
    }

    private static function createFormData()
    {
        return [
            'membership_request' => [
                'gender' => 'male',
                'firstName' => 'Paul',
                'lastName' => 'Dupont',
                'emailAddress' => 'paul@dupont.tld',
                'password' => [
                    'first' => '#example!12345#',
                    'second' => '#example!12345#',
                ],
                'country' => 'FR',
                'postalCode' => '92110',
                'city' => '92110-92024',
                'address' => '92 Bld Victor Hugo',
                'phone' => [
                    'country' => 'FR',
                    'number' => '0140998080',
                ],
                'position' => 'retired',
                'birthdate' => '20/01/1950',
                'conditions' => true,
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->loadFixtures([
            LoadAdherentData::class,
        ]);
        $this->client = static::createClient();
    }

    protected function tearDown()
    {
        $this->loadFixtures([]);
        $this->client = null;

        parent::tearDown();
    }
}
