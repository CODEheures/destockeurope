'use strict';

let destockTools = {
    calcMargins:  function(advert, forSeller) {
        let margins= {
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
        };

        if(advert){
            let originalUnitSellerPrice = advert.originalPrice;
            let originalTotalSellerPrice = Math.floor(advert.totalQuantity*(advert.originalPrice*(1-(advert.discount_on_total/100))));

            let unitSellerPrice = originalUnitSellerPrice/(Math.pow(10,advert.priceSubUnit));
            let totalSellerPrice = originalTotalSellerPrice/(Math.pow(10,advert.priceSubUnit));
            let totalPriceByLot = unitSellerPrice*advert.totalQuantity;

            let unitMargin=0;
            let totalMargin=0;
            let lotMiniMargin=0;
            let priceMargin=0;
            let totalPriceMargin=0;
            let totalPriceByLotMargin=0;
            let globalDiscount=0;

            if(forSeller){
                unitMargin =  advert.originalPrice-advert.buyingPrice;
                unitMargin = Math.floor(unitMargin)/Math.pow(10,advert.priceSubUnit);

                lotMiniMargin = unitMargin*advert.lotMiniQuantity;

                totalMargin = originalTotalSellerPrice-advert.buyingPrice*advert.totalQuantity;
                totalMargin = Math.floor(totalMargin)/Math.pow(10,advert.priceSubUnit);

                globalDiscount = advert.discount_on_total;
            } else {
                unitMargin =  advert.originalPrice*advert.price_coefficient/100;
                unitMargin = Math.floor(unitMargin)/Math.pow(10,advert.priceSubUnit);

                lotMiniMargin = unitMargin*advert.lotMiniQuantity;

                totalMargin = originalTotalSellerPrice*advert.price_coefficient_total/100;
                totalMargin = Math.floor(totalMargin)/Math.pow(10,advert.priceSubUnit);

                priceMargin = unitSellerPrice + unitMargin;
                totalPriceMargin = totalSellerPrice + totalMargin;

                totalPriceByLotMargin = priceMargin*advert.totalQuantity;

                globalDiscount = (1 - totalPriceMargin/totalPriceByLotMargin)*100;
            }

            margins.coefficientTotalIsOverMax = totalPriceMargin > priceMargin*advert.totalQuantity;


            margins.unitMargin = unitMargin.toFixed(advert.priceSubUnit);
            margins.totalMargin = totalMargin.toFixed(advert.priceSubUnit);
            margins.lotMiniMargin = lotMiniMargin.toFixed(advert.priceSubUnit);
            margins.unitSellerPrice = unitSellerPrice.toFixed(advert.priceSubUnit);
            margins.priceMargin = priceMargin.toFixed(advert.priceSubUnit);
            margins.totalSellerPrice = totalSellerPrice.toFixed(advert.priceSubUnit);
            margins.totalSellerPriceWholePart = _.split(margins.totalSellerPrice,'.')[0];
            margins.totalSellerPriceDecimalPart = _.split(margins.totalSellerPrice,'.')[1];
            margins.totalPriceMargin = totalPriceMargin.toFixed(advert.priceSubUnit);
            margins.totalPriceMarginWholePart = _.split(margins.totalPriceMargin,'.')[0];
            margins.totalPriceMarginDecimalPart = _.split(margins.totalPriceMargin,'.')[1];
            margins.totalPriceByLot = totalPriceByLot.toFixed(advert.priceSubUnit);
            margins.totalPriceByLotMargin = totalPriceByLotMargin.toFixed(advert.priceSubUnit);
            margins.globalDiscount = globalDiscount.toFixed(2);
        }


        return margins;
    }
};

module.exports = destockTools;