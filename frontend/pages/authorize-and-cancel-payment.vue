<template>
  <div>
    <form v-if="!payment" @submit.prevent="authorizePayment">
      <fieldset>

        <ul>
          <li>
            <label for="paymentMethodId">Payment Method ID:</label>
            <input
              v-model="paymentMethodId"
              id="paymentMethodId"
              name="paymentMethodId"
              type="text"
            />
          </li>
          <li>
            <label for="cardToken">Card Token ID:</label>
            <input
              v-model="cardToken"
              id="cardToken"
              name="cardToken"
              type="text"
            />
          </li>
          <li>
            <label for="customerEmail">Email</label>
            <input
              v-model="customerEmail"
              id="customerEmail"
              name="customerEmail"
              type="email"
            />
          </li>
        </ul>

        <input type="submit" value="Authorize payment" />
      </fieldset>
    </form>

    <form v-else @submit.prevent="cancelPayment">
      <fieldset>
        <ul>
          <li>
            <label for="paymentId">Payment ID:</label>
            <input
              v-model="paymentId"
              id="paymentId"
              name="paymentId"
              type="text"
            />
          </li>
        </ul>

        <input type="submit" value="Cancel payment" />
      </fieldset>

      <pre>{{ payment }}</pre>

    </form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      paymentMethodId: '',
      cardToken: '',
      customerEmail: '',
      payment: null
    }
  },

  computed: {
    paymentId () {
      return (this.payment && this.payment.id) ? this.payment.id : 0
    }
  },

  // I'll need all this data in order to create the authorization
  created () {
    this.paymentMethodId = this.$route.query.paymentMethodId || ''
    this.cardToken = this.$route.query.cardToken || ''
    this.customerEmail = this.$route.query.customerEmail || ''
  },

  methods: {
    // For doing the authorization we're going to use the minimum we can
    // Here for the sake of the example we let pass some data throught URL params
    async getMinAllowedAmount (paymentMethodId) {
      let paymentMethod = await this.getPaymentMethod(paymentMethodId)
      if (!paymentMethod) {
        throw new Error('The payment method ID is invalid.')
      }
      return paymentMethod.min_allowed_amount
    },

    // SDK: https://www.mercadopago.com.ar/developers/en/tools/sdk/client/javascript#get-pm-info
    // API: https://www.mercadopago.com.ar/developers/en/api-docs/custom-checkout/payment-methods/
    async getPaymentMethod(paymentMethodId) {
      if (!process.browser) {
        return
      }
      return new Promise((resolve, reject) => {
        if (paymentMethodId) {
          this.$mercadopago.getPaymentMethod({
            payment_method_id: paymentMethodId
          },(status, response) => {
            if (status === 200) {
              resolve(response[0])
            } else {
              reject('An error ocurred when trying to get the payment method info.')
            }
          })
        }else{
          reject('The Payment Method ID is not valid.', paymentMethodId)
        }
      })
    },

    async authorizePayment () {
      try {
        let minAllowedAmount = await this.getMinAllowedAmount(this.paymentMethodId)
        this.payment = await this.$axios.$post(
          'create-payment-authorization', {
            transaction_amount: minAllowedAmount,
            card_token: this.cardToken,
            payment_method_id: this.paymentMethodId,
            email: this.customerEmail
        })
        alert('The payment has been authorized');
      } catch(error) {
        alert(error.response.data.error.message || error)
      }
    },

    async cancelPayment () {
      try {
        await this.$axios.$put(
          'cancel-payment-authorization', {
            payment_id: this.paymentId,
        })
        this.payment = null
        alert('The payment has been cancelled');
      } catch(error) {
        alert(error.response.data.error.message || error)
      }
    }
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
