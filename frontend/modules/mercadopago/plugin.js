import helpers from '~/modules/mercadopago/helpers.js'

export default (ctx, inject) => {

  // Create new MercadoPago instance
  Mercadopago.setPublishableKey('<%= options.public_key %>')

  // Add helpers to deal with MercadoPago SDK
  Mercadopago.helpers = helpers

  // Inject mercadopago to the context as $mercadopago
  inject('mercadopago', Mercadopago)
}
