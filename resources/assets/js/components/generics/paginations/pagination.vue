<template>
    <div>
        <div class="ui mini pagination menu" v-if="datapages.length>0">
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
            'routeGetAdvertList',
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
                let parsed = Parser.parse(this.routeGetAdvertList, true);
                parsed.search=undefined;
                parsed.query.page=pageNumber.toString();
                return Parser.format(parsed);
            },
            constructPages() {
                this.datapages = [];
                if(this.pages.last_page > 1) {


                    if(this.pages.last_page > 1 && this.pages.last_page <= 6 ){
                        //previous if current page > 1
                        if(this.pages.current_page > 1){
                            this.datapages.push({
                                label: '<i class="chevron left icon" style="pointer-events: none"></i>',
                                href : this.urlForPageNumber(this.pages.current_page-1),
                                title: this.pagePreviousLabel
                            });
                        }
                        for(let i=1 ; i<= this.pages.last_page; i++){
                            this.datapages.push({
                                label: i.toString(),
                                href : this.urlForPageNumber(i),
                                isDisabled: i==this.pages.current_page,
                                title: this.pageLabel + ' ' + (i)
                            });
                        }
                        //next if current page < last page
                        if(this.pages.current_page < this.pages.last_page){
                            this.datapages.push({
                                label: '<i class="chevron right icon" style="pointer-events: none"></i>',
                                href : this.urlForPageNumber(this.pages.current_page+1),
                                title: this.pageNextLabel
                            });
                        }
                    } else {
                        if(this.pages.current_page<=3){
                            for(let i=1 ; i<=this.pages.current_page+2; i++){
                                this.datapages.push({
                                    label: i.toString(),
                                    href : this.urlForPageNumber(i),
                                    isDisabled: i==this.pages.current_page,
                                    title: this.pageLabel + ' ' + (i)
                                });
                            }
                            this.datapages.push({
                                label: '...',
                                href : null,
                                isDisabled: true,
                                title: ''
                            });
                            this.datapages.push({
                                label: this.pages.last_page,
                                href : this.urlForPageNumber(this.pages.last_page),
                                title: this.pageNextLabel
                            });
                        }
                        if(this.pages.current_page>3){
                            //first if current page > 1
                            if(this.pages.current_page > 1){
                                this.datapages.push({
                                    label: '1',
                                    href : this.urlForPageNumber(1),
                                    title: this.pagePreviousLabel
                                });
                            }
                            this.datapages.push({
                                label: '...',
                                href : null,
                                isDisabled: true,
                                title: ''
                            });
                            if(this.pages.current_page+3<this.pages.last_page){
                                for(let i=this.pages.current_page-1 ; i<= this.pages.current_page+1; i++){
                                    this.datapages.push({
                                        label: i.toString(),
                                        href : this.urlForPageNumber(i),
                                        isDisabled: i==this.pages.current_page,
                                        title: this.pageLabel + ' ' + (i)
                                    });
                                }
                                this.datapages.push({
                                    label: '...',
                                    href : null,
                                    isDisabled: true,
                                    title: ''
                                });
                                //last if current page < last page
                                if(this.pages.current_page < this.pages.last_page){
                                    this.datapages.push({
                                        label: this.pages.last_page,
                                        href : this.urlForPageNumber(this.pages.last_page),
                                        title: this.pageNextLabel
                                    });
                                }
                            } else {
                                for(let i=this.pages.current_page-2 ; i<= this.pages.last_page; i++){
                                    this.datapages.push({
                                        label: i.toString(),
                                        href : this.urlForPageNumber(i),
                                        isDisabled: i==this.pages.current_page,
                                        title: this.pageLabel + ' ' + (i)
                                    });
                                }
                            }
                        }
                    }
                }
            },
            changePage (event) {
                event.preventDefault();
                this.$parent.$emit('changePage', event.target.href);
            }
        }
    }
</script>
