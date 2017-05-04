<template>
    <tr>
        <td>
            # {{ user.id }}
        </td>
        <td>
            {{ getMoment(user.created_at) }}
        </td>
        <td>
            {{ user.email }}
        </td>
        <td>
            {{ user.name }}
        </td>
        <td>
            {{ user.compagnyName }}
        </td>
        <td>
            {{ user.registrationNumber }}
        </td>
        <td>
            <div :id="'dropdown-'+_uid" class="ui selection dropdown">
                <input :id="'role-'+_uid" type="hidden" :name="'role-'+_uid" :value="user.role">
                <i class="dropdown icon"></i>
                <div class="default text">{{ roleUserLabel }}</div>
                <div class="menu">
                    <template v-for="(role, index) in user.rolesList">
                        <div class="item" :data-value="role">{{ role }}</div>
                    </template>
                </div>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        props: {
            user: Object,
            roleUserLabel: String,
            actualLocale: String
        },
        data: () => {
            return {

            };
        },
        mounted () {
            let that = this;
            $('#dropdown-'+this._uid).dropdown({
                onChange: function(value, text, $selectedItem) {
                    that.$parent.$emit('changeRole', {'url': that.user.urlSetRole, 'role': value});
                }
            });
        },
        methods: {
            getMoment: function (dateTime) {
                moment.locale(this.actualLocale);
                return moment(dateTime).format('L');
            }
        }
    }
</script>
