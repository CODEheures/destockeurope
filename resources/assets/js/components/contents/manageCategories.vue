<template>
    <div class="ui one column grid">
        <div :id="'modal-'+_uid" class="ui basic modal">
            <i class="close icon"></i>
            <div class="header">
                {{ strings.modalDelHeader }}
            </div>
            <div class="image content">
                <div class="image">
                    <i class="trash icon"></i>
                </div>
                <div class="description">
                    <p>{{ strings.modalDelDescription }}</p>
                </div>
            </div>
            <div class="actions">
                <div class="two fluid ui inverted buttons">
                    <div class="ui cancel red basic inverted button">
                        <i class="remove icon"></i>
                        {{ strings.modalNo }}
                    </div>
                    <div class="ui ok green basic inverted button">
                        <i class="checkmark icon"></i>
                        {{ strings.modalYes }}
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <h2 class="ui header">{{ strings.contentHeader }}</h2>
        </div>
        <div class="column">
            <div id="category-accordion" class="ui fluid styled accordion">
                <div class="ui active inverted dimmer" v-if="!isLoaded">
                    <div class="ui large text loader">Loading</div>
                </div>
                <div class="active title">
                    <i class="dropdown icon"></i>
                </div>
                <div class="active content">
                    <div v-for="(category,index) in categories" :id="'accordion-'+_uid+'-'+index" class="accordion">
                        <div class="title flex">
                            <div>
                                <i class="dropdown icon"></i>
                                <i class="big blue minus square icon" v-on:click="delCategory"
                                   :data-id="category.id" v-if="category.canBeDeleted"></i>
                                <i class="big ban icon" v-else></i>
                                <span v-for="locale in properties.availableLocalesList">
                                    <div class="ui labeled input">
                                        <div class="ui label">{{ locale }}</div>
                                        <input type="text"
                                               :name="category.id + '_' + locale"
                                               :data-id="category.id"
                                               :data-key="locale"
                                               v-model="category['description'][locale]"
                                               v-on:keyup.enter="updateCategory"
                                               v-on:focus="focused={'id': category.id, 'locale': locale, 'value': category['description'][locale]}"
                                               v-on:blur="testChanged(focused, {'id': category.id, 'locale': locale, 'value': category['description'][locale]})"/>
                                    </div>
                                </span>
                            </div>
                            <div class="change-category">
                                <span>
                                    <categories-list-move-to
                                            :category="category"
                                            @categoryChoice="appendToCategory($event.id, $event.parentId)"
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
                                    @categoryChoice="appendToCategory($event.id, $event.parentId)"
                                    @getCategories="getCategories"
                                    @addCategory="addCategory(null, $event)"
                                    @delCategory="delCategory(null, $event)"
                                    @updateCategory="updateCategory(null, $event)"
                                    @shiftDown="shiftCategory($event, 'down')"
                                    @shiftUp="shiftCategory($event, 'up')"
                            ></categories-updatable>
                        </div>
                    </div>
                    <div class="ui blue segment">
                        <i class="big blue add square icon" v-on:click="addCategory"></i>
                        <span v-for="locale in properties.availableLocalesList">
                            <div class="ui labeled input">
                                <div class="ui mini label">{{ locale }}</div>
                                <input type="text" placeholder="Nouvelle Catégorie"
                                       :name="'newCategory-' + _uid + '_' + locale"
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
            </div>
        </div>
    </div>
</template>


<script>
  /**
   * Props
   *  - routeShiftUpCategory: String. The route to shift up a category
   *  - routeShiftDownCategory: String. The route to shift down a category
   *  - routeAppendToCategory: String. The route to append a category to another
   *
   * Events:
   *
   */
  import Axios from 'axios'
  import { focus } from 'vue-focus'
  export default {
    directives: {focus: focus},
    props: {
      routeShiftUpCategory: String,
      routeShiftDownCategory: String,
      routeAppendToCategory: String
    },
    computed: {
      strings () {
        return this.$store.state.strings['manage-categories']
      },
      properties () {
        return this.$store.state.properties['global']
      }
    },
    data () {
      return {
        isLoaded: false,
        sendMessage: false,
        typeMessage: '',
        message: '',
        focused: {},
        blured: {},
        categories: {},
        categoryName: {}
      }
    },
    mounted () {
      this.getCategories()
    },
    updated () {
      for (let index in this.categories) {
        $('#accordion-' + this._uid + '-' + index).accordion({
          selector: {
            trigger: '.title > div > .dropdown.icon'
          }
        })
      }
    },
    methods: {
      getCategories () {
        this.isLoaded = false
        let that = this
        Axios.get(this.properties.routeCategory + '?withInfos=true')
          .then(function (response) {
            that.categories = response.data
            that.isLoaded = true
          })
          .catch(function () {
            that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
          })
      },
      addCategory (event, emitPostValue) {
        let isEmpty = true
        let postValue = {}
        if (emitPostValue !== undefined && emitPostValue !== null) {
          postValue = emitPostValue
          isEmpty = false
        }
        else if (this.categoryName !== undefined && this.categoryName !== null) {
          postValue['descriptions'] = {}
          for (let lang in this.categoryName) {
            postValue['descriptions'][lang] = this.categoryName[lang]
            if (this.categoryName[lang] !== '') {
              isEmpty = false
            }
          }
        }
        if (!isEmpty) {
          this.isLoaded = false
          this.categoryName = {}
          let that = this
          Axios.post(this.properties.routeCategory, postValue)
            .then(function (response) {
              that.getCategories()
            })
            .catch(function (error) {
              that.isLoaded = true
              if (error.response && error.response.status === 409) {
                that.$alertV({'message': error.response.data, 'type': 'error'})
              }
              else {
                that.$alertV({'message': that.strings.addErrorMessage, 'type': 'error'})
              }
            })
        }
      },
      delCategory (event, id) {
        let categoryId = null
        if (event !== undefined && event !== null && event.target.dataset.id !== undefined && event.target.dataset.id !== null && event.target.dataset.id > 0) {
          categoryId = event.target.dataset.id
        }
        else if (id !== undefined && id !== null && id > 0) {
          categoryId = id
        }
        if (categoryId !== null && categoryId > 0) {
          let that = this
          $('#modal-' + this._uid).modal({
            closable: false,
            onApprove () {
              that.isLoaded = false
              Axios.delete(that.properties.routeCategory + '/' + categoryId)
                .then(function (response) {
                  that.getCategories()
                })
                .catch(function (error) {
                  that.isLoaded = true
                  if (error.response && error.response.status === 409) {
                    that.$alertV({'message': error.response.data, 'type': 'error'})
                  }
                  else {
                    that.$alertV({'message': that.strings.delErrorMessage, 'type': 'error'})
                  }
                })
            }
          }).modal('show')
        }
      },
      updateCategory (event, emitPostValue) {
        let postValue = {}
        let key = ''
        let id = ''
        let that = this
        if (emitPostValue !== undefined && emitPostValue !== null) {
          postValue = emitPostValue.postValue
          key = emitPostValue.key
          id = emitPostValue.id
        }
        else {
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
        }
        if (postValue[key] !== undefined && postValue[key] !== null && postValue[key] !== '') {
          Axios.patch(this.properties.routeCategory + '/' + id, {description: postValue})
            .then(function (response) {
              that.$alertV({'message': that.strings.patchSuccessMessage, 'type': 'success'})
            })
            .catch(function (error) {
              that.getCategories()
              if (error.response && error.response.status === 409) {
                that.$alertV({'message': error.response.data, 'type': 'error'})
              }
              else {
                that.$alertV({'message': that.strings.patchErrorMessage, 'type': 'error'})
              }
            })
        }
        else {
          this.getCategories()
          this.$alertV({'message': this.strings.patchErrorMessage, 'type': 'error'})
        }
      },
      shiftUp (event) {
        this.shiftCategory(event, 'up')
      },
      shiftDown (event) {
        this.shiftCategory(event, 'down')
      },
      shiftCategory (event, way) {
        let animTime = 600
        let me = $(event.target).closest('.accordion')
        let sibling = null
        if (way === 'down') {
          sibling = $(me).next('.accordion')
        }
        else {
          sibling = $(me).prev('.accordion')
        }
        let meTop = ($(me).position()).top
        let siblingTop = ($(sibling).position()).top
        // Animation
        $(me).addClass('main-action')
        $(sibling).addClass('sub-action')
        $(me).animate(
          {top: siblingTop - meTop},
          {
            duration: animTime,
            complete () {
              $(me).removeClass('main-action')
            }
          }
        )
        let that = this
        $(sibling).animate(
          {top: meTop - siblingTop},
          {
            duration: animTime,
            complete () {
              $(sibling).removeClass('sub-action')
              let route = null
              if (way === 'down') {
                route = that.routeShiftDownCategory
              }
              else {
                route = that.routeShiftUpCategory
              }
              Axios.patch(route, {id: event.target.dataset.value})
                .then(function (response) {
                  that.getCategories()
                  $(me).css('top', 0)
                  $(sibling).css('top', 0)
                  that.$alertV({'message': that.strings.patchSuccessMessage, 'type': 'success'})
                })
                .catch(function (error) {
                  that.getCategories()
                  $(me).animate('top', 0)
                  $(sibling).animate('top', 0)
                  if (error.response && error.response.status === 409) {
                    that.$alertV({'message': error.response.data, 'type': 'error'})
                  }
                  else {
                    that.$alertV({'message': that.strings.patchErrorMessage, 'type': 'error'})
                  }
                })
            }
          }
        )
      },
      appendToCategory (childId, parentId) {
        console.log('append')
        let that = this
        Axios.patch(this.routeAppendToCategory, {childId: childId, parentId: parentId})
          .then(function (response) {
            that.getCategories()
            that.$alertV({'message': that.strings.patchSuccessMessage, 'type': 'success'})
          })
          .catch(function (error) {
            if (error.response && error.response.status === 409) {
              that.$alertV({'message': error.response.data, 'type': 'error'})
            }
            else {
              that.$alertV({'message': that.strings.patchErrorMessage, 'type': 'error'})
            }
          })
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
