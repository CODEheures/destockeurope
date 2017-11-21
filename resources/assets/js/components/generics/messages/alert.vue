<template>
    <div style="position: fixed; right: 10px; top: 12em; z-index: 2000;">
        <transition-group name="appear-right" tag="div">
            <div v-for="(myAlert, index) in alerts" class="ui icon message appear-right-item" :class="myAlert.type" :key="myAlert.key">
                <i class="info icon"></i>
                <i class="close icon" @click.prevent.stop="delAlert(index)"></i>
                <div class="content">
                    <p>{{ myAlert.message }}</p>
                </div>
            </div>
        </transition-group>
    </div>
</template>

<script>
  /**
   * Global $alertV
   * Use: this.$alertV({message: 'my message', type: 'error'})
   * type: see types of semantic-ui message class (error, success, warning, info...)
   *
   * Props
   *
   * Events:
   *
   */
  import Vue from 'vue'
  export default {
    data () {
      return {
        alerts: [],
        key: 0
      }
    },
    mounted () {
      Vue.prototype.$alertV = (payload) => { this.push(payload) }
    },
    methods: {
      push (payload) {
        this.alerts.push({
          message: payload.message,
          type: payload.type,
          key: this.key
        })
        this.key++
      },
      delAlert (index) {
        this.alerts.splice(index, 1)
      }
    }
  }
</script>
