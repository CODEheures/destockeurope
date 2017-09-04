<template>
    <div class="range">
        <span class="range-title">{{ title }}</span>
        <input type="text" :id="_uid" name="example_name" value="" />
    </div>
</template>

<script>
    export default {
        props: {
            //Vue routes
            //Vue Vars
            mini: Number,
            maxi: Number,
            handleMin: Number,
            handleMax: Number,
            step: {
                type: Number,
                required: false,
                default: 1
            },
                //Vue Strings
            name: String,
            prefix: String,
            title: String
        },
        data: () => {
            return {

            }
        },
        mounted () {
            let that = this;
            let elemSlider = $('#'+this._uid);
            elemSlider.ionRangeSlider({
                type: "double",
                min: that.mini,
                max: that.maxi,
                from: that.handleMin,
                to: that.handleMax,
                grid: true,
                step: that.step,
                prefix: that.prefix
            });
            let slider = elemSlider.data("ionRangeSlider");
            this.$watch('prefix', function () {
                slider.update({
                    prefix: that.prefix
                });
            });
            this.$watch('maxi', function () {
                slider.update({
                    max: that.maxi
                });
            });
            this.$watch('mini', function () {
                slider.update({
                    min: that.mini
                });
            });
            this.$watch('handleMin', function () {
                slider.update({
                    from: that.handleMin,
                });
            });
            this.$watch('handleMax', function () {
                slider.update({
                    to: that.handleMax,
                });
            });
//
            slider.update({
                min: that.mini,
                max: that.maxi,
                prefix: that.prefix,
                from: that.handleMin,
                to: that.handleMax,
                onFinish: function (data) {
                    that.$parent.$emit('rangeUpdate', {name: that.name, values: [parseFloat(data.from), parseFloat(data.to)]});
                }
            });
        },
        updated () {
            let elemSlider = $('#'+this._uid);
            let slider = elemSlider.data("ionRangeSlider");
            let that = this;
            slider.update({
                min: that.mini,
                max: that.maxi,
                prefix: that.prefix,
                from: that.handleMin,
                to: that.handleMax,
                onFinish: function (data) {
                    that.$parent.$emit('rangeUpdate', {name: that.name, values: [parseFloat(data.from), parseFloat(data.to)]});
                }
            });
        },
        methods: {

        }
    }
</script>
