function applyFilter() {
    $("#usertable > tr").hide();
    var x = 0;
    $(".userlist-filter").each(function () {
        var e = $(this);
        if (e.is(":checked")) {
            $("#usertable > tr.user-" + e.val()).show();
            x++;
        }
    });
    if (x === 0)
        $("#usertable > tr").show();
}

function changeUserStatus(action, data) {
//    if (action === "groupid") {
//sendRequest({action:"changeStatus","groupid":data,},"admin","users",function(res){
//    
//});
//    } else if (action === "delete") {
//
//    } else if (action == "activate") {
//        
//    }
}