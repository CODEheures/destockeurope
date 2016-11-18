<template>
    <div>
        <div :id="'range-'+_uid"></div>
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
            var rangeSlider = document.getElementById('range-'+this._uid);

            this.start[0] = (this.mini);
            this.start[1] = (this.maxi);

            noUiSlider.create(rangeSlider, {
                start: [this.mini,this.maxi],
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
            rangeSlider.noUiSlider.on('change', function( values, handle ) {
                that.updateStart(values, handle);
                that.emitRangeChange(values);
            });
            this.$watch('mini', function () {
                this.updateRange();
            });
            this.$watch('maxi', function () {
                this.updateRange();
            })
        },
        methods: {
            updateStart: function (values, handle) {
                this.$set(this.start, handle, values[handle]);
            },
            updateRange: function () {
                var that = this;
                var rangeSlider = document.getElementById('range-'+this._uid);
                rangeSlider.noUiSlider.updateOptions({
                    range: {
                        'min': [that.mini],
                        'max': [that.maxi]
                    }
                });
                this.start[0] = (this.mini);
                this.start[1] = (this.maxi);
                rangeSlider.noUiSlider.set([this.mini, this.maxi]);
                this.emitRangeChange(this.start);
            },
            emitRangeChange: function (values) {
                this.$parent.$emit('rangeUpdate', {name: this.name, values: values});
            }
        }
    }
</script>
