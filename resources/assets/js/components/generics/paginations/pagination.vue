<template>
    <div>
        <div class="ui mini pagination menu" v-if="datapages.length>0">
            <template v-for="datapage in datapages" >
                <template v-if="datapage.label == '...'">
                    <div class="disabled item" v-html="datapage.label"></div>
                </template>
                <template v-else>
                    <a :class="datapage.isDisabled ? 'disabled item' : 'active item'" :href="datapage.href"
                       v-on:click="changePage"
                       v-html="datapage.label"
                       :title="datapage.title">
                    </a>
                </template>
            </template>
        </div>
        <div v-if="datapages.length==0">
            <p>{{ strings.pageLabel }} 1/1</p>
        </div>
    </div>

</template>

<script>
  import Parser from 'url'
  export default {
    props: {
      pages: Object,
      routeGetList: String,
      fakePageRoute: {
        type: String,
        required: false,
        default: null
      }
    },
    computed: {
      strings () {
        return this.$store.state.strings['pagination']
      }
    },
    watch: {
      pages () {
        this.constructPages()
      },
      fakePageRoute () {
        this.constructPages()
      }
    },
    data () {
      return {
        datapages: [],
        url: ''
      }
    },
    mounted () {
      this.constructPages()
    },
    methods: {
      urlForPageNumber (pageNumber) {
        let parsed = null
        if (this.fakePageRoute === undefined || this.fakePageRoute === null) {
          parsed = Parser.parse(this.routeGetList, true)
        }
        else {
          parsed = Parser.parse(this.fakePageRoute, true)
        }
        parsed.search = undefined
        parsed.hash = undefined
        parsed.query.page = pageNumber.toString()
        return Parser.format(parsed)
      },
      constructPages () {
        this.datapages = []
        if (this.pages.last_page > 1) {
          if (this.pages.last_page > 1 && this.pages.last_page <= 6) {
            // previous if current page > 1
            if (this.pages.current_page > 1) {
              this.datapages.push({
                label: '<i class="chevron left icon" style="pointer-events: none"></i>',
                href: this.urlForPageNumber(this.pages.current_page - 1),
                title: this.strings.pagePreviousLabel
              })
            }
            for (let i = 1; i <= this.pages.last_page; i++) {
              this.datapages.push({
                label: i.toString(),
                href: this.urlForPageNumber(i),
                isDisabled: i === this.pages.current_page,
                title: this.strings.pageLabel + ' ' + (i)
              })
            }
            // next if current page < last page
            if (this.pages.current_page < this.pages.last_page) {
              this.datapages.push({
                label: '<i class="chevron right icon" style="pointer-events: none"></i>',
                href: this.urlForPageNumber(this.pages.current_page + 1),
                title: this.strings.pageNextLabel
              })
            }
          }
          else {
            if (this.pages.current_page <= 3) {
              for (let i = 1; i <= this.pages.current_page + 2; i++) {
                this.datapages.push({
                  label: i.toString(),
                  href: this.urlForPageNumber(i),
                  isDisabled: i === this.pages.current_page,
                  title: this.strings.pageLabel + ' ' + (i)
                })
              }
              this.datapages.push({
                label: '...',
                href: null,
                isDisabled: true,
                title: ''
              })
              this.datapages.push({
                label: this.pages.last_page,
                href: this.urlForPageNumber(this.pages.last_page),
                title: this.strings.pageNextLabel
              })
            }
            if (this.pages.current_page > 3) {
              // first if current page > 1
              if (this.pages.current_page > 1) {
                this.datapages.push({
                  label: '1',
                  href: this.urlForPageNumber(1),
                  title: this.strings.pagePreviousLabel
                })
              }
              this.datapages.push({
                label: '...',
                href: null,
                isDisabled: true,
                title: ''
              })
              if (this.pages.current_page + 3 < this.pages.last_page) {
                for (let i = this.pages.current_page - 1; i <= this.pages.current_page + 1; i++) {
                  this.datapages.push({
                    label: i.toString(),
                    href: this.urlForPageNumber(i),
                    isDisabled: i === this.pages.current_page,
                    title: this.strings.pageLabel + ' ' + (i)
                  })
                }
                this.datapages.push({
                  label: '...',
                  href: null,
                  isDisabled: true,
                  title: ''
                })
                // last if current page < last page
                if (this.pages.current_page < this.pages.last_page) {
                  this.datapages.push({
                    label: this.pages.last_page,
                    href: this.urlForPageNumber(this.pages.last_page),
                    title: this.strings.pageNextLabel
                  })
                }
              }
              else {
                for (let i = this.pages.current_page - 2; i <= this.pages.last_page; i++) {
                  this.datapages.push({
                    label: i.toString(),
                    href: this.urlForPageNumber(i),
                    isDisabled: i === this.pages.current_page,
                    title: this.strings.pageLabel + ' ' + (i)
                  })
                }
              }
            }
          }
        }
      },
      changePage (event) {
        event.preventDefault()
        this.$emit('changePage', event.target.href)
      }
    }
  }
</script>
