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
            $customer = $this->getCostumerByEmail($email);

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

    /**
     * Subscribe customer
     *
     * @link (Guide, https://www.mercadopago.com.ar/developers/en/solutions/payments/custom-checkout/plans-and-subscriptions/)
     * @link(API Docs, https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/plans/subscriptions/)
     *
     * @url POST subscribe-customer
     *
     * @param string $plan_id
     * @param string $customer_id
     *
     * @return Subscription
     */
    public function subscribeCustomer($plan_id, $customer_id) {
        try {
            // Check if customer exists
            $customer = $this->getCostumerById($customer_id);
            if (!$customer) {
                throw new RestException(
                    404,
                    'The customer doesn\'t exists'
                );
            }
            $response = $this->mp->post("/v1/subscriptions", [
                "plan_id" => $plan_id,
                "payer" => array(
                    "id" => $customer_id
                )
            ]);
            $subscription = $response['response'];
        } catch(Exception $e) {
            throw new RestException(
                $e->getCode(),
                $e->getMessage()
            );
        }
        return $subscription;
    }

    /**
     * Get customer by email
     *
     * @link(API Docs, https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/customers-cards/)
     *
     * @url GET get-customer-by-email
     *
     * @param string $email
     *
     * @return Customer
     */
    public function getCostumerByEmail($email) {
        try {
            $response = $this->mp->get("/v1/customers/search", [
              "email" => $email
            ]);
            $customer = $response['response']['results']
              ? array_shift($response['response']['results'])
              : null;
            return $customer;
        } catch(Exception $e) {
            throw new RestException(
                $e->getCode(),
                $e->getMessage()
            );
        }
        return $customer;
    }

    /**
     * Get customer by ID
     *
     * @link(API Docs,https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/customers-cards/)
     *
     * @url GET get-customer-by-id
     *
     * @param string $id
     *
     * @return Customer
     */
    private function getCostumerById($id) {
        try {
            $response = $this->mp->get("/v1/customers/search", [
              "id" => $id
            ]);
            $customer = $response['response']['results']
              ? array_shift($response['response']['results'])
              : null;
            return $customer;
        } catch(Exception $e) {
            throw new RestException(
                $e->getCode(),
                $e->getMessage()
            );
        }
        return $customer;
    }

}
