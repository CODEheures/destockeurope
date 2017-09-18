<script type="application/ld+json">
{
  "@context": "http://schema.org/",
  "@type": "Product",
  "name": "{{ $advert->title }}",
  "image": [
        @foreach($advert->pictures as $picture)
        "{{ $picture->thumbUrl }}",
        "{{ $picture->normalUrl }}"@if(!$loop->last),@endif
        @endforeach
   ],
  "description": "{{ $advert->description }}",
  "mpn": "{{ $advert->manu_ref }}",
  "offers": {
    "@type": "Offer",
    "priceCurrency": "{{ $advert->currency }}",
    "price": "{{ \App\Common\MoneyUtils::getPriceWithDecimal($advert->originalPriceWithMargin,$advert->currency,false) }}",
    "itemCondition": "http://schema.org/UsedCondition",
    "availability": "http://schema.org/InStock",
    "seller": {
      "@type": "Organization",
      "name": "{{ $advert->user->compagnyName }}"
    }
  }
}
</script>