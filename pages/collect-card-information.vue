<template>
  <form action="" method="post" id="pay" name="pay" @submit.prevent="doPay">
    <fieldset>
      <ul>
        <li>
          <label for="email">Email</label>
          <input
            v-model="email"
            id="email"
            name="email"
            type="email"
            placeholder="your email"
          />
        </li>
        <li>
          <label for="cardNumber">Credit card number:</label>
          <input type="text"
            v-model.lazy="cardNumber"
            id="cardNumber"
            data-checkout="cardNumber"
            placeholder="4509 9535 6623 3704"
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
            placeholder="123"
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
          <input type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" placeholder="12" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
        </li>
        <li>
          <label for="cardExpirationYear">Expiration year:</label>
          <input type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" placeholder="2015" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" autocomplete=off />
        </li>
        <li>
          <label for="cardholderName">Card holder name:</label>
          <input type="text" id="cardholderName" data-checkout="cardholderName" placeholder="APRO" />
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
          <input type="text" id="docNumber" data-checkout="docNumber" placeholder="12345678" />
        </li>
      </ul>
      <input v-model="paymentMethodInfo.id" type="hidden" name="paymentMethodId" />
      <input v-model="cardToken" type="hidden" name="token" />
      <input type="submit" value="Pay!" />
    </fieldset>
  </form>
</template>

<script>
export default {

  data () {
    return {
      email: 'test_user_19653727@testuser.com',
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

    async doPay (event) {
      let form = event.target
      try {
        this.cardToken = await this.createToken(form)
        alert('The card is valid! Save this token to retrieve the card data later: ' + this.cardToken)
      } catch(error) {
        alert(error)
        event.preventDefault()
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
