<template>
    <div class="ui segment">
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <template v-if="usersList.length==0">
            <div class="item ads">
                <div class="ui info message">
                    <div class="header">{{ strings.noResultFoundHeader }}</div>
                    <p>{{ strings.noResultFoundMessage }}</p>
                </div>
            </div>
        </template>
        <template v-if="usersList.length>0">
            <table class="ui small compact celled table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ strings.listHeaderRegisterDate }}</th>
                        <th>{{ strings.listHeaderName }} / {{ strings.listHeaderUsermail }}</th>
                        <th>{{ strings.listHeaderCompagny }} / {{ strings.listHeaderVatNumber }}</th>
                        <th>{{ strings.listHeaderAddress }}</th>
                        <th>{{ strings.roleUserLabel }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(user, index) in usersList">
                        <users-by-list-item
                                :user="user"
                                @changeRole="patchUserRole($event.url, $event.role)"
                                @deleteUser="$emit('deleteUser', $event)"
                        ></users-by-list-item>
                    </template>
                </tbody>

            </table>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            routeGetUsersList: String,
            flagForceReload: {
                type: Boolean,
                default: false,
                required: false
            }
        },
        computed: {
            strings () {
                return this.$store.state.strings['users-by-list']
            },
            properties () {
                return this.$store.state.properties['global']
            }
        },
        watch: {
            routeGetUsersList () {
                this.getUsersList()
            },
            flagForceReload () {
                this.getUsersList()
            }
        },
        data () {
            return {
                usersList: [],
                isLoaded: false,
            };
        },
        methods: {
            getUsersList: function (withLoadIndicator) {
                withLoadIndicator == undefined ? withLoadIndicator = true : null;
                withLoadIndicator ? this.isLoaded = false : this.isLoaded = true;
                let that = this;
                this.usersList = [];
                axios.get(this.routeGetUsersList)
                    .then(function (response) {
                        that.usersList = (response.data).users.data;
                        that.isLoaded = true;
                        let paginate = response.data.users;
                        delete paginate.data;
                        that.$emit('paginate', paginate);
                    })
                    .catch(function (error) {
                        that.$emit('loadError')
                    });
            },
            patchUserRole: function (url, role) {
                this.isLoaded = false;
                let that = this;
                axios.patch(url, {'role': role})
                    .then(function (response) {
                        that.getUsersList();
                    })
                    .catch(function (error) {
                        if (error.response && error.response.status == 409) {
                            let msg = error.response.data;
                            that.$emit('sendToast', {'message': msg, 'type':'error'});
                        } else {
                            that.$emit('loadError');
                        }
                        that.getUsersList();
                    });
            }
        }
    }
</script>
