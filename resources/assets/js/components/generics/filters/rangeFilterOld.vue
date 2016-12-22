<template>
    <div>
        <div :id="'range-'+_uid"></div>
        <div class="ui grid">
            <div class="two column row">
                <div class="column left aligned">
                    <div>{{ 'mini: ' + mini }}</div>
                </div>
                <div class="column right aligned">
                    <div>{{ 'maxi: ' + maxi }}</div>
                </div>
                <!--<div v-for="(item,index) in start" class="column" :class="[index == 1 ? 'right aligned' : '']">-->
                    <!--<div>{{ index == 0 ? 'mini: ' : 'maxi: ' }}{{ item }}</div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            mini: Number,
            maxi: Number,
            handleMin: Number,
            handleMax: Number,
            update: Boolean,
            name: String
        },
        data: () => {
            return {
                start: [0,0]
            }
        },
        mounted () {
            let rangeSlider = document.getElementById('range-'+this._uid);

            this.start[0] = this.handleMin;
            this.start[1] = this.handleMax;

            noUiSlider.create(rangeSlider, {
                start: [this.start[0],this.start[1]],
                connect: [false,true,false],
                tooltips: [ true , true],
                range: {
                    'min': [this.mini],
                    'max': [this.maxi]
                }
            });

            let that = this;
            rangeSlider.noUiSlider.on('update', function( values, handle ) {
                that.updateStart(values, handle);
            });
            rangeSlider.noUiSlider.on('change', function( values, handle ) {
                that.updateStart(values, handle);
                that.$parent.$emit('rangeUpdate', {name: that.name, values: [parseFloat(values[0]), parseFloat(values[1])]});
            });
            this.$watch('update', function () {
                this.updateRange();
            });
        },
        methods: {
            updateStart: function (values, handle) {
                this.$set(this.start, handle, values[handle]);
            },
            updateRange: function () {
                let that = this;
                let rangeSlider = document.getElementById('range-'+this._uid);
                rangeSlider.noUiSlider.updateOptions({
                    range: {
                        'min': [that.mini],
                        'max': [that.maxi]
                    }
                });
                this.start[0] = this.handleMin;
                this.start[1] = this.handleMax;
                rangeSlider.noUiSlider.set([this.start[0], this.start[1]]);
                if(this.mini == this.maxi){
                    rangeSlider.setAttribute('disabled', true);
                } else {
                    rangeSlider.removeAttribute('disabled');
                }
            }
        }
    }
</script>
