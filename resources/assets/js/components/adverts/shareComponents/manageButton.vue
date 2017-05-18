<template>
    <div :id="'manage-btn-1-'+_uid" class="ui labeled icon top right pointing dropdown blue button">
        <i class="wrench icon"></i>
        <span class="text">{{ manageAdvertLabel }}</span>
        <div class="menu">
            <div class="item" v-if="withSeeAction" v-on:click="seeMe()">{{ seeAdvertLabel }}</div>
            <div class="item" v-on:click="destroyMe()">{{ deleteAdvertLabel }}</div>
            <div class="item" v-on:click="backToTopMe()">{{ backToTopLabel }}</div>
            <div class="item" v-if="advert.isEligibleForHighlight" v-on:click="highlightMe()">{{ highlightLabel }}</div>
            <div class="item" v-if="advert.isEligibleForRenew" v-on:click="renewMe()">{{ renewAdvertLabel }}</div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            advert: {
                type: Object
            },
            withSeeAction: {
                type: Boolean,
                required: false,
                default: true
            },
            manageAdvertLabel: {
                type: String
            },
            seeAdvertLabel: {
                type: String
            },
            deleteAdvertLabel: {
                type: String
            },
            backToTopLabel: {
                type: String
            },
            highlightLabel: {
                type: String
            },
            renewAdvertLabel: {
                type: String
            }
        },
        data: () => {
            return {

            }
        },
        mounted () {
            $('#manage-btn-1-'+this._uid).dropdown();
        },
        methods: {
            destroyMe: function () {
                this.$parent.$emit('deleteAdvert', {'url': this.advert.destroyUrl});
            },
            renewMe: function () {
                window.location.assign(this.advert.renewUrl);
            },
            backToTopMe: function () {
                window.location.assign(this.advert.backToTopUrl);
            },
            highlightMe: function () {
                window.location.assign(this.advert.highlightUrl);
            },
            seeMe: function () {
                window.location.assign(this.advert.url);
            },
        }
    }
</script>
