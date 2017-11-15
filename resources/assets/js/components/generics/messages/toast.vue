<template>
    <div>
        <transition name="appear-right">
            <div id="toast" class="ui icon message" :class="type" v-if="visible">
                <i class="info icon"></i>
                <i class="close icon" @click.prevent.stop="visible = false"></i>
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
        watch: {
            sendMessage () {
                this.visible = false;
                this.setVisible();
            }
        },
        data () {
            return {
                visible: false
            }
        },
        methods: {
            setVisible () {
                this.visible = true;
            }
        },
    }
</script>
