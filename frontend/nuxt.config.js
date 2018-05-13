module.exports = {
  /*
  ** Headers of the page
  */
  head: {
    title: 'nuxt-mercadopago-demo',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: 'Example of implementation of MercadoPago API on NUXT' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },
  modules: [
    '~/modules/mercadopago',
    '@nuxtjs/axios'
  ],

  mercadopago: {
    public_key: 'TEST-3683240c-494f-4a4b-b6d4-6f82b73c1952'
  },

  axios: {
    baseURL: 'http://localhost:8056/'
  },

  /*
  ** Customize the progress bar color
  */
  loading: { color: '#3B8070' },
  /*
  ** Build configuration
  */
  build: {
    /*
    ** Run ESLint on save
    */
    extend (config, { isDev, isClient }) {
      if (isDev && isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/
        })
      }
    }
  }
}
