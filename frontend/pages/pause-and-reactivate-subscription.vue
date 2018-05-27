<template>
  <div>
    <form @submit.prevent="changeSubscriptionStatus">
      <fieldset>

        <ul>
          <li>
            <label for="subscriptionId">Subscription ID:</label>
            <input
              v-model="subscriptionId"
              id="subscriptionId"
              name="subscriptionId"
              type="text"
            />
          </li>
        </ul>

        <p v-if="subscription">The subscription is {{ subscription.status }} </p>

        <input type="submit" :value="btnText" />
      </fieldset>
    </form>
  </div>
</template>

<script>
export default {

  data () {
    return {
      subscriptionId: '9efb949f0f8a4d17ae2b14cbce4da3c9',
      subscription: null
    }
  },

  computed: {
      btnText () {
        if (!this.subscription) {
          return 'Get subscription info'
        }
        return this.subscription.status === 'authorized'
          ? 'Pause subscription'
          : 'Reactivate subscription'
      }
    },

  methods: {
    async changeSubscriptionStatus () {
      if (! this.subscription) {
        this.subscription = this.getSubscription()

      } else if(this.subscription.status === 'authorized') {
        this.pauseSubscription()

      } else if(this.subscription.status === 'paused') {
        this.reactivateSubscription()
      }
    },
    async getSubscription () {
      try {
        this.subscription = await this.$axios.$get(
          'get-subscription', {
            params: {
              subscription_id: this.subscriptionId
            }
          }
        )
        if (!this.subscription) {
          throw new Error('There\'s no subscription with that ID.')
        }
      } catch(error) {
        alert(error.response.data.error.message || error)
      }
    },
    async pauseSubscription () {
      try {
        this.subscription = await this.$axios.$put(
          'pause-subscription', {
            subscription_id: this.subscriptionId
          }
        )
        if (!this.subscription) {
          throw new Error('There\'s no subscription with that ID.')
        }
        alert('The subscription has been paused')
      } catch(error) {
        alert(error.response.data.error.message || error)
      }
    },
    async reactivateSubscription () {
      try {
        this.subscription = await this.$axios.$put(
          'reactivate-subscription', {
            subscription_id: this.subscriptionId
          }
        )
        if (!this.subscription) {
          throw new Error('There\'s no subscription with that ID.')
        }
        alert('The subscription has been reactivated')
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
