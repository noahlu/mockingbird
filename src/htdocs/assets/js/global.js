seajs.use(['jquery', 'prettify'],function($,Prettify){
    var oTracker = $('tracker');
    $(window).load(function(){
        $("pre").addClass("prettyprint linenums");
        prettyPrint();
    })
});
