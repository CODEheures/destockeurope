<template>
    <div :class="level==1 ? 'ui fluid popup' : 'ui link list'">
        <template v-if="level==1 && maxLevel>1">
            <div :class="'ui ' + numberToWord(categories.length) +' column relaxed equal height divided grid'">
                <template v-for="(category,index) in categories">
                    <div class="column">
                        <h4 class="ui header">{{ category['description'][properties.actualLocale] }}</h4>
                        <recursive-categories-horizontal-menu
                                :categories="category.children"
                                :actual-locale="properties.actualLocale"
                                :parent-id="category.id"
                                :parent-description="category.description[properties.actualLocale]"
                                :all-item="allItem"
                                :level="level+1"
                                :max-level="parseInt(0)"
                                @categoryChoice="$emit('categoryChoice', $event)"
                        ></recursive-categories-horizontal-menu>
                    </div>
                </template>
            </div>
        </template>
        <template v-if="level==1 && maxLevel==1">
            <div class="ui one  column relaxed equal height divided grid">
                <div class="column">
                    <h4 class="ui header">{{ parentDescription }}</h4>
                    <div class="ui link list">
                        <template v-for="(category,index) in categories">
                            <a class="item" :data-value="category.id" v-on:click="$emit('categoryChoice', category.id)">{{ category['description'][properties.actualLocale] }}</a>
                        </template>
                    </div>
                </div>
            </div>
        </template>
        <template v-if="level>1">
            <a class="item" :data-value="parentId" v-on:click="$emit('categoryChoice', parentId)">{{ allItem }}</a>
            <template v-for="(category,index) in categories">
                <a class="item" :data-value="category.id" v-on:click="$emit('categoryChoice', category.id)" v-if="category.children.length==0">{{ category['description'][properties.actualLocale] }}</a>
            </template>
        </template>
    </div>
</template>


<script>
  /**
   * Props
   *  - categories: Array. The categories to process
   *  - allItem: String. The all item label
   *  - parentId: Number. The id of the parent of categories
   *  - parentDescription: String. The description of the parent of categories
   *  - level: Number. The depth level of categories
   *  - maxLevel: Number. To determine if maxLevel==1 to choice the good template
   *
   * Events:
   *  @categoryChoice: emit the object to process move {parentId: value, id: that.category.id}
   */
  export default {
    props: {
      categories: Array,
      allItem: String,
      parentId: Number,
      parentDescription: String,
      level: Number,
      maxLevel: Number
    },
    computed: {
      properties () {
        return this.$store.state.properties['global']
      }
    },
    mounted () {
      let that = this
      this.categories.forEach(function (elem, index) {
        $('#' + that._uid + '-' + index).dropdown()
      })
    },
    methods: {
      numberToWord (num) {
        let a = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen']
        return a[num]
      }
    }
  }
</script>
