# MercadoPago NUXT integration example

> Example of implementation of MercadoPago API on NUXT

## Setup
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

## Build

``` bash
# install dependencies
$ npm install # Or yarn install

# serve with hot reload at localhost:3000
$ npm run dev
```

## Docs
- For detailed explanation on how NUXT work, checkout the [Nuxt.js docs](https://github.com/nuxt/nuxt.js).
- [MercadoPago SDK Client](https://www.mercadopago.com.ar/developers/en/tools/sdk/client/javascript/)
- [MercadoPago Custom Checkout Guide](https://www.mercadopago.com.ar/developers/en/solutions/payments/custom-checkout/get-started/)
- [MercadoPago API Reference](https://www.mercadopago.com.ar/developers/en/api-docs/)

# License

MIT