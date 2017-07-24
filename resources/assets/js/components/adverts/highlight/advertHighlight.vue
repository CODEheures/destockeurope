<template>
    <div class="ui centered grid">
        <div class="fourteen wide column">
            <a :href="advert.url" class="ui card" :id="'highlight-card-'+_uid">
                <div class="image">
                    <img class="ui bordered rounded image" :src="advert.thumb">
                </div>
                <div class="extra">
                    <div class="left floated author">
                        {{ advert.title }}
                    </div>
                    <div class="right floated author">
                        <span :title="strings.totalQuantityLabel"><i class="cubes icon"></i>{{ advert.totalQuantity }} </span>
                        <span :class="advert.isNegociated ? 'ui tiny blue right floated left pointing label price negociated' : 'ui tiny yellow right floated left pointing label price'">{{ advert.isNegociated ? strings.isNegociatedLabel + '(' + advert.currencySymbol + ')' : advert.price_margin }}</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            advert: Object,
        },
        data: () => {
            return {
                strings: {},
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['advert-highlight'];
        },
        methods: {
        }
    }
</script>
