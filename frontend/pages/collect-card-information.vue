<template>
  <form method="post" @submit.prevent="generateCardToken">
    <fieldset>
      <legend><h2>Collect card information</h2></legend>
      <ul>
        <li>
          <label for="email">Email</label>
          <input
            v-model="email"
            id="email"
            name="email"
            type="email"
            placeholder="customer's email"
          />
        </li>
        <li>
          <label for="cardNumber">Credit card number:</label>
          <input type="text"
            v-model.lazy="cardNumber"
            id="cardNumber"
            data-checkout="cardNumber"
            onselectstart="return false"
            onpaste="return false"
            onCopy="return false"
            onCut="return false"
            onDrag="return false"
            onDrop="return false"
            autocomplete="off"
          />
        </li>
        <li v-show="isSecurityCodeRequired">
          <label for="securityCode">Security code:</label>
          <input
            type="text"
            id="securityCode"
            data-checkout="securityCode"
            value="123"
            onselectstart="return false"
            onpaste="return false"
            onCopy="return false"
            onCut="return false"
            onDrag="return false"
            onDrop="return false"
            autocomplete="off"
          />
        </li>
        <li>
          <label for="cardExpirationMonth">Expiration month:</label>
          <input
            type="text"
            id="cardExpirationMonth"
            data-checkout="cardExpirationMonth"
            value="12"
            onselectstart="return false"
            onpaste="return false"
            onCopy="return false"
            onCut="return false"
            onDrag="return false"
            onDrop="return false"
            autocomplete=off
          />
        </li>
        <li>
          <label for="cardExpirationYear">Expiration year:</label>
          <input
            type="text"
            id="cardExpirationYear"
            data-checkout="cardExpirationYear"
            value="2022"
            onselectstart="return false"
            onpaste="return false"
            onCopy="return false"
            onCut="return false"
            onDrag="return false"
            onDrop="return false"
            autocomplete=off
          />
        </li>
        <li>
          <label for="cardholderName">Card holder name:</label>
          <input
            type="text"
            id="cardholderName"
            data-checkout="cardholderName"
            value="APRO"
          />
        </li>
        <li>
          <label for="docType">Document type:</label>
          <select id="docType" data-checkout="docType">
            <option v-for="docType in documentTypes" :key="docType.id">
              {{ docType.name }}
            </option>
          </select>
        </li>
        <li>
          <label for="docNumber">Document number:</label>
          <input
            type="text"
            id="docNumber"
            data-checkout="docNumber"
            value="12345678"
          />
        </li>
      </ul>
      <input v-model="paymentMethodInfo.id" type="hidden" name="paymentMethodId" />
      <input v-model="cardToken" type="hidden" name="token" />
      <input type="submit" value="Create card token" />
    </fieldset>

    <div v-if="!cardToken">
      <p style="margin-top: 20px;">If you can't generate a new card token try <a href="javascript:location.reload()">refreshing this page</a> to load the SDK again.
      </p>
    </div>
    <div
      v-else
      style="margin-top: 20px;"
    >
      <p>If this is a new customer next you'll want to
        <nuxt-link
          :to="{
            name: 'create-customer-with-default-card',
            query: {
              cardToken: this.cardToken,
              email: this.email
            }
          }"
        >
          create a new customer
        </nuxt-link> but I recommend you to verify if the card can be used to perform a payment before doing that.<br><br>

        This can be done <nuxt-link
          :to="{
            name: 'authorize-and-cancel-payment',
            query: {
              paymentMethodId: this.paymentMethodInfo ? this.paymentMethodInfo.id : '',
              cardToken: this.cardToken,
              email: this.email
            }
          }"
        >
          making an authorization of a payment and then cancelling it.
        </nuxt-link>
      </p>
    </div>
  </form>
</template>

<script>
export default {

  data () {
    return {
      email: this.$route.query.email || '',
      documentTypes: [],
      paymentMethodInfo: {
        id: '',
        settings: []
      },
      cardNumber:'4509 9535 6623 3704',
      cardToken:''
    }
  },

  computed: {
    // First six digits of the card
    bin () {
      return this.cardNumber.replace(/[ .-]/g, '').slice(0, 6)
    },
    isSecurityCodeRequired () {
      let founded = this.paymentMethodInfo.settings.find(config => {
        return this.bin.match(config.bin.pattern) != null && config.security_code.length == 0
      })
      return founded === undefined ? true : false
    }
  },

  watch: {
    bin: {
      immediate: true,
      handler: async function (newBin, oldBin) {
        this.paymentMethodInfo = await this.guessingPaymentMethod(newBin)
      }
    }
  },

  methods: {
    // https://www.mercadopago.com.ar/developers/en/tools/sdk/client/javascript#get-doc-types
    async getDocumentTypes () {
      if (!process.browser) {
        return []
      }
      return new Promise((resolve, reject) => {
        this.$mercadopago.getIdentificationTypes((status, response) => {
          if (status === 200) {
            resolve(response)
          } else {
            reject('An error ocurred when trying to get the identification types.')
          }
        })
      })
    },

    // https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/payment-methods/
    async guessingPaymentMethod (bin) {
      return new Promise((resolve, reject) => {
        if (bin.length >= 6) {
          this.$mercadopago.getPaymentMethod({
            "bin": bin
          },(status, response) => {
            if (status === 200) {
              resolve(response[0])
            } else {
              reject('An error ocurred when trying to get the payment method info.')
            }
          })
        }else{
          reject('The bin is not valid.', bin)
        }
      })
    },

    // Creates a card_token, which is the secure representation of the card
    async createToken (form) {
      return new Promise((resolve, reject) => {
        this.$mercadopago.createToken(form, (status, response) => {
          if (status === 200 || status === 201) {
            resolve(response.id)
          } else {
            let message = this.$mercadopago.helpers.getMessage('card-token-creation', response)
            reject(message)
          }
        })
      })
    },

    async generateCardToken (event) {
      let form = event.target
      try {
        this.cardToken = await this.createToken(form)
        alert('The card is valid! Save this token to retrieve the card data later: ' + this.cardToken)
      } catch(error) {
        alert(error)
      }
    }
  },

  async created () {
    this.documentTypes = await this.getDocumentTypes()
  }
}
</script>

<style>
form {
  max-width: 400px;
  margin: 20px auto;
}
ul {
  padding-left: 0;
}
li {
  list-style-type: none;
}
label {
  display: block;
  margin-top: 5px;
}
input {
  display: block;
  width: 100%;
  border-radius: 2px;
  padding: 5px;
  margin: 5px 0 10px 0;
}
</style>
