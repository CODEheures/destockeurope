<template>
    <div :id="'subscribe-'+_uid" class="ui toggle checkbox" v-show="destockShareVarData.firebase.token!=null && destockShareVarData.firebase.token!=undefined && destockShareVarData.firebase.token!=''">
        <input type="checkbox" name="public">
        <label>{{ strings.checkboxLabel }}</label>
    </div>
</template>

<script>
    export default {
        props: {
            //vue routes
            routeExistIn: String,
            routeAdd: String,
            routeRemove: String,
            //Vue vars
            topic_id: Number
        },
        data: () => {
            return {
                strings: {},
                destockShareVarData: destockShareVar,
                existIn: false,
            }
        },
        mounted () {
            this.strings = this.$store.state.strings['notifications-activer'];
            //App Notifications
            let that = this;
            let subscribeCheckbox = $('#subscribe-'+this._uid);

            if(this.destockShareVarData.firebase.token!=undefined){
                this.existInToken(this.destockShareVarData.firebase.token);
            }

            this.$watch('destockShareVarData.firebase.token', function (token) {
                that.existInToken(token);
            });

            this.$watch('existIn', function (value) {
                if(value==true) {
                    subscribeCheckbox.checkbox('set checked');
                } else {
                    subscribeCheckbox.checkbox('set unchecked');
                }
            });

            subscribeCheckbox.checkbox({
                onChecked: function() {
                    axios.post(that.routeAdd, {'token': that.destockShareVarData.firebase.token,'topic_id': that.topic_id})
                        .then(function (response) {
                            //console.log('subscribe success', response)
                        })
                        .catch(function (error) {
                            //console.log('subscribe error', error)
                        });
                },
                onUnchecked: function() {
                    axios.delete(that.routeRemove, {data: {'token': that.destockShareVarData.firebase.token,'topic_id': that.topic_id}})
                        .then(function (response) {
                            //console.log('unsubscribe success', response)
                        })
                        .catch(function (error) {
                            //console.log('unsubscribe error', error)
                        });
                }
            });
        },
        methods: {
            existInToken: function (token) {
                let that = this;
                axios.post(that.routeExistIn, {'token': token,'topic_id': that.topic_id})
                    .then(function (response) {
                        that.existIn = response.data.existIn == true;
                    })
                    .catch(function (error) {
                        that.existIn = false;
                    });
            }
        }
    }
</script>
