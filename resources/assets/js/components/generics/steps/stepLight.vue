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
      $('#mini-steps' + this._uid).visibility({
        type: 'fixed',
        offset: 70
      })
    }
  }
</script>
