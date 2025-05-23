$.fn.extend({
    treed: function (o) {
      
      var openedClass = 'ik ik-plus';
      var closedClass = 'ik ik-minus';
      
      if (typeof o != 'undefined'){
        if (typeof o.openedClass != 'undefined'){
        openedClass = o.openedClass;
        }
        if (typeof o.closedClass != 'undefined'){
        closedClass = o.closedClass;
        }
      };
      
        /* initialize each of the top levels */
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this);
            branch.prepend("");
            branch.addClass('branch');   
            branch.addClass(openedClass);
            branch.on('click', function (e) {
                if (this == e.target) {
                    branch.toggleClass(closedClass + " " + openedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });

        /* fire event from the dynamically added icon */
        // tree.find('.branch .indicator').each(function(){
        //     $(this).on('click', function () {
        //         $(this).closest('li').click();
        //     });
        // });
        /* fire event to open branch if the li contains an anchor instead of text */
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        // /* fire event to open branch if the li contains a button instead of text */
        // tree.find('.branch>button').each(function () {
        //     $(this).on('click', function (e) {
        //         $(this).closest('li').click();
        //         e.preventDefault();
        //     });
        // });

    }
});
/* Initialization of treeviews */
$('#coaTree').treed();
$('#mcoaTree2').treed();