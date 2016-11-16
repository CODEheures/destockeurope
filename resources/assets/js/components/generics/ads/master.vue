<template>
    <div v-if="dataIsActive">
        <img class="ui fluid image masterads" :src="datasrc" :style="'width:'+datawidth+'px; left:calc(50% - '+datawidth/2+'px);'">
    </div>
</template>

<script>
    export default {
        props: [
            //vue vars
            'isActive',
            'url',
            'width'
        ],
        data: () => {
            return {
                dataIsActive: false,
                datasrc: '/images/background.jpg',
                datawidth: 1400
            }
        },
        mounted () {
            this.dataIsActive = this.isActive == '1';
            this.setDatasrc();
            this.setDataWidth();
        },
        methods: {
            setDatasrc() {
                if(this.url != ''){
                    this.datasrc = this.url;
                }
            },
            setDataWidth() {
                if(this.url != '' && this.width != undefined){
                    this.datawidth = this.width;
                }
            }
        }
    }
</script>
