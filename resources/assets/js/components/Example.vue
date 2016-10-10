<template>
    <div>
        <div v-for="message in messages" class="item">
            <i class="large minus square icon"></i>
            <div class="content">
                <div class="ui transparent input">
                    <input type="text" :name="message" class="header"
                           :value="message"/>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        data: () => {
            return {messages: ['toto', 'tata']} ;
        },
//        mounted () {
//            this.$http.get('http://destockeurope.progress/metaCategory')
//                    .then(
//                            (response) => {
//                                console.log(response.data);
//                                this.props.items = response.data;
//                            },
//                            (response) => {
//                                console.log('erreur');
//                            }
//                    );
//        }
    }
</script>
