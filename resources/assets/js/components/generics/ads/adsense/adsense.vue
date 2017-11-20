<template>
    <ins class="adsbygoogle"
         :data-ad-client="adClient"
         :data-ad-slot="adSlot"
         :style="adStyle"></ins>
</template>

<script>
  /**
   * Props
   *  - adClient: String. The client ad ID: 'ca-pub-xxxxxxxxxxx'
   *  - adSlot: String. The slot ad ID: '145407xxxx'
   *  - adStyle: String. CSS style of AD: 'display: block; ...'
   */
  export default {
    props: {
      adClient: {
        type: String,
        required: true
      },
      adSlot: {
        type: String,
        required: true
      },
      adStyle: {
        type: String,
        required: false,
        default: 'display: block'
      }
    },
    mounted () {
      (window.adsbygoogle = window.adsbygoogle || []).push({})
    }
  }
</script>
