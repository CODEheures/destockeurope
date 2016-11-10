<template>
    <div class="ui steps">
        <div :class="(step.isActive ? 'active ': '') + (step.isCompleted ? 'completed ': '') + (step.isDisabled ? 'disabled ' : '') + (step.isCompleted ? 'completed ' : '') + 'step'" v-for="step in steps">
            <i :class="step.icon + ' icon'"></i>
            <div class="content">
                <div class="title">{{ step.title }}</div>
                <div class="description">{{ step.description }}</div>
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

        },
        methods: {

        }
    }
</script>
