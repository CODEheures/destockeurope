<template>
    <div :class="left ? 'left menu' : 'menu'">
        <div v-for="category in categories" class="item" :data-value="category.id" >
            <i :class="left ? 'right dropdown icon' : 'left dropdown icon'" v-if="category.children.length>0"></i>
            <span class="text">{{ category['description'][properties.actualLocale] }}</span>
            <recursive-categories-dropdown-menu
                    v-if="category.children.length>0"
                    :categories="category.children"
                    :left="!left">
            </recursive-categories-dropdown-menu>
        </div>
    </div>
</template>


<script>
  export default {
    props: {
      categories: Array,
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
