# MercadoPago NUXT integration example

> Example of custom checkout implementations of MercadoPago API and SDK on NUXT

Here there are some examples of basic actions you may want to perform in a custom checkout implementation using MercadoPago API. There are not covered all the posible actions, but I hope you find them useful.

We will use the SDK when posible an fill the gaps calling their API otherwise.


## Backend
### Build
Go to backend folder and run:
``` bash
export UID && docker-compose up -d
```
The server will be listening at http://localhost::8056


### Docs
- [MercadoPago Custom Checkout Guide](https://www.mercadopago.com.ar/developers/en/solutions/payments/custom-checkout/get-started/): The guide explains how to use MercadoPago API to make a custom checkout. All the comunication is meant to be done from the backend.
- [MercadoPago API Reference](https://www.mercadopago.com.ar/developers/en/api-docs/): This reference gives aditional information about what resources are available through the API but it explains nothing about how to use them.


## Frontend
### Setup
- Add `/modules/` folder to your project
- Add `~/modules/mercadopago` to `modules` section of `nuxt.config.js`
- Add your public_key from your credentials page: https://www.mercadopago.com/mla/account/credentials
```js
{
  modules: [
    '~/modules/mercadopago'
  ],

   mercadopago: {
    public_key: 'replace-with-your-public-key'
  },
}
```

### Build
Go to frontend folder and run:
``` bash
# install dependencies
$ npm install # Or yarn install

# serve with hot reload at localhost:3000
$ npm run dev
```

### Docs
- For detailed explanation on how NUXT work, checkout the [Nuxt.js docs](https://github.com/nuxt/nuxt.js).
- [MercadoPago SDK Client](https://www.mercadopago.com.ar/developers/en/tools/sdk/client/javascript/): The SDK has some build-in methods but most of the actions will be direct calls to their API.



## EXAMPLES

### Collect card information
[View example](http://0.0.0.0:3000/collect-card-information)

To make a payment we have to create a card token which will hold all data needed in a secure way.
Bear in mind this token has an expiration time and it can be used only once.

**Guide**\
(This guide is followed partially)\
https://www.mercadopago.com.ar/developers/es/solutions/payments/custom-checkout/charge-with-creditcard/javascript/

### Create customer with default card
[View example](http://0.0.0.0:3000/create-customer-with-default-card)

In order to perform payments later on we have to create a customer on MercadoPago. Here's how.

**Guide**\
https://www.mercadopago.com.ar/developers/es/solutions/payments/custom-checkout/customers-and-cards/


### Subscribe a customer
[View example](http://0.0.0.0:3000/subscribe-customer)

Now we can subscribe a customer. We only need the ID of a previusly created plan and the
customer's ID.

**Guide**\
https://www.mercadopago.com.ar/developers/en/solutions/payments/custom-checkout/plans-and-subscriptions/

**API Doc**\
https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/plans/subscriptions/


### Pause and reactivate a subscription
[View example](http://0.0.0.0:3000/pause-and-reactivate-subscription)

Once you've made a subscription you can pause or reactivate it.

**Guide**\
https://www.mercadopago.com.ar/developers/en/solutions/payments/custom-checkout/plans-and-subscriptions/#pause-and-reactivate

**API Doc**\
https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/plans/subscriptions/


### Authorize and cancel payment
[View example](http://0.0.0.0:3000/authorize-and-cancel-payment)

You can freeze the money on the card of your client at first, and realize or cancel the payment later. This can be useful to verify if a card can be used to perform a payment beforehand.

**Guide**\
https://www.mercadopago.com.ar/developers/en/solutions/payments/custom-checkout/two-step-payments/

**API Doc**\
https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/create-payments/


## License
MIT