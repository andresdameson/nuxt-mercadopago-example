# EXAMPLES

Here is one page for each action you may perform using MercadoPago SDK. There are
not covered all the actions, only the once needed to perform a suscription.

## Collect card information
[View example](http://0.0.0.0:3000/collect-card-information)

To make a payment we have to create a card token which will hold all data needed in a secure way.
Bear in mind this token has an expiration time and it can be used only once.

**Guide**\
(This guide is followed partially)\
https://www.mercadopago.com.ar/developers/es/solutions/payments/custom-checkout/charge-with-creditcard/javascript/

## Create customer with default card
[View example](http://0.0.0.0:3000/create-customer-with-default-card)

In order to perform payments later on we have to create a customer on MercadoPago. Here's how.

**Guide**\
https://www.mercadopago.com.ar/developers/es/solutions/payments/custom-checkout/customers-and-cards/


## Subscribe a customer
[View example](http://0.0.0.0:3000/subscribe-customer)

Now we can subscribe a customer. We only need the ID of a previusly created plan and the
customer's ID.

**Guide**\
https://www.mercadopago.com.ar/developers/en/solutions/payments/custom-checkout/plans-and-subscriptions/

**API Doc**\
https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/plans/subscriptions/
