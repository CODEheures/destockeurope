;(function () {
    $(document)
        .ready(function() {

            // fix menu when passed
            $('.masthead .ui.secondary.menu')
                .visibility({
                    once: false,
                    onTopPassed: function() {
                        $('.fixed.menu').transition('fade in');
                    },
                    onTopPassedReverse: function() {
                        $('.fixed.menu').transition('fade out');
                    }
                })
            ;

            // create sidebar and attach to menu open
            $('.ui.sidebar')
                .sidebar('attach events', '.toc.item')
            ;

            //menu user dropdowns
            $('.logs .ui.dropdown:not(.langChoice)')
                .dropdown()
            ;

            //Login form toogle
            $('.login .ui.toggle.checkbox')
                .checkbox()
            ;

            //fermeture des messages
            $('.message .close')
                .on('click', function() {
                    $(this)
                        .closest('.message')
                        .transition('fade')
                    ;
                })
            ;
        })
    ;
})();

