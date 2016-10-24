<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="ui basic modal">
            <i class="close icon"></i>
            <div class="header">
                {{ modalValidHeader }}
            </div>
            <div class="image content">
                <div class="image">
                    <i class="legal icon"></i>
                </div>
                <div class="description">
                    <p>{{ modalValidDescription }}</p>
                </div>
            </div>
            <div class="actions">
                <div class="two fluid ui inverted buttons">
                    <div class="ui cancel red basic inverted button">
                        <i class="remove icon"></i>
                        {{ modalNo }}
                    </div>
                    <div class="ui ok green basic inverted button">
                        <i class="checkmark icon"></i>
                        {{ modalYes }}
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <h2 class="ui header">{{ contentHeader }}</h2>
        </div>
        <div class="column">
            <div class="ui divided list">
                <div class="ui active inverted dimmer" v-if="!isLoaded">
                    <div class="ui large text loader">Loading</div>
                </div>
                <form>
                    <div v-for="advert in advertsList" class="ui segment">
                        <table class="ui definition table">
                            <tbody>
                            <tr>
                                <td class="two wide column">{{ advertTitleLabel }}</td>
                                <td>{{ advert.title }}</td>
                            </tr>
                            <tr>
                                <td class="two wide column">{{ advertDescriptionLabel }}</td>
                                <td><p style="white-space: pre-wrap;">{{ advert.description }}</p></td>
                            </tr>
                            <tr>
                                <td class="two wide column">{{ advertPriceLabel }}</td>
                                <td>{{ advert.price }} {{ advert.currency}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="ui grid">
                            <div class="right floated sixteen wide mobile eight wide tablet five wide computer column">
                                <div class="ui form">
                                    <div class="grouped fields">
                                        <div class="field">
                                            <div class="ui slider checkbox">
                                                <input type="radio" :name="advert.id" value="1">
                                                <label>{{ toggleApproveLabel }}</label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="ui slider checkbox">
                                                <input type="radio" :name="advert.id" value="0">
                                                <label>{{ toggleDisapproveLabel }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="ui primary button" :class="action ? '': 'disabled'" v-on:click="approveAll">{{
                        formValidationButtonLabel }}
                    </button>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'contentHeader',
            'routeGetAdvertsList',
            'routeAdvertApprove',
            'loadErrorMessage',
            'toggleApproveLabel',
            'toggleDisapproveLabel',
            'formValidationButtonLabel',
            'modalValidHeader',
            'modalValidDescription',
            'modalNo',
            'modalYes',
            'advertTitleLabel',
            'advertDescriptionLabel',
            'advertPriceLabel',
            'advertApproveSuccess'
        ],
        data: () => {
            return {
                advertsList: [],
                isLoaded: false,
                action: false,
                approveList: [],
                sendMessage: false,
                typeMessage: '',
                message: ''
            };
        },
        mounted () {
            this.getAdvertsList();
        },
        updated () {
            var that = this;
            $('.ui.slider').each(function () {
                $(this).checkbox({
                    onChange: function () {
                        that.action = true;
                        that.approveList[this.name] = this.value;
                    }
                })
            });
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
                                    this.sendToast(this.loadErrorMessage, 'error');
                                }
                        );
            },
            approveAll: function (event) {
                event.preventDefault();
                var that = this;
                $('.ui.basic.modal').modal({
                    closable: false,
                    onApprove: function () {
                        that.isLoaded = false;
                        that.$http.post(that.routeAdvertApprove, that.approveList)
                                .then(
                                        (response) => {
                                            that.getAdvertsList();
                                            that.sendToast(that.advertApproveSuccess, 'success');
                                        },
                                        (response) => {
                                            if (response.status == 409) {
                                                that.sendToast(response.body, 'error');
                                            }
                                            that.isLoaded = false;
                                            that.sendToast(that.loadErrorMessage, 'error');
                                        }
                                );
                    }
                }).modal('show');
            },
            sendToast: function(message,type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            }
        }
    }
</script>