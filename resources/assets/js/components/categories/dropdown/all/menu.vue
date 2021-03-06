<template>
    <div>
        <div class="ui mini labeled right action input">
            <div class="ui blue label" v-on:click="setOldChoice">
                {{ strings.firstMenuName }}
            </div>
            <div :id="_uid" class="ui mini floating dropdown" :class="isButton ? 'button' : ''">
                <div class="default text">{{ strings.firstMenuName }}</div>
                <i class="dropdown icon"></i>
                <div class="menu">
                    <div class="item" data-value="0" :data-text="strings.allItem" v-if="withAll">
                        <span class="text">{{ strings.allItem }}</span>
                    </div>
                    <div v-for="category in categories" class="item" >
                        <i class="dropdown icon" v-if="category.children.length>0"></i>
                        <span class="text">{{ category['description'][properties.actualLocale] }}</span>
                        <recursive-categories-dropdown-menu
                                :parent-description="category['description'][properties.actualLocale]"
                                :categories="category.children"
                                :parent-id="category.id"
                                :with-all="withAll"
                                :all-item="strings.allItem"
                                :left="false">
                        </recursive-categories-dropdown-menu>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>


<script>
  /**
   * Props
   *  - oldChoice: Number. The category Id to be selected when mounted
   *  - withAll: Boolean. To available an All item in parent category
   *  - allowCategorySelection: Boolean. To allow a selection on a non last child category
   *  - isButton: Boolean. To make dropdown button
   *  - withRedirectionOnClick. Redirect to next url with param of selected categoryId
   *
   * Events:
   *  @categoryChoice: emit id of selected category
   */
  import { DestockTools } from '../../../../destockTools'
  export default {
    props: {
      oldChoice: {
        type: Number,
        required: false,
        default: 0
      },
      withAll: {
        type: Boolean,
        required: false,
        default: false
      },
      allowCategorySelection: {
        type: Boolean,
        required: false,
        default: false
      },
      isButton: {
        type: Boolean,
        required: false,
        default: true
      },
      withRedirectionOnClick: {
        type: Boolean,
        required: false,
        default: false
      }
    },
    watch: {
      oldChoice () {
        this.setOldChoice()
      }
    },
    computed: {
      strings () {
        return this.$store.state.strings['categories-dropdown-menu']
      },
      properties () {
        return this.$store.state.properties['global']
      },
      categories () {
        return this.$store.state.properties['categories-dropdown-menu']['datas']
      },
      nextUrl () {
        return this.$store.state.properties['global']['routeHome']
      }
    },
    mounted () {
      let that = this
      $('#' + this._uid).dropdown({
        allowCategorySelection: that.allowCategorySelection,
        onChange (value, text, $selectedItem) {
          if (value !== undefined && value !== null && value !== '') {
            if (!that.withRedirectionOnClick) {
              that.$emit('categoryChoice', value)
            }
            else {
              if (value !== that.oldChoice) { document.location.href = that.getNextUrl('categoryId', value) }
            }
          }
        }
      })
      this.setOldChoice()
    },
    methods: {
      setOldChoice () {
        if (!isNaN(Number(this.oldChoice)) && Number(this.oldChoice) > 0) {
          $('#' + this._uid).dropdown('set selected', this.oldChoice)
        }
      },
      getNextUrl (paramName, paramValue) {
        return DestockTools.getNextUrl(this.nextUrl, paramName, paramValue, true)
      }
    }
  }
</script>
