/**
 * The Common Javascript for Admin
 */

//Current Route, it will be set value on the layout for every page.
var cur_route = null;
/**
 * Check the menu, setting the current page
 */
function checkMenu(){
    //cur_route
    $(".menu li").each(function(){
        var url = $(this).find("a").attr("href");
        if(cur_route === url){
            $(".menu li").removeClass("on");
            $(this).addClass("on");
        }
    });
}


//Run JS when the document ready
$(function(){
    checkMenu();
});