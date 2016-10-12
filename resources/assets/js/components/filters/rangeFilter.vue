<template>
    <div>
        <div id="price-range-slider"></div>
        <div class="ui grid">
            <div class="two column row">
                <div v-for="(item,index) in start" class="column" :class="[index == 1 ? 'right aligned' : '']">
                    <div>{{ index == 0 ? 'mini: ' : 'maxi: ' }}{{ item }}</div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            mini: Number,
            maxi: Number,
            name: String
        },
        data: () => {
            return {
                start: [0,1]
            }
        },
        mounted () {
            var rangeSlider = document.getElementById('price-range-slider');
            var range = this.maxi-this.mini;

            this.start[0] = (this.mini+range*0.1);
            this.start[1] = (this.mini+range*0.9);

            noUiSlider.create(rangeSlider, {
                start: [this.start[0],this.start[1]],
                connect: [false,true,false],
                range: {
                    'min': [this.mini],
                    'max': [this.maxi]
                }
            });

            var that = this;
            rangeSlider.noUiSlider.on('update', function( values, handle ) {
                that.updateStart(values, handle);
            });
        },
        methods: {
            updateStart: function (values, handle) {
                this.$set(this.start, handle, values[handle]);
                this.$parent.$emit('rangeUpdate', {name: this.name, values: values});
            }
        }
    }
</script>
