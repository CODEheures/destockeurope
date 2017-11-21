<template>
    <div id="advert-type-dropdown">
        <div class="inline fields">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <label class="text">{{ firstMenuName }}</label>
            <div v-for="(type,index) in listType" :class="isDisabled ? 'disabled field':'filed'" :data-value="index">
                <div class="field">
                    <div :id="'radio-'+index+'-'+_uid" class="ui radio checkbox">
                        <input type="radio" name="type" :value="index" :checked="oldChoice == index ? true : false">
                        <label>{{ type }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
  /**
   * Props
   *  - routeGetListType: String. Url of the list
   *  - firstMenuName: String. The label of the field
   *  - oldChoice: String. To set the field by a value
   *  - isDisabled: Boolean. To disable the field
   *
   * Events:
   *  @typeChoice: emit the value of the choice
   */
  import Axios from 'axios'
  export default {
    props: {
      routeGetListType: String,
      firstMenuName: String,
      oldChoice: {
        type: String,
        default: undefined,
        required: false
      },
      isDisabled: {
        type: Boolean,
        default: false,
        required: false
      }
    },
    computed: {
      strings () {
        return this.$store.state.strings['type-radio-button']
      }
    },
    data () {
      return {
        listType: [],
        isLoaded: false
      }
    },
    mounted () {
      this.getListType()
    },
    updated () {
      let that = this
      for (let index in this.listType) {
        $('#radio-' + index + '-' + that._uid).checkbox({
          onChange () {
            that.$emit('typeChoice', this.value)
          }
        })
      }
    },
    methods: {
      getListType () {
        let that = this
        Axios.get(this.routeGetListType)
          .then(function (response) {
            that.listType = response.data
            that.isLoaded = true
          })
          .catch(function () {
            that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
          })
      }
    }
  }
</script>
