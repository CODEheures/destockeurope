<template>
    <div style="display: inline-block">
        <div class="ui grid breadcrumb">
            <div class="sixteen wide tablet sixteen wide computer only column">
                <div class="ui big breadcrumb">
                    <template v-for="(item, index) in items">
                        <template v-if="withAction">
                            <a :href="getNextUrl('categoryId',item.value)" class="section" :data-value="item.value" @click.stop="">{{ item.name }}</a>
                        </template>
                        <template v-else>
                            <a :href="getNextUrl('categoryId',item.value)" class="section" :data-value="item.value" @click.stop.prevent="$emit('categoryChoice', item.value)">{{ item.name }}</a>
                        </template>
                        <template v-if="index!=items.length-1">
                            <i class="right angle icon divider"></i>
                        </template>
                    </template>
                </div>
            </div>
            <div class="sixteen wide mobile only column">
                <div class="ui breadcrumb">
                    <template v-for="(item, index) in items">
                        <template v-if="withAction">
                            <a :href="getNextUrl('categoryId',item.value)" class="section" :data-value="item.value" @click.stop="">{{ item.name }}</a>
                        </template>
                        <template v-else>
                            <a :href="getNextUrl('categoryId',item.value)" class="section" :data-value="item.value" @click.stop.prevent="$emit('categoryChoice', item.value)">{{ item.name }}</a>
                        </template>
                        <template v-if="index!=items.length-1">
                            <i class="right angle icon divider"></i>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  /**
   * Props
   *  - staticItems: Array of objects. Categories items for breacrumb: [{value: 1, name: 'Multimedia'}, {value: 8, name: 'computers'}, ...]
   *  - withAction: Boolean. To active link
   *
   * Events:
   *  @categoryChoice: emit the id of the category
   */
  import { DestockTools } from '../../../destockTools'
  export default {
    props: {
      'staticItems': {
        type: Array,
        required: false,
        default: null
      },
      'withAction': {
        type: Boolean,
        required: false,
        default: false
      }
    },
    computed: {
      nextUrl () {
        return this.$store.state.properties['global']['routeHome']
      },
      items () {
        return this.staticItems || this.$store.state.properties['breadcrumbItems'] || []
      }
    },
    methods: {
      getNextUrl (paramName, paramValue) {
        return DestockTools.getNextUrl(this.nextUrl, paramName, paramValue, true)
      }
    }
  }
</script>
