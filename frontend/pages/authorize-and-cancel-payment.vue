<template>
  <div>
    <form v-if="!payment" @submit.prevent="authorizePayment">
      <fieldset>
        <legend><h2>Authorize & cancel payment</h2></legend>
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

      <p style="margin-top: 20px;">If the Card Token in invalid you have probably already used it. Remember that it is one time use only. <br><br>

        Want to
        <nuxt-link
            :to="{
              name: 'collect-card-information',
              query: {
                email: this.customerEmail
              }
            }"
          >
            create a new token</nuxt-link>?
      </p>
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

      <div style="margin-top: 20px;">
        <p v-if="payment.status === 'cancelled'">You verified  that the card can be used to perform a payment, now you can
          <nuxt-link
            :to="{
              name: 'create-customer-with-default-card',
              query: {
                cardToken: this.cardToken,
                email: this.customerEmail
              }
            }"
          >
            create a new customer and set this card as default.
          </nuxt-link>

          <br><br>

          Do you want to try again?
          <nuxt-link
            :to="{
              name: 'collect-card-information',
              query: {
                email: this.customerEmail
              }
            }"
          >
            Generate a new token
          </nuxt-link>
          and start again.
        </p>

        <pre style="margin-top: 20px;">Payment: {{ payment }}</pre>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data () {
    return {
      paymentMethodId: this.$route.query.paymentMethodId || '',
      cardToken: this.$route.query.cardToken || '',
      customerEmail: this.$route.query.email || '',
      payment: null
    }
  },

  computed: {
    paymentId () {
      return (this.payment && this.payment.id) ? this.payment.id : 0
    }
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
        this.payment.status = 'cancelled'
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
