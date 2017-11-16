<template>
    <div :id="'manage-btn-1-'+_uid" class="ui labeled icon top right pointing dropdown blue button">
        <i class="wrench icon"></i>
        <span class="text">{{ strings.manageAdvertLabel }}</span>
        <div class="menu">
            <div class="item" v-if="withSeeAction" v-on:click="seeMe()" :data-content="strings.seeAdvertPopupLabel"><i class="unhide blue icon"></i>{{ strings.seeAdvertLabel }}</div>
            <div class="item" v-if="withDelegationAction && advert.delegationUrl!==null" v-on:click="delegationMe()" :data-content="strings.delegationAdvertPopupLabel"><i class="pie chart blue icon"></i>{{ strings.delegationAdvertLabel }}</div>
            <div class="item" v-if="advert.editUrl!==null && advert.isEligibleForEdit" v-on:click="editMe()" :data-content="strings.editAdvertPopupLabel"><i class="write blue icon"></i>{{ strings.editAdvertLabel }}</div>
            <div class="item" v-if="advert.backToTopUrl!==null" v-on:click="backToTopMe()" :data-content="strings.backToTopPopupLabel"><i class="arrow up blue icon"></i>{{ strings.backToTopLabel }}</div>
            <div class="item" v-if="advert.highlightUrl!==null && advert.isEligibleForHighlight" v-on:click="highlightMe()" :data-content="strings.highlightPopupLabel"><i class="announcement blue icon"></i>{{ strings.highlightLabel }}</div>
            <div class="item" v-if="advert.renewUrl!==null && advert.isEligibleForRenew" v-on:click="renewMe()" :data-content="strings.renewAdvertPopupLabel"><i class="power blue icon"></i>{{ strings.renewAdvertLabel }}</div>
            <div class="item" v-if="advert.destroyUrl!==null" v-on:click="destroyMe()" :data-content="strings.deleteAdvertPopupLabel"><i class="delete red icon"></i>{{ strings.deleteAdvertLabel }}</div>
        </div>
    </div>
</template>

<script>
  import { DestockTools } from '../../../destockTools'
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
            withDelegationAction: {
                type: Boolean,
                required: false,
                default: true
            }
        },
        computed: {
            strings () {
                return this.$store.state.strings['advert-manage-button']
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
                this.$emit('deleteAdvert', {'url': this.advert.destroyUrl});
            },
            renewMe: function () {
                DestockTools.goToUrl(this.advert.renewUrl);
            },
            backToTopMe: function () {
                DestockTools.goToUrl(this.advert.backToTopUrl);
            },
            highlightMe: function () {
                DestockTools.goToUrl(this.advert.highlightUrl);
            },
            seeMe: function () {
                DestockTools.goToUrl(this.advert.url);
            },
            delegationMe: function () {
                DestockTools.goToUrl(this.advert.delegationUrl);
            },
            editMe: function () {
                DestockTools.goToUrl(this.advert.editUrl);
            },
        }
    }
</script>
