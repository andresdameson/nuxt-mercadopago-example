<template>
  <form @submit.prevent="createNewCustomer">
    <fieldset>
      <legend><h2>Create new customer</h2></legend>
      <ul>
        <li>
          <label for="cardToken">Card token:</label>
          <input type="text"
            v-model="cardToken"
            id="cardToken"
            autocomplete="off"
          />
        </li>
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
      </ul>
      <input type="submit" value="Create" />
    </fieldset>

    <div
      v-if="customerInfo"
      style="margin: 20px 0;"
    >
      <p>At this time we can
        <nuxt-link
          :to="{
            name: 'subscribe-customer',
            query: {
              email: this.email
            }
          }"
        >
          subscribe the customer to a plan
        </nuxt-link>
      </p>

      <br>

      <pre v-if="customerInfo">Customer's info: {{ customerInfo }}</pre>
    </div>
  </form>
</template>

<script>
export default {

  data () {
    return {
      cardToken: this.$route.query.cardToken || '',
      email: this.$route.query.email || '',
      customerInfo: null
    }
  },

  methods: {
    async createCustomerWithDefaultCard () {
      return await this.$axios.$post('create-customer', {
        email: this.email,
        token: this.cardToken
      })
    },

    async createNewCustomer () {
      try {
        this.customerInfo = await this.createCustomerWithDefaultCard()
        alert('The customer has been created!')
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
