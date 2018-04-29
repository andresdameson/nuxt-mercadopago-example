const path = require('path')

module.exports = function nuxtMercadoPago (_moduleOptions) {
  const moduleOptions = Object.assign({}, this.options.mercadopago, _moduleOptions)

  let currentOptions = {
    public_key: process.env.MP_PUBLIC_KEY ||
      moduleOptions.public_key ||
      null
  }

  // Don't include when no public key is provided
  if(!currentOptions.public_key) {
    console.log('!currentOptions')
	  return
	}

	// Add MercadoPago script to head
	this.options.head.script.push({
	  src: ('https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js'),
	  async: true
	})

	// Register plugin
  this.addPlugin({
    src: path.resolve(__dirname, 'mercadopago-plugin.js'),
    ssr: false,
    options: currentOptions
  })
}
