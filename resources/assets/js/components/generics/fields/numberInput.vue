<template>
        <input ref="input" v-bind:value="value" autocomplete="off"
               v-on:input="updateValue($event.target.value)"
               v-on:blur="formatValue">
</template>

<script>
    export default {
        props: {
            value: {
                type: Number,
                default: 0
            },
            decimal: {
                type: Number,
                required: false,
                default: 0
            },
            min: {
                type: Number,
                required: false,
                default: null
            },
            max: {
                type: Number,
                required: false,
                default: null
            },
            emitOnBlur: {
                type: String,
                required: false,
                default: null
            }
        },
        data: () => {
            return {
                strings: {},
            }
        },
        mounted () {
            this.formatValue();
            this.$watch('decimal', function () {
                this.formatValue();
            });
            this.$watch('min', function () {
                this.formatValue();
            });
            this.$watch('max', function () {
                console.log('max:'+this.max)
                this.formatValue();
            });
        },
        methods: {
            updateValue: function (value) {
                let result = this.parse(value, this.value);
                if(result.isEmpty){
                    this.$refs.input.value = null;
                } else if (!result.isNumber) {
                    this.$refs.input.value = result.number;
                } else if (result.isUnderMin) {
                    this.$refs.input.value = this.min;
                    this.$emit('input', this.min);
                } else if (result.isOverMax) {
                    this.$refs.input.value = this.max;
                    this.$emit('input', this.max);
                } else {
                    this.$emit('input', result.number);
                }
            },
            formatValue: function () {
                let result = this.parse(this.value, this.value);
                if (result.isUnderMin || result.isEmpty) {
                    this.$emit('input', this.min);
                } else if (result.isOverMax) {
                    this.$emit('input', this.max);
                } else if (!result.isNumber) {
                    this.$refs.input.value = this.min ? this.min : 0;
                } else {
                    this.$refs.input.value = this.fixed(this.value)
                }
                if(this.emitOnBlur!==null){
                    this.$parent.$emit(this.emitOnBlur, null);
                }
            },
            fixed: function (number) {
                let cents = (number * Math.pow(10,this.decimal)).toFixed(0);
                return (cents/ Math.pow(10,this.decimal)).toFixed(this.decimal);
            },
            parse: function (number, oldNumber) {
                let result= {isNumber: true, number: 0, isUnderMin: false, isOverMax: false, isEmpty: false};
                let newNumber =  Number(number);
                if(number==''){
                    result.isEmpty = true;
                } else if(isNaN(newNumber)){
                    result.isNumber = false;
                    result.number = oldNumber;
                } else {
                    if(this.min!==null && number < this.min){
                        result.isUnderMin = true;
                    } else if(this.max!==null && number > this.max){
                        result.isOverMax = true;
                    }
                    result.number = newNumber;
                }
                return result;
            }
        }
    }
</script>
