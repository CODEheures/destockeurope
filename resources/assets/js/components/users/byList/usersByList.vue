<template>
    <div class="ui segment">
        <div class="ui active inverted dimmer" v-if="!isLoaded">
            <div class="ui large text loader">Loading</div>
        </div>
        <template v-if="usersList.length==0">
            <div class="item ads">
                <div class="ui info message">
                    <div class="header">{{ noResultFoundHeader }}</div>
                    <p>{{ noResultFoundMessage }}</p>
                </div>
            </div>
        </template>
        <template v-if="usersList.length>0">
            <table class="ui small compact celled table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ listHeaderRegisterDate }}</th>
                        <th>{{ listHeaderName }} / {{ listHeaderUsermail }}</th>
                        <th>{{ listHeaderCompagny }} / {{ listHeaderVatNumber }}</th>
                        <th>{{ listHeaderAddress }}</th>
                        <th>{{ roleUserLabel }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(user, index) in usersList">
                        <users-by-list-item
                                :user="user"
                                :actual-locale="actualLocale"
                                :role-user-label="roleUserLabel"
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
            },
            actualLocale: String,
            roleUserLabel: String,
            noResultFoundHeader: String,
            noResultFoundMessage: String,
            listHeaderUsermail: String,
            listHeaderName: String,
            listHeaderCompagny: String,
            listHeaderRegisterDate: String,
            listHeaderVatNumber: String,
            listVatVerificationNumberLabel: String,
            listHeaderAddress: String
        },
        data: () => {
            return {
                usersList: [],
                isLoaded: false,
            };
        },
        mounted () {
            let that = this;
            this.$watch('routeGetUsersList', function () {
                this.getUsersList();
            });
            this.$watch('flagForceReload', function () {
                this.getUsersList();
            });
            this.$on('sendToast', function (message) {
                that.$parent.$emit('sendToast', message);
            });
            this.$on('loadError', function () {
                that.$parent.$emit('loadError');
            });
            this.$on('changeRole', function (event) {
                that.patchUserRole(event.url, event.role);
            });
            this.$on('deleteUser', function (event) {
                that.$parent.$emit('deleteUser', event);
            })
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
                        that.$parent.$emit('paginate', paginate);
                    })
                    .catch(function (error) {
                        that.$parent.$emit('loadError')
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
                            that.$parent.$emit('sendToast', {'message': msg, 'type':'error'});
                        } else {
                            that.$parent.$emit('loadError');
                        }
                        that.getUsersList();
                    });
            }
        }
    }
</script>
