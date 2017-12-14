<template>
    <div>
        <div class="ui mini pagination menu" v-if="datapages.length>0">
            <template v-for="datapage in datapages" >
                <template v-if="datapage.label == '...'">
                    <div class="disabled item" v-html="datapage.label"></div>
                </template>
                <template v-else>
                    <a :class="datapage.isDisabled ? 'disabled item' : 'active item'" :href="datapage.href"
                       v-on:click.prevent="changePage"
                       v-html="datapage.label"
                       :title="datapage.title">
                    </a>
                </template>
            </template>
        </div>
        <div style="padding-top: 0.5rem" v-if="perPageOption">
            <p>
                <a :class="'ui mini ' + (perPage == properties.minPerPage ? 'blue' : 'basic') + ' circular label'" href="#" v-on:click.prevent="changePerPage(properties.minPerPage)">x{{ properties.minPerPage }}</a>
                <a :class="'ui mini ' + (perPage == 20 ? 'blue' : 'basic') + ' circular label'" href="#" v-on:click.prevent="changePerPage(20)">x20</a>
                <a :class="'ui mini ' + (perPage == 50 ? 'blue' : 'basic') + ' circular label'" href="#" v-on:click.prevent="changePerPage(50)">x50</a>
                <a :class="'ui mini ' + (perPage == 100 ? 'blue' : 'basic') + ' circular label'" href="#" v-on:click.prevent="changePerPage(100)">x100</a>
            </p>
        </div>
        <div v-if="datapages.length==0">
            <p>{{ strings.pageLabel }} 1/1</p>
        </div>
    </div>

</template>

<script>
  /**
   * Props
   *  - pages: Object. The page object by laravel: {current_page: 1, first_page_url: http..., .... }
   *  - routeGetList: String. The route to follow with add page number (use with xhr)
   *  - fakePageRoute: String. The route to follow withadd page number (use with href)
   *
   * Events:
   *  @changePage: emit the url to go (not automatic href to prevent if prefer xhr loading)
   */
  import Parser from 'url'
  import { DestockTools } from '../../../destockTools'
  export default {
    props: {
      pages: Object,
      routeGetList: {
        type: String,
        required: false,
        default: ''
      },
      fakePageRoute: {
        type: String,
        required: false,
        default: null
      },
      perPageOption: {
        type: Boolean,
        required: false,
        default: false
      }
    },
    computed: {
      strings () {
        return this.$store.state.strings['pagination']
      },
      properties () {
        return this.$store.state.properties['global']
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
        url: '',
        perPage: 0
      }
    },
    mounted () {
      this.constructPages()
      this.perPage = this.properties.perPage
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
        this.$emit('changePage', event.target.href)
      },
      changePerPage (value) {
        this.perPage = value
        let urlBase = ''
        if (this.fakePageRoute === undefined || this.fakePageRoute === null) {
          urlBase = this.routeGetList
        }
        else {
          urlBase = this.fakePageRoute
        }
        let nextUrl = DestockTools.getNextUrl(urlBase, 'perPage', value, true)
        this.$emit('changePage', nextUrl)
      }
    }
  }
</script>
