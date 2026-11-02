function ddldd(){
    if($("#opp_file").val()=='')
    {   mess2='Are you sure to submit without attachment?';
        y=confirm(mess2);
        if(y==true)
        { }
        else
        {return false;}
    }
    else{
        mess1= "Are you sure?";
        x= confirm(mess1);
        if(x==true)
        { }
        else
        {return false;}
    }
}

$("#payment_method").on("change", function() {
                       console.log($("#payment_method option:selected").val());
                       if ($("#payment_method option:selected").val() == 2) {
                        $('#shipping_number').hide();
                       } else {
                         $('#shipping_number').show();
                       }
                     });

$("#hod_method").on("change", function() {
                      console.log($("#hod_method option:selected").val());
                      if ($("#hod_method option:selected").val() == 4) {
                        $('#action_number').hide();
                      } else {
                        $('#action_number').show();
                      }
                    });
                 
