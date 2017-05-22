<template>
    <div :id="'manage-btn-1-'+_uid" class="ui labeled icon top right pointing dropdown blue button">
        <i class="wrench icon"></i>
        <span class="text">{{ manageAdvertLabel }}</span>
        <div class="menu">
            <div class="item" v-if="withSeeAction" v-on:click="seeMe()" :data-content="seeAdvertPopupLabel"><i class="unhide blue icon"></i>{{ seeAdvertLabel }}</div>
            <div class="item" v-if="advert.editUrl!==null" v-on:click="editMe()" :data-content="editAdvertPopupLabel"><i class="write blue icon"></i>{{ editAdvertLabel }}</div>
            <div class="item" v-if="advert.backToTopUrl!==null" v-on:click="backToTopMe()" :data-content="backToTopPopupLabel"><i class="arrow up blue icon"></i>{{ backToTopLabel }}</div>
            <div class="item" v-if="advert.highlightUrl!==null && advert.isEligibleForHighlight" v-on:click="highlightMe()" :data-content="highlightPopupLabel"><i class="announcement blue icon"></i>{{ highlightLabel }}</div>
            <div class="item" v-if="advert.renewUrl!==null && advert.isEligibleForRenew" v-on:click="renewMe()" :data-content="renewAdvertPopupLabel"><i class="power blue icon"></i>{{ renewAdvertLabel }}</div>
            <div class="item" v-if="advert.destroyUrl!==null" v-on:click="destroyMe()" :data-content="deleteAdvertPopupLabel"><i class="delete red icon"></i>{{ deleteAdvertLabel }}</div>
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
            editAdvertLabel: {
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
            },
            seeAdvertPopupLabel: {
                type: String
            },
            editAdvertPopupLabel: {
                type: String
            },
            deleteAdvertPopupLabel: {
                type: String
            },
            backToTopPopupLabel: {
                type: String
            },
            highlightPopupLabel: {
                type: String
            },
            renewAdvertPopupLabel: {
                type: String
            }
        },
        data: () => {
            return {

            }
        },
        mounted () {
            this.constructMe();
        },
        updated () {
            this.constructMe();
        },
        methods: {
            constructMe: function () {
                let manageBtn = $('#manage-btn-1-'+this._uid);
                manageBtn.dropdown({action: 'select'});
                manageBtn.find('div.menu div.item').each(function () {
                    $(this).popup({
                        position: 'right center'
                    });
                });
            },
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
            editMe: function () {
                window.location.assign(this.advert.editUrl);
            },
        }
    }
</script>
