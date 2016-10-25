<template>
    <div>
        <div class="ui celled list">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <a v-for="advert in advertsList" class="item advert">
                <div class="ui image">
                    <div class="ui right blue corner label"><i class="icon">3</i></div>
                    <img  class="ui top aligned small bordered rounded image" src="/images/jenny.jpg">
                </div>

                <div class="content">

                    <div class="header">{{ advert.title }}</div>
                    <p class="ui teal tag label">{{ advert.price }} {{ advert.currency }}</p>
                    <a class="ui primary button">{{ seeAdvertLinkLabel }}</a>
                </div>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'routeGetAdvertsList',
            'advertTitleLabel',
            'advertDescriptionLabel',
            'advertPriceLabel',
            'seeAdvertLinkLabel'
        ],
        data: () => {
            return {
                advertsList: [],
                isLoaded: false
            };
        },
        mounted () {
            this.getAdvertsList();
        },
        methods: {
            getAdvertsList: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                this.$http.get(this.routeGetAdvertsList)
                        .then(
                                (response) => {
                                    this.advertsList = response.data;
                                    this.isLoaded = true;
                                },
                                (response) => {
                                    this.$parent.$emit('loadError')
                                }
                        );
            }
        }
    }
</script>
