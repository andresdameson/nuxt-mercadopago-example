<template>
  <form @submit.prevent="subscribe">
    <fieldset>
      <ul>
        <li>
          <label for="planId">Plan ID:</label>
          <input
            v-model="planId"
            id="planId"
            name="planId"
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

      <pre v-if="subscription">{{ subscription }}</pre>

      <input type="submit" value="Suscribe customer" />
    </fieldset>
  </form>
</template>

<script>
export default {

  data () {
    return {
      planId: '3de24b4eadf24e68af737f07f24ca473',
      customerEmail: '',
      customer: null,
      subscription: null
    }
  },

  methods: {
    async subscribe () {
      try {
        this.customer = await this.$axios.$get(
          'get-customer-by-email', {
            params: {
              email: this.customerEmail
            }
          }
        )
        if (!this.customer) {
          throw new Error('There\'s no customer with that email address.')
        }
        this.subscription = await this.$axios.$post(
          'subscribe-customer', {
            plan_id: this.planId,
            customer_id: this.customer.id
          }
        )
        alert('The customer has been subscribed!')
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
