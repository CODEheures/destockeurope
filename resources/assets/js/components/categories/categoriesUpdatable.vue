<template>
    <div>
        <div v-for="(category,index) in categories" class="accordion">
            <div class="title flex">
                <div>
                    <i class="dropdown icon"></i>
                    <i :class="'large ' + colors[colorNumber%colors.length] + ' minus square icon'" v-on:click="delCategory" :data-id="category.id" v-if="category.canBeDeleted"></i>
                    <i class="big ban icon" v-else></i>
                    <span v-for="locale in properties.availableLocalesList">
                    <div class="ui mini labeled input">
                        <div class="ui label">{{ locale }}</div>
                        <input type="text"
                               :name="category.id + '_' + locale"
                               :data-id="category.id"
                               :data-key="locale"
                               v-model="category['description'][locale]"
                               v-on:keyup.enter="updateCategory"
                               v-on:focus="focused={'id': category.id, 'locale': locale, 'value': category['description'][locale]}"
                               v-on:blur="testChanged(focused, {'id': category.id, 'locale': locale, 'value': category['description'][locale]})"
                        />
                    </div>
                </span>
                </div>
                <div class="change-category">
                    <span>
                        <categories-list-move-to
                                :category="category"
                                @categoryChoice="$emit('categoryChoice', $event)"
                        ></categories-list-move-to>
                    </span>
                    <span class="drag-category" :data-value="category.id" v-if="categories.length>1">
                        <template v-if="index==0">
                            <i class="large toggle down icon" :data-value="category.id" v-on:click="shiftDown"></i>
                        </template>
                        <template v-if="index==categories.length-1">
                            <i class="large toggle up icon" :data-value="category.id" v-on:click="shiftUp"></i>
                        </template>
                        <template v-if="index!=0 && index!=categories.length-1">
                            <i class="large toggle down icon" :data-value="category.id" v-on:click="shiftDown"></i>
                            <i class="large toggle up icon" :data-value="category.id" v-on:click="shiftUp"></i>
                        </template>
                    </span>
                </div>

            </div>
            <div class="content">
                <categories-updatable
                        :categories="category.children"
                        :parent-id="category.id"
                        :color-number="colorNumber+1"
                        @categoryChoice="$emit('categoryChoice', $event)"
                        @getCategories="$emit('getCategories')"
                        @addCategory="$emit('addCategory', $event)"
                        @delCategory="$emit('delCategory', $event)"
                        @updateCategory="$emit('updateCategory', $event)"
                        @shiftDown="$emit('shiftDown', $event)"
                        @shiftUp="$emit('shiftUp', $event)"
                ></categories-updatable>
            </div>
        </div>
        <div :class="'ui ' + colors[colorNumber%colors.length] + ' segment'">
            <i :class="'large ' + colors[colorNumber%colors.length] + ' add square icon'"
               :data-parent-id="parentId"
               v-on:click="addCategory">
            </i>
            <span v-for="locale in properties.availableLocalesList">
                        <div class="ui mini labeled input">
                            <div class="ui label">{{ locale }}</div>
                            <input type="text" placeholder="Nouvelle sous-catégorie"
                                   :name="'newCategory-' + _uid + '_' + locale"
                                   :data-parent-id="parentId"
                                   :data-key="locale"
                                   v-on:keyup.enter="addCategory"
                                   v-model="categoryName[locale]"
                                   v-on:focus="focused={}"
                                   v-on:blur="blured={}"
                            />
                        </div>
                    </span>
        </div>
    </div>
</template>


<script>
  /**
   * Props
   *  - categories: Array. The categories to process
   *  - parentId: Number. The id of the parent of categories
   *  - colorNumber: Number. The color index
   *
   * Events:
   *  @shiftDown: emit when shiftDown. Pass event to determine parent
   *  @shiftUp: emit when shiftUp. Pass event to determine parent
   *  @addCategory: emit to add Category {description: {fr: 'une description, en: 'a describe'}, parentId: 4}
   *  @delCategory: emit the ID to delete category
   *  @updateCategory: emit Object to update a category: {postValue: value, key: key, id: id}
   *  @getCategories: emit to force reload categories
   *  @categoryChoice: emit the object to process move {parentId: value, id: that.category.id}
   */
  export default {
    props: {
      categories: Array,
      parentId: Number,
      colorNumber: {
        type: Number,
        required: false,
        default: 0
      }
    },
    computed: {
      properties () {
        return this.$store.state.properties['global']
      },
      strings () {
        return this.$store.state.strings['categories-updatable']
      },
      colors () {
        return [
          'violet',
          'purple',
          'pink',
          'brown',
          'grey',
          'black',
          'red',
          'orange',
          'yellow',
          'olive',
          'green',
          'teal',
          'blue'
        ]
      }
    },
    data () {
      return {
        isLoaded: false,
        categoryName: {},
        focused: {},
        blured: {}
      }
    },
    methods: {
      addCategory (event) {
        if (this.categoryName !== undefined && this.categoryName !== null) {
          let isEmpty = true
          let postValue = {}
          postValue['descriptions'] = {}
          postValue['parentId'] = this.parentId
          for (let category in this.categoryName) {
            postValue['descriptions'][category] = this.categoryName[category]
            if (this.categoryName[category] !== '') {
              isEmpty = false
            }
          }
          if (!isEmpty) {
            this.isLoaded = false
            this.categoryName = {}
            this.$emit('addCategory', postValue)
          }
        }
      },
      delCategory (event) {
        if (event.target.dataset.id !== undefined && event.target.dataset.id !== null && event.target.dataset.id > 0) {
          this.$emit('delCategory', event.target.dataset.id)
        }
      },
      updateCategory (event) {
        let postValue = {}
        let key = ''
        let id = ''
        if (event === undefined || event === null) {
          id = this.blured.id
          key = this.blured.locale
          postValue[key] = this.blured.value
        }
        else if ((event instanceof KeyboardEvent) && event.key === 'Enter') {
          id = event.target.dataset.id
          key = event.target.dataset.key
          postValue[key] = event.target.value
          this.focused.value = event.target.value
        }
        if (postValue[key] !== undefined && postValue[key] !== null && postValue[key] !== '') {
          this.$emit('updateCategory', {postValue: postValue, key: key, id: id})
        }
        else {
          this.$emit('getCategories')
          this.$alertV({'message': this.strings.patchErrorMessage, 'type': 'error'})
        }
      },
      shiftDown (event) {
        this.$emit('shiftDown', event)
      },
      shiftUp (event) {
        this.$emit('shiftUp', event)
      },
      testChanged ($in, $out) {
        if ($in.id === $out.id && $in.locale === $out.locale && $in.value !== $out.value) {
          this.blured = {id: $out.id, locale: $out.locale, value: $out.value}
          this.updateCategory()
        }
      }
    }
  }
</script>
