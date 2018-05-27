<template>
  <form @submit.prevent="subscribe">
    <fieldset>
      <legend><h2>Suscribe customer to plan</h2></legend>
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

      <input type="submit" value="Suscribe customer" />
    </fieldset>

    <div
      v-if="subscription"
      style="margin: 20px 0;"
    >
      <p>Once you've made a subscription you can
        <nuxt-link
          :to="{
            name: 'pause-and-reactivate-subscription',
            query: {
              subscriptionId: this.subscription.id
            }
          }"
        >
          pause or reactivate it.
        </nuxt-link>
      </p>

      <br>

      <pre v-if="subscription">Subscription: {{ subscription }}</pre>
    </div>
  </form>
</template>

<script>
export default {

  data () {
    return {
      planId: this.$route.query.planId || '',
      customerEmail: this.$route.query.email || '',
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
