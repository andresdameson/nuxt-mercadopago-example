<template>
  <form @submit.prevent="createNewCustomer">
    <fieldset>
      <ul>
        <li>
          <label for="cardToken">Card token:</label>
          <input type="text"
            v-model="cardToken"
            id="cardToken"
            placeholder="190de448353c88a2bf7eb4988c6e086e"
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
      <input type="submit" value="Create new customer" />
    </fieldset>
  </form>
</template>

<script>
export default {

  data () {
    return {
      cardToken: '190de448353c88a2bf7eb4988c6e086e',
      email: 'test_user_19653727@testuser.com'
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
        alert(error)
        event.preventDefault()
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
