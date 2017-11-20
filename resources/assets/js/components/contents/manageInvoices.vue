<template>
    <div class="ui grid">
        <div :id="'modal-'+_uid" class="ui basic modal">
            <i class="close icon"></i>
            <div class="header">
                {{ strings.modalValidHeader }}
            </div>
            <div class="image content">
                <div class="image">
                    <i class="legal icon"></i>
                </div>
                <div class="description">
                    <p>{{ strings.modalValidDescriptionOne+refundAmount+strings.modalValidDescriptionTwo }}</p>
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
        <div class="sixteen wide column">
            <h2 class="ui header">{{ strings.contentHeader }}</h2>
        </div>
        <div class="row">
            <div class="sixteen wide column">
                <div class="row filters">
                    <invoice-filter
                            :update="update"
                            :filter="filter"
                            :route-search="dataRouteGetInvoicesList"
                            :flag-reset-search="dataFlagResetSearch"
                            @clearSearchResults="clearSearchResults"
                            @refreshResults="refreshResults"
                    ></invoice-filter>
                </div>
            </div>
            <div class="sixteen wide column">
                <div class="row">
                    <div class="ui active inverted dimmer" v-if="!isLoaded">
                        <div class="ui large text loader">Loading</div>
                    </div>
                    <invoices-by-list
                            :route-get-invoices-list="dataRouteGetInvoicesList"
                            :flag-force-reload="dataForceReload"
                            @refund="refund($event)"
                            @paginate="paginate=$event"
                            @loadError="$alertV({'message': strings.loadErrorMessage, 'type': 'error'})"
                    ></invoices-by-list>
                </div>
                <div class="ui right aligned grid">
                    <div class="sixteen wide column pagination">
                        <pagination
                                :pages="paginate"
                                :route-get-list="dataRouteGetInvoicesList"
                                @changePage="changePage"
                        ></pagination>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>


<script>
  import Parser from 'url'
  import Axios from 'axios'
  export default {
    props: [
      // vue routes
      'routeGetInvoicesList'
    ],
    computed: {
      strings () {
        return this.$store.state.strings['manage-invoices']
      },
      properties () {
        return this.$store.state.properties['global']
      }
    },
    data () {
      return {
        sendMessage: false,
        typeMessage: '',
        message: '',
        filter: {},
        paginate: {},
        dataRouteGetInvoicesList: '',
        dataFlagResetSearch: false,
        oldChoice: {},
        update: false,
        refundAmount: 0,
        dataForceReload: false,
        isLoaded: true
      }
    },
    mounted () {
      sessionStorage.clear()
      this.updateResults()
    },
    methods: {
      urlForFilter (init = false) {
        let urlBase = init ? this.routeGetInvoicesList : this.dataRouteGetInvoicesList
        let parsed = Parser.parse(urlBase, true)
        parsed.search = undefined
        parsed.query = {}
        // reset sessionStorageFilter
        sessionStorage.removeItem('filter')
        sessionStorage.setItem('filter', JSON.stringify(this.filter))
        for (let elem in this.filter) {
          if (this.filter[elem] !== undefined && this.filter[elem] !== null) {
            parsed.query[elem] = (this.filter[elem]).toString()
          }
        }
        return Parser.format(parsed)
      },
      clearInputSearch () {
        if ('resultsFor' in this.filter) {
          delete this.filter.resultsFor
          this.dataFlagResetSearch = !this.dataFlagResetSearch
          return true
        }
        else {
          return false
        }
      },
      updateResults () {
        this.update = !this.update
        this.dataRouteGetInvoicesList = this.urlForFilter(true)
      },
      refund (refund) {
        event.preventDefault()
        this.refundAmount = refund.amount
        let that = this
        $('#modal-' + this._uid).modal({
          closable: false,
          onApprove () {
            that.isLoaded = false
            Axios.get(refund.refundUrl)
              .then(function (response) {
                that.isLoaded = true
                that.dataForceReload = !that.dataForceReload
                that.$alertV({'message': that.strings.invoiceRefundSuccess, 'type': 'success'})
              })
              .catch(function (error) {
                if (error.response && error.response.status === 409) {
                  that.$alertV({'message': error.response.data, 'type': 'error'})
                }
                else {
                  that.$alertV({'message': that.strings.loadErrorMessage, 'type': 'error'})
                }
                that.isLoaded = true
              })
          }
        }).modal('show')
      },
      clearSearchResults () {
        let haveClearAction = this.clearInputSearch()
        if (haveClearAction) {
          this.updateResults(true)
        }
      },
      refreshResults (query) {
        if (query !== undefined && query.length >= this.properties.filterMinLengthSearch) {
          this.filter.resultsFor = query
          this.updateResults(true)
        }
      },
      changePage (url) {
        let that = this
        $('html, body').animate({
          scrollTop: 0
        }, 600, function () {
          that.dataRouteGetInvoicesList = url
        })
      }
    }
  }
</script>
