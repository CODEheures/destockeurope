<template>
    <div class="ui steps">
        <div :class="(step.isActive ? 'active ': '') + (step.isDisabled ? 'disabled ' : '') + (step.isCompleted ? 'completed ' : '') + 'step'" v-for="step in steps">
            <i :class="step.icon + ' icon'"></i>
            <div class="content">
                <div class="title">{{ step.title }}</div>
                <template v-if="step.routeDescription != undefined">
                    <a :href="step.routeDescription" target="_blank" style="pointer-events: initial">{{ step.description }}</a>
                </template>
                <template v-else>
                    <div class="description">{{ step.description }}</div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
  /**
   * Props
   *  - steps: Array. The array of step objects: [{description:"describe", icon:"write", isActive:true, isCompleted:false, isDisabled:false, title:"Création de l'annonce"}, {..}, ...]
   *
   * Events:
   *
   */
  export default {
    props: {
      steps: {
        type: Array
      }
    },
    mounted () {
      $('.ui.steps').visibility({
        type: 'fixed',
        offset: 70
      })
    }
  }
</script>
