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

     /**
     * Pause subscription
     *
     * @link (Guide, https://www.mercadopago.com.ar/developers/en/solutions/payments/custom-checkout/plans-and-subscriptions/#pause-and-reactivate)
     * @link(API Docs, https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/plans/subscriptions/)
     *
     * @url PUT pause-subscription
     *
     * @param string $subscription_id
     *
     * @return Subscription
     */
    public function pauseSubscription($subscription_id) {
        try {
            $response = $this->mp->put(
                "/v1/subscriptions/" . $subscription_id, [
                "status" => 'paused'
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
     * Reactivate subscription
     *
     * @link (Guide, https://www.mercadopago.com.ar/developers/en/solutions/payments/custom-checkout/plans-and-subscriptions/#pause-and-reactivate)
     * @link(API Docs, https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/plans/subscriptions/)
     *
     * @url PUT reactivate-subscription
     *
     * @param string $subscription_id
     *
     * @return Subscription
     */
    public function reactivateSubscription($subscription_id) {
        try {
            $response = $this->mp->put(
                "/v1/subscriptions/" . $subscription_id, [
                "status" => 'authorized'
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
     * Get subscription
     *
     * @link(API Docs, https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/plans/subscriptions/)
     *
     * @url GET get-subscription
     *
     * @param string $subscription_id
     *
     * @return Subscription
     */
    public function getSubscription($subscription_id) {
        try {
            $response = $this->mp->get(
                "/v1/subscriptions/".$subscription_id);
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
     * Cancel payment authorization
     *
     * @link(Guide, https://www.mercadopago.com.ar/developers/en/solutions/payments/custom-checkout/two-step-payments/#cancel-auth)
     * @link(API Docs, https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/create-payments/)
     *
     * @url PUT cancel-payment-authorization
     *
     * @param int $payment_id Payment ID {@from body}
     *
     * @return Payment
     */
    public function cancelPaymentAuthorization($payment_id) {
        try {
            $response = $this->mp->put(
                "/v1/payments/" . $payment_id,
                array(
                    "status" => "cancelled"
                )
            );
            $payment = $response['response'];
        } catch(Exception $e) {
            throw new RestException(
                $e->getCode(),
                $e->getMessage()
            );
        }
        return $payment;
    }

    /**
     * Create payment authorization
     *
     * @link(Guide, https://www.mercadopago.com.ar/developers/en/solutions/payments/custom-checkout/two-step-payments/#authorization)
     * @link(API Docs, https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/create-payments/)
     *
     * @url POST create-payment-authorization
     *
     * @param float $transaction_amount Customer's email {@from body}
     * @param string $token Card token {@from body}
     * @param string $payment_method_id Payment Method ID. Ex."visa" {@from body}
     * @param string $email Customer's email {@from body}
     *
     * @return Payment
     */
    public function createPaymentAuthorization(
        $transaction_amount,
        $card_token,
        $payment_method_id,
        $email
    ) {

        $payment_data = [
            "transaction_amount" => floatval($transaction_amount),
            "token" => $card_token,
            "description" => "Authorization performed to verify card",
            "installments" => 1,
            "payment_method_id" => $payment_method_id,
            "payer" => array (
                "email" => $email
            ),
            "capture" => false
        ];

        try {
            $response = $this->mp->post(
                "/v1/payments/",
                $payment_data
            );
            $payment = $response['response'];
        } catch(Exception $e) {
            throw new RestException(
                $e->getCode(),
                $e->getMessage()
            );
        }
        return $payment;
    }

    /**
     * Create subscription plan
     *
     * @link(Guide, https://www.mercadopago.com.ar/developers/en/solutions/payments/custom-checkout/plans-and-subscriptions/#first-plan)
     * @link(API Docs, https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/plans/)
     *
     * @url POST create-plan
     *
     * @return Plan
     */
    public function createPlan($request_data) {
        try {
            $response = $this->mp->post("/v1/plans", $request_data);
            $plan = $response['response'];
        } catch(Exception $e) {
            throw new RestException(
                $e->getCode(),
                $e->getMessage()
            );
        }
        return $plan;
    }

}
