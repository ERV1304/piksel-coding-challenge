<?php
namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PaymentControllerTest extends WebTestCase
{

    
    /**
     * Test the expected HTTP Status in response to the route:
     *      Testing route: "/royaltymanager/payments"
     *      Expected HTTP status: 200
     *
     * @return void
     */
    public function testResponsePaymentsStatusIsOK (): void
    { 
        $expectedStatus = 200;

        $url = "http://localhost:8000/royaltymanager/payments";
        
        $res = $this->clientRequest( $url );

        var_dump($res);


        $this->assertEquals($res->getStatusCode(), $expectedStatus);
    }

    /**
     * Test the expected HTTP Status in response to the route:
     *      Testing route: "/royaltymanager/payments"
     *      Expected HTTP status: 200
     *
     * @return void
     */
    public function testResponsePaymentByStudioIdStatusIsOK (): void
    { 
        $expectedStatus = 200;

        $id = 1;

        $url = "http://localhost:8000/royaltymanager/payment/" . $id;
        
        $res = $this->clientRequest( $url );

        $this->assertEquals($res->getStatusCode(), $expectedStatus);
    }




    private function clientRequest( String $url, Array $query = [] ): Response
    {
        $client = WebTestCase::createClient();
        
        $client->request(
			'GET',
			$url,
			$query,
		    [],
            ['Accept' => '*/*']
		);

        return $client->getResponse();
    }
    
}