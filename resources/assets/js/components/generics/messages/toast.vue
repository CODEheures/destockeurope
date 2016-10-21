<template>
    <div>
        <transition name="appear-right">
            <div id="toast" class="ui icon message" :class="type" v-if="visible">
                <i class="info icon"></i>
                <div class="content">
                    <p>{{ message }}</p>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        props: [
            'message',
            'type',
            'sendMessage'
        ],
        data: () => {
            return {
                visible: false,
                timeOut: 4000
            }
        },
        mounted () {
            this.$watch('sendMessage', function () {
                this.setVisible();
            })
        },
        methods: {
            setVisible: function () {
                this.visible = true;
                var that = this;
                setTimeout(function () {
                    that.visible = false;
                },that.timeOut);
            }
        },
    }
</script>
