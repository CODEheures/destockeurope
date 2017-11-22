<template>
    <div :class="left ? 'left menu' : 'menu'">
        <div class="item" :data-value="parentId" :data-text="parentDescription + ' > ' + allItem" v-if="withAll">
            <span class="text">{{ allItem }}</span>
        </div>
        <div v-for="category in categories" class="item" :data-value="category.id" :data-text="parentDescription + '<br /> > ' + category['description'][properties.actualLocale]" >
            <i :class="left ? 'right dropdown icon' : 'left dropdown icon'" v-if="category.children.length>0"></i>
            <span class="text">{{ category['description'][properties.actualLocale] }}</span>
            <recursive-categories-dropdown-menu
                    v-if="category.children.length>0"
                    :parent-description="parentDescription + '<br /> > ' + category['description'][properties.actualLocale]"
                    :categories="category.children"
                    :parent-id="category.id"
                    :with-all="withAll"
                    :all-item="allItem"
                    :left="!left">
            </recursive-categories-dropdown-menu>
        </div>
    </div>
</template>


<script>
  /**
   * Props
   *  - parentDescription: String. Cumulative recursive description
   *  - categories: Array. Categories with id, descriptions, children..
   *  - parentId: Number. The id of the parent
   *  - withAll: Boolean. To available an All item in parent category
   *  - allItem: String. The All item label
   *  - left: Boolean. To alternate left-right of the dropdown
   *
   * Events:
   *
   */
  export default {
    props: {
      parentDescription: {
        type: String,
        required: false,
        default: ''
      },
      categories: Array,
      parentId: {
        type: Number,
        required: false,
        default: 0
      },
      withAll: Boolean,
      allItem: {
        type: String,
        required: false,
        default: ''
      },
      left: {
        type: Boolean,
        required: false,
        default: false
      }
    },
    computed: {
      properties () {
        return this.$store.state.properties['global']
      }
    }
  }
</script>
