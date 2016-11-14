<template>
    <div>
        <div class="ui pagination menu" v-if="datapages.length>0">
            <template v-for="datapage in datapages" >
                <a :class="datapage.isDisabled ? 'disabled item' : 'active item'" :href="datapage.href"
                    v-on:click="changePage"
                    v-html="datapage.label"
                    :title="datapage.title">
                </a>
            </template>
        </div>
        <div v-if="datapages.length==0">
            <p>{{ pageLabel }} 1/1</p>
        </div>
    </div>

</template>

<script>
    export default {
        props: [
            //vue routes
            //vue vars
            'pages',
            //vue strings
            'pageLabel',
            'pagePreviousLabel',
            'pageNextLabel'
        ],
        data: () => {
            return {
                datapages: [],
                url: ''
            }
        },
        mounted () {
            this.$watch('pages', function () {
                this.constructPages();
            })
        },
        methods: {
            urlForPageNumber(pageNumber) {
                let urlBase;
                if(this.pages.next_page_url){
                    urlBase = this.pages.next_page_url;
                } else {
                    urlBase = this.pages.prev_page_url;
                }
                let parsed = Parser.parse(urlBase, true);
                parsed.search=undefined;
                parsed.query.page=pageNumber.toString();
                return Parser.format(parsed);
            },
            constructPages() {
                this.datapages = [];
                console.log(this.pages);
                if(this.pages.last_page > 1) {
                    //previous if current page > 1
                    if(this.pages.current_page > 1){
                        this.datapages.push({
                            label: '<i class="chevron left icon" style="pointer-events: none"></i>',
                            href : this.urlForPageNumber(this.pages.current_page-1),
                            title: this.pagePreviousLabel
                        });
                    }

                    if(this.pages.last_page > 1 && this.pages.last_page <= 6 ){
                        for(let i=1 ; i<= this.pages.last_page; i++){
                            this.datapages.push({
                                label: i.toString(),
                                href : this.urlForPageNumber(i),
                                isDisabled: i==this.pages.current_page,
                                title: this.pageLabel + ' ' + (i)
                            });
                        }
                    } else {
                        //TODO cas page > 6
                    }


                    //next if current page < last page
                    if(this.pages.current_page < this.pages.last_page){
                        this.datapages.push({
                            label: '<i class="chevron right icon" style="pointer-events: none"></i>',
                            href : this.urlForPageNumber(this.pages.current_page+1),
                            title: this.pageNextLabel
                        });
                    }
                }
            },
            changePage (event) {
                event.preventDefault();
                console.log(event.target);
                this.$parent.$emit('changePage', event.target.href);
            }
        }
    }
</script>
