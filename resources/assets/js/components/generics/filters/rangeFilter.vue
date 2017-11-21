<template>
    <div class="range">
        <span class="range-title">{{ title }}</span>
        <input type="text" :id="_uid" name="example_name" value="" />
    </div>
</template>

<script>
  /**
   * Props
   *  - mini: Number. The min value of the slider
   *  - maxi: Number. The max value of the slider
   *  - handleMin: Number. The position of the handle min slider
   *  - handleMax: Number. The position of the handle max slider
   *  - step: Number. To move slider with a step
   *  - name: String. The name of the slider (use in emitters)
   *  - prefix: String. The prefix of handles values
   *  - title: String. Title above the slider
   *
   * Events:
   *  @rangeUpdate: emit object of handles position on end move {name: that.name, values: [parseFloat(data.from), parseFloat(data.to)]}
   *
   */
  export default {
    props: {
      mini: Number,
      maxi: Number,
      handleMin: Number,
      handleMax: Number,
      step: {
        type: Number,
        required: false,
        default: 1
      },
      name: String,
      prefix: String,
      title: String
    },
    watch: {
      prefix () {
        this.updateSlider()
      },
      mini () {
        this.updateSlider()
      },
      maxi () {
        this.updateSlider()
      },
      handleMin () {
        this.updateSlider()
      },
      handleMax () {
        this.updateSlider()
      }
    },
    data () {
      return {
        slider: Object
      }
    },
    mounted () {
      let that = this
      let elemSlider = $('#' + this._uid)
      elemSlider.ionRangeSlider({
        type: 'double',
        min: that.mini,
        max: that.maxi,
        from: that.handleMin,
        to: that.handleMax,
        grid: true,
        step: that.step,
        prefix: that.prefix,
        onFinish (data) {
          that.$emit('rangeUpdate', {name: that.name, values: [parseFloat(data.from), parseFloat(data.to)]})
        }
      })
      this.slider = elemSlider.data('ionRangeSlider')
    },
    methods: {
      updateSlider () {
        let that = this
        this.slider.update({
          min: that.mini,
          max: that.maxi,
          prefix: that.prefix,
          from: that.handleMin,
          to: that.handleMax,
          onFinish (data) {
            that.$emit('rangeUpdate', {name: that.name, values: [parseFloat(data.from), parseFloat(data.to)]})
          }
        })
      }
    }
  }
</script>
