<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    @foreach($advert->breadCrumb as $item)
    {
      "@type": "ListItem",
      "position": {{ $loop->index+1 }},
      "item": {
        "@id": "{{ route('home', ['categoryId' => $item->id, 'lang' => \Illuminate\Support\Facades\App::getLocale()]) }}",
        "name": "{{ $item->description[\Illuminate\Support\Facades\App::getLocale()] }}"
      }
    }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>