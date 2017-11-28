import _ from 'lodash'
import Parser from 'url'
import Axios from 'axios'

class DestockTools {
  static calcMargins (advert, forSeller) {
    let margins = {
      unitMargin: 0,
      totalMargin: 0,
      lotMiniMargin: 0,
      unitSellerPrice: 0,
      priceMargin: 0,
      totalSellerPrice: 0,
      totalSellerPriceWholePart: 0,
      totalSellerPriceDecimalPart: 0,
      totalPriceMargin: 0,
      totalPriceMarginWholePart: 0,
      totalPriceMarginDecimalPart: 0,
      totalPriceByLot: 0,
      totalPriceByLotMargin: 0,
      globalDiscount: 0,
      coefficientTotalIsOverMax: false
    }

    if (advert) {
      let originalUnitSellerPrice = advert.originalPrice
      let originalTotalSellerPrice = Math.floor(advert.totalQuantity * (advert.originalPrice * (1 - (advert.discount_on_total / 100))))

      let unitSellerPrice = originalUnitSellerPrice / (Math.pow(10, advert.priceSubUnit))
      let totalSellerPrice = originalTotalSellerPrice / (Math.pow(10, advert.priceSubUnit))
      let totalPriceByLot = unitSellerPrice * advert.totalQuantity

      let unitMargin = 0
      let totalMargin = 0
      let lotMiniMargin = 0
      let priceMargin = 0
      let totalPriceMargin = 0
      let totalPriceByLotMargin = 0
      let globalDiscount = 0

      if (forSeller) {
        unitMargin = advert.originalPrice - advert.buyingPrice
        unitMargin = Math.floor(unitMargin) / Math.pow(10, advert.priceSubUnit)
        unitMargin = this.truncFloat(unitMargin, advert.priceSubUnit)

        lotMiniMargin = unitMargin * advert.lotMiniQuantity
        lotMiniMargin = this.truncFloat(lotMiniMargin, advert.priceSubUnit)

        totalMargin = originalTotalSellerPrice - advert.buyingPrice * advert.totalQuantity
        totalMargin = Math.floor(totalMargin) / Math.pow(10, advert.priceSubUnit)
        totalMargin = this.truncFloat(totalMargin, advert.priceSubUnit)

        globalDiscount = advert.discount_on_total
      }
      else {
        unitMargin = advert.originalPrice * advert.price_coefficient / 100
        unitMargin = Math.floor(unitMargin) / Math.pow(10, advert.priceSubUnit)
        unitMargin = this.truncFloat(unitMargin, advert.priceSubUnit)

        lotMiniMargin = unitMargin * advert.lotMiniQuantity
        lotMiniMargin = this.truncFloat(lotMiniMargin, advert.priceSubUnit)

        totalMargin = originalTotalSellerPrice * advert.price_coefficient_total / 100
        totalMargin = Math.floor(totalMargin) / Math.pow(10, advert.priceSubUnit)
        totalMargin = this.truncFloat(totalMargin, advert.priceSubUnit)

        priceMargin = unitSellerPrice + unitMargin
        priceMargin = this.truncFloat(priceMargin, advert.priceSubUnit)

        totalPriceMargin = totalSellerPrice + totalMargin
        totalPriceMargin = this.truncFloat(totalPriceMargin, advert.priceSubUnit)

        totalPriceByLotMargin = priceMargin * advert.totalQuantity
        totalPriceByLotMargin = this.truncFloat(totalPriceByLotMargin, advert.priceSubUnit)

        globalDiscount = (1 - totalPriceMargin / totalPriceByLotMargin) * 100
      }

      margins.coefficientTotalIsOverMax = totalPriceMargin >= this.truncFloat(priceMargin * advert.totalQuantity, advert.priceSubUnit)

      margins.unitMargin = unitMargin.toFixed(advert.priceSubUnit)
      margins.totalMargin = totalMargin.toFixed(advert.priceSubUnit)
      margins.lotMiniMargin = lotMiniMargin.toFixed(advert.priceSubUnit)
      margins.unitSellerPrice = unitSellerPrice.toFixed(advert.priceSubUnit)
      margins.priceMargin = priceMargin.toFixed(advert.priceSubUnit)
      margins.totalSellerPrice = totalSellerPrice.toFixed(advert.priceSubUnit)
      margins.totalSellerPriceWholePart = _.split(margins.totalSellerPrice, '.')[0]
      margins.totalSellerPriceDecimalPart = _.split(margins.totalSellerPrice, '.')[1]
      margins.totalPriceMargin = totalPriceMargin.toFixed(advert.priceSubUnit)
      margins.totalPriceMarginWholePart = _.split(margins.totalPriceMargin, '.')[0]
      margins.totalPriceMarginDecimalPart = _.split(margins.totalPriceMargin, '.')[1]
      margins.totalPriceByLot = totalPriceByLot.toFixed(advert.priceSubUnit)
      margins.totalPriceByLotMargin = totalPriceByLotMargin.toFixed(advert.priceSubUnit)
      margins.globalDiscount = globalDiscount.toFixed(2)
    }

    return margins
  }

  static truncFloat (value, decimal) {
    return parseFloat(value.toFixed(decimal))
  }

  static findInUrl (param) {
    let urlBase = window.location.href
    let parsed = Parser.parse(urlBase, true)
    parsed.search = undefined

    if (param in parsed.query) {
      return parsed.query[param]
    }
    else {
      return null
    }
  }

  static getNextUrl (urlBase, paramName, paramValue, reinitPage = false) {
    let parsed = Parser.parse(urlBase, true)
    parsed.search = undefined

    if (paramValue != null) {
      parsed.query[paramName] = paramValue.toString()
    }
    else if (paramName in parsed.query) {
      delete parsed.query[paramName]
    }

    if ('page' in parsed.query && reinitPage) { delete parsed.query['page'] }
    return Parser.format(parsed)
  }

  static goToUrl (url) {
    this.paceRestart()
    window.location.href = url
  }

  static paceRestart () {
    let paceForcing = document.getElementById('paceforcing')

    if (paceForcing !== null) { paceForcing.remove() }
    window.Pace.restart()
  }

  static setBreadCrumbItems ($store, id = null, allLabel) {
    let breadcrumbItems = []
    let categoryId = id !== null ? id : this.findInUrl('categoryId')
    // eslint-disable-next-line eqeqeq
    if (categoryId !== null && categoryId == parseInt(categoryId) && categoryId > 0) {
      Axios.get($store.state.properties.global.routeCategory + '/' + categoryId)
        .then(function (response) {
          let chainedCategories = response.data
          breadcrumbItems.push({
            name: allLabel,
            value: 0
          })
          chainedCategories.forEach(function (elem, index) {
            breadcrumbItems.push({
              name: elem['description'][$store.state.properties.global.actualLocale],
              value: elem.id
            })
          })
        })
        .catch(function () {
        })
    }
    $store.commit('setProperties', {name: 'breadcrumbItems', properties: breadcrumbItems})
  }

  static smoothscroll () {
    let h1PosTop = document.querySelector('h1').getBoundingClientRect().top
    let currentScroll = document.documentElement.scrollTop || document.body.scrollTop
    let delta = 110 - h1PosTop
    if (h1PosTop < 110) {
      window.requestAnimationFrame(this.smoothscroll.bind(this))
      window.scrollTo(0, currentScroll - (delta / 7))
    }
  }
}

export { DestockTools }
