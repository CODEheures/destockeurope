<template>
    <div :id="'subscribe-'+_uid" class="ui toggle checkbox" v-show="destockShareVarData.firebase.token!=null && destockShareVarData.firebase.token!=undefined && destockShareVarData.firebase.token!=''">
        <input type="checkbox" name="public">
        <label>{{ strings.checkboxLabel }}</label>
    </div>
</template>

<script>
  /**
   * Props
   *  - routeExistIn: String. The route to get if user exist in notification list
   *  - routeAdd: String. The route to add user in notification list
   *  - routeRemove: String. The route to remove user of notification list
   *  - topic_id: Number. The topic number list
   *
   * Events:
   *
   */
  import Axios from 'axios'
  export default {
    props: {
      routeExistIn: String,
      routeAdd: String,
      routeRemove: String,
      topic_id: Number
    },
    computed: {
      strings () {
        return this.$store.state.strings['notifications-activer']
      }
    },
    watch: {
      'destockShareVarData.firebase.token' (token) {
        this.existInToken(token)
      },
      'existIn' (value) {
        let subscribeCheckbox = $('#subscribe-' + this._uid)
        if (value === true) {
          subscribeCheckbox.checkbox('set checked')
        }
        else {
          subscribeCheckbox.checkbox('set unchecked')
        }
      }
    },
    data () {
      return {
        destockShareVarData: window.destockShareVar,
        existIn: false
      }
    },
    mounted () {
      // App Notifications
      let that = this
      let subscribeCheckbox = $('#subscribe-' + this._uid)
      if (this.destockShareVarData.firebase.token !== undefined && this.destockShareVarData.firebase.token !== null) {
        this.existInToken(this.destockShareVarData.firebase.token)
      }
      subscribeCheckbox.checkbox({
        onChecked () {
          Axios.post(that.routeAdd, {'token': that.destockShareVarData.firebase.token, 'topic_id': that.topic_id})
            .then(function (response) {
              // console.log('subscribe success', response)
            })
            .catch(function () {
              // console.log('subscribe error', error)
            })
        },
        onUnchecked () {
          Axios.delete(that.routeRemove, {data: {'token': that.destockShareVarData.firebase.token, 'topic_id': that.topic_id}})
            .then(function (response) {
              // console.log('unsubscribe success', response)
            })
            .catch(function () {
              // console.log('unsubscribe error', error)
            })
        }
      })
    },
    methods: {
      existInToken (token) {
        let that = this
        Axios.post(that.routeExistIn, {'token': token, 'topic_id': that.topic_id})
          .then(function (response) {
            that.existIn = response.data.existIn === true
          })
          .catch(function () {
            that.existIn = false
          })
      }
    }
  }
</script>
