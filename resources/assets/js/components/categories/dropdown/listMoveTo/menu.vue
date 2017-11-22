<template>
    <div>
        <div :id="_uid" class="ui floating dropdown" v-show="category.availableMoveTo.length>0">
            <div class="text">{{ strings.firstMenuName }}</div>
            <i class="dropdown icon"></i>
            <div class="menu">
                <div v-for="category in category.availableMoveTo" class="item" :data-value="category.id">
                    <i class="dropdown icon" v-if="category.children.length>0"></i>
                    <span class="text">{{ category['description'][properties.actualLocale] }}</span>
                    <recursive-categories-dropdown-menu
                      :categories="category.children"
                    ></recursive-categories-dropdown-menu>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
  /**
   * Props
   *  - category: Object. The category object with parent, child, id etc etc to move
   *
   * Events:
   *  @categoryChoice: emit the object to process move {parentId: value, id: that.category.id}
   */
  export default {
    props: {
      category: Object
    },
    computed: {
      strings () {
        return this.$store.state.strings['categories-list-move-to']
      },
      properties () {
        return this.$store.state.properties['global']
      }
    },
    mounted () {
      let that = this
      let dropdown = $('#' + that._uid)
      dropdown.on('click', function () {
        $(this).closest('.accordion').css({'z-index': '2'})
      })
      dropdown.dropdown({
        allowCategorySelection: true,
        action: 'select',
        onChange (value, text, $selectedItem) {
          if (value !== undefined && value !== null && value !== '') {
            $(this).closest('.accordion').css({'z-index': ''})
            that.$emit('categoryChoice', {parentId: value, id: that.category.id})
          }
        }
      })
    }
  }
</script>
