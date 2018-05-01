export default (ctx, inject) => {

  // Create new MercadoPago instance
  Mercadopago.setPublishableKey('<%= options.public_key %>')

  // Inject mercadopago to the context as $mercadopago
  inject('mercadopago', Mercadopago)
}
