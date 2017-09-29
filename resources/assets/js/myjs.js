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

            //Add paceforcing for PACE JS forcing restart when window beforeunload
            //Improve loader for mobiles devices without browser indicators
            let paceForcingDiv = document.createElement('div');
            paceForcingDiv.id="paceforcing";
            document.body.appendChild(paceForcingDiv);

            $(window).on('click', function (event) {
                let $anchor = $(event.target).closest("a");
                if($anchor.length > 0 && $anchor.attr('href') !== undefined){
                    let parsedAnchor = Parser.parse($anchor.attr('href'), true);
                    parsedAnchor.search=undefined;
                    parsedAnchor.hash = '';

                    let actualHref = Parser.parse(window.location.href, true);
                    actualHref.search=undefined;
                    actualHref.hash = '';

                    if(Parser.format(parsedAnchor)!==Parser.format(actualHref)){
                        DestockTools.paceRestart();
                    }
                }
            })

        })
    ;
})();

