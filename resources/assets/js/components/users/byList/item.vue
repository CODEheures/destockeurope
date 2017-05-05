<template>
    <tr>
        <td>
            # {{ user.id }}
        </td>
        <td>
            {{ getMoment(user.created_at) }}
        </td>
        <td>
            {{ user.name }}
            <br />{{ user.email }}
        </td>
        <td>
            {{ user.compagnyName }}
            <br />{{ user.registrationNumber }}
        </td>
        <td>
            {{ getFormattedAddress() }}
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
            },
            getFormattedAddress() {
                let geoloc = JSON.parse(this.user.geoloc);
                if(geoloc != undefined && geoloc != null && Array.isArray(geoloc) && 'formatted_address' in geoloc[0]){
                    return geoloc[0].formatted_address
                } else {
                    return '';
                }
            }
        }
    }
</script>
