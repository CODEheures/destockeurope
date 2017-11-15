<template>
    <input ref="input" autocomplete="off" :id="_uid"
           v-bind:value="dataValue"
           v-on:input.stop="updateValue($event.target.value)"
           @keydown.enter.prevent.stop
           v-on:blur="formatValue(true)">
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
        data () {
            return {
                dataValue: 0
            }
        },
        mounted () {
            this.dataValue = this.value;
            this.formatValue();
            this.$watch('decimal', function () { this.updateAndFormat()  });
            this.$watch('min', function () { this.updateAndFormat() });
            this.$watch('max', function () { this.updateAndFormat() });
            this.$watch('value', function () {this.updateAndFormat() })
        },
        methods: {
            updateValue: function (value) {
                let result = this.parse(value, this.dataValue, false);
                if(result.isEmpty){
                    this.dataValue = value;
                } else if(result.isNotYetEvaluable){
                    this.dataValue = value;
                } else if (!result.isNumber) {
                    this.$refs.input.value = result.fixedNumber;
                    this.dataValue = result.fixedNumber;
                } else if (result.isUnderMin) {
                    this.$refs.input.value = this.fixed(this.min);
                    this.dataValue = this.fixed(this.min);
                    this.$emit('input', this.min);
                } else if (result.isOverMax) {
                    this.$refs.input.value = this.fixed(this.max);
                    this.dataValue = this.fixed(this.max);
                    this.$emit('input', this.max);
                } else {
                    this.dataValue = value;
                    this.$emit('input', result.number);
                }
            },
            formatValue: function (isOnBlur = false) {
                let result = this.parse(this.dataValue, this.dataValue, true);
                if (result.isUnderMin || result.isEmpty) {
                    this.dataValue = this.fixed(this.min);
                    this.$emit('input', this.min);
                } else if (result.isOverMax) {
                    this.dataValue = this.fixed(this.max);
                    this.$emit('input', this.max);
                } else if (!result.isNumber) {
                    this.dataValue = this.min ? this.fixed(this.min) : this.fixed(0);
                    this.dataValue = this.min ? this.fixed(this.min) : this.fixed(0);
                    this.$emit('input', this.min ? this.min : 0);
                } else {
                    this.dataValue = this.fixed(this.value);
                    this.$emit('input', Number(this.fixed(result.number)));
                }
                if(isOnBlur){
                    this.$emit('blur');
                }
            },
            fixed: function (number) {
                let cents = Number((number * Math.pow(10,this.decimal)).toFixed(0));
                return (cents/ Math.pow(10,this.decimal)).toFixed(this.decimal);
            },
            parse: function (number, oldNumber, isForFormat = false) {
                let result= {isNumber: true, number: 0, fixedNumber: this.fixed(0), isUnderMin: false, isOverMax: false, isEmpty: false, isNotYetEvaluable: false};
                let newNumber =  Number(number);
                if(number==='' || number ==='.' || number ==='0.'){
                    result.isEmpty = true;
                } else if(isNaN(newNumber)){
                    result.isNumber = false;
                    result.number = oldNumber;
                    result.fixedNumber = this.fixed(oldNumber);
                    if(isNaN(result.fixedNumber)){
                        result.fixedNumber = oldNumber;
                    }
                } else {
                    if(this.min!==null && newNumber < this.min){
                        if(this.min < 1 && !isForFormat && newNumber === 0 ) {
                            result.isNotYetEvaluable = true;
                        } else {
                            result.isUnderMin = true;
                        }
                    } else if(this.max!==null && newNumber > this.max){
                        result.isOverMax = true;
                    }
                    result.number = newNumber;
                    result.fixedNumber = this.fixed(number);
                }
                return result;
            },
            updateAndFormat () {
                if(document.getElementById(this._uid) !== document.activeElement){
                    this.dataValue = this.value;
                    this.formatValue();
                }
            }
        }
    }
</script>
