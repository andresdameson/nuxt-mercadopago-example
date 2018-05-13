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
- To read MercadoPago API docs follow this link: https://www.mercadopago.com.ar/developers/es/tools/sdk/client/javascript/

# License

MIT