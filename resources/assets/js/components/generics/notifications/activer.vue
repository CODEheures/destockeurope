<template>
    <div :id="'subscribe-'+_uid" class="ui toggle checkbox" v-show="destockShareVarData.firebase.token!=null && destockShareVarData.firebase.token!=undefined && destockShareVarData.firebase.token!=''">
        <input type="checkbox" name="public">
        <label>{{ strings.checkboxLabel }}</label>
    </div>
</template>

<script>
  import Axios from 'axios'
    export default {
        props: {
            //vue routes
            routeExistIn: String,
            routeAdd: String,
            routeRemove: String,
            //Vue vars
            topic_id: Number
        },
        computed: {
            strings () {
                return this.$store.state.strings['notifications-activer']
            }
        },
        watch: {
            'destockShareVarData.firebase.token' (token) {
                this.existInToken(token)
            },
            'existIn' (value) {
                if(value==true) {
                    subscribeCheckbox.checkbox('set checked');
                } else {
                    subscribeCheckbox.checkbox('set unchecked');
                }
            }
        },
        data () {
            return {
                destockShareVarData: destockShareVar,
                existIn: false,
            }
        },
        mounted () {
            //App Notifications
            let that = this;
            let subscribeCheckbox = $('#subscribe-'+this._uid);

            if(this.destockShareVarData.firebase.token!=undefined){
                this.existInToken(this.destockShareVarData.firebase.token);
            }

            subscribeCheckbox.checkbox({
                onChecked: function() {
                    Axios.post(that.routeAdd, {'token': that.destockShareVarData.firebase.token,'topic_id': that.topic_id})
                        .then(function (response) {
                            //console.log('subscribe success', response)
                        })
                        .catch(function (error) {
                            //console.log('subscribe error', error)
                        });
                },
                onUnchecked: function() {
                    Axios.delete(that.routeRemove, {data: {'token': that.destockShareVarData.firebase.token,'topic_id': that.topic_id}})
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
                Axios.post(that.routeExistIn, {'token': token,'topic_id': that.topic_id})
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
