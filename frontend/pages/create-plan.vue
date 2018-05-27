<template>
  <form @submit.prevent="subscribe">
    <fieldset>
      <legend><h2>Create plan</h2></legend>
      <ul>
        <li>
          <label for="planDescription">Plan description</label>
          <input
            v-model="planDescription"
            id="planDescription"
            name="planDescription"
            type="text"
          />
        </li>
      </ul>

      <input type="submit" value="Create" />
    </fieldset>

    <div
      v-if="plan"
      style="margin: 20px 0;"
    >
      <p>Once you've created a subscription plan you can
        <nuxt-link
          :to="{
            name: 'subscribe-customer',
            query: {
              planId: this.plan.id,
              email: this.$route.query.email || ''
            }
          }"
        >
          subscribe the customer.
        </nuxt-link>
      </p>

      <br>

      <pre>Plan: {{ plan }}</pre>
    </div>
  </form>
</template>

<script>
export default {

  data () {
    return {
      planDescription: 'Test plan fot NUXT demo',
      plan: ''
    }
  },

  methods: {
    async subscribe () {
      try {
        this.plan = await this.$axios.$post(
          'create-plan', {
            "description": this.planDescription,
            "auto_recurring":{
              "frequency":1,
              "frequency_type":"months",
              "transaction_amount":270,
              "currency_id": "ARS",
              "free_trial": {
                "frequency":7,
                "frequency_type":"days",
              }
            }
          }
        )
        alert('The plan has been created!')
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
