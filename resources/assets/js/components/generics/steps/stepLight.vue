<template>
    <div class="ui segment" :id="'mini-steps'+_uid">
        <div class="ui mini breadcrumb type-steps">
            <div :class="(step.isActive ? 'active ': '') + (step.isDisabled ? 'disabled ' : '') + (step.isCompleted ? 'completed ' : '') + 'section'" v-for="(step,index) in steps">
                <i :class="step.isCompleted ? 'checkmark green icon' : step.icon + ' icon'"></i>{{ step.title }}<a v-if="step.routeDescription != undefined" :href="step.routeDescription">{{step.description}}</a><i class="right arrow icon divider" v-if="index != (steps.length-1)"></i>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props: {
            steps: {
                type: Array
            }
        },
        data: () => {
            return {

            }
        },
        mounted () {
            $('#mini-steps'+this._uid).visibility({
                type   : 'fixed',
                offset : 70
            });
        },
        methods: {

        }
    }
</script>
