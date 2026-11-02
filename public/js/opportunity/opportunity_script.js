$("#del").on("click", function() {
    var dataArr = new Array();
    if($('input:checkbox:checked').length > 0){
        $('input:checkbox:checked').each(function(){ 
            dataArr.push($(this).attr('id'));
            $.ajax({
                type    :'get',
                url     :"/user/opportunity/delete/" + $(this).attr("id"),
                success : function(response){
                        // alert(response);
                        window.location.href =route_opportunity_oppindex;
                        },
                error   :function(response){
                        alert(errResponse);
                }
    });
        });

    }
    else{
            alert('No Record Selected');
         }
});

$("#edit").on("click", function() {
    var dataArr = new Array();
    var group = new Array();
    var d;
    var all;
    if($('input:checkbox:checked').length > 0){
        $('input:checkbox:checked').each(function(){ 
            dataArr.push($(this).attr('id'));
           all = ""+dataArr;
            group.push(all); 
            
            
    });
    $.ajax({
                type    :'get',
                url     : "/user/opportunity/editreq/" + all,
                success : function(response){
                         window.location.href ="/user/opportunity/editreq/" + all;
                         },
                error   :function(response){
                        alert(errResponse);
                }
        });

    }
    else{
            alert('No Record Selected');
         }
});

