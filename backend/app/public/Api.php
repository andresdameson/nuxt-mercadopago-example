<?php

use Luracast\Restler\RestException;

class Api {

    public function __construct(){
        $this->mp = new MP (
            "TEST-3213445048116419-040606-35390f537d8c142d24ec4ceacf5926eb__LD_LC__-136747793"
        );
    }

    /**
     * Create customer
     *
     * @link https://www.mercadopago.com.ar/developers/en/solutions/payments/custom-checkout/customers-and-cards/
     *
     * @url POST create-customer
     *
     * @param string $email Customer's email
     * @param string $token Card token
     *
     * @return Customer
     */
    public function createCustomer($email, $token) {
        try {
            // Check if customer already exists
            $response = $this->mp->get("/v1/customers/search", [
                "email" => $email
            ]);
            $customer = $response['response']['results']
                ? array_shift($response['response']['results'])
                : null;

            // Create new customer
            if(! $customer){
                $response = $this->mp->post("/v1/customers", [
                    "email" => $email
                ]);
                $customer = $response['response'];
            }

            // Create default customer card
            if(!$customer['default_card']){
                $response = $this->mp->post("/v1/customers/". $customer['id'] ."/cards", [
                   "token" => $token
                ]);
                $customer['default_card'] = $response['response'];
            }
        } catch(Exception $e) {
            throw new RestException(
                $e->getCode(),
                $e->getMessage()
            );
        }
        return $customer;
    }

}
