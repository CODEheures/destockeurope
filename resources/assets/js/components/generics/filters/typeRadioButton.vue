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
            that.$parent.$emit('loadError')
          })
      }
    }
  }
</script>
