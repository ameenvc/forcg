<?php
namespace App;
use Config;
use DB;
class CofingureMailSettings{
    public function __construct($mails){
        $this->mails=$mails;
        $this->updateSettings($this->mails);
    }
    private function updateSettings($mails){
        $mail=DB::table('mail')->first();
        if($mail){
        DB::table('mail')
        ->where('id',$mail->id)
        ->update(['driver'=>$mails['driver'],'host'=>$mails['host'],'port'=>$mails['port'],'faddress'=>$mails['faddress'],'fname'=>$mails['fname'],'encryption'=>$mails['encryption'],'username'=>$mails['username'],'password'=>$mails['password']]);
        }
        else{
            DB::table('mail')
            ->insert(['driver'=>$mails['driver'],'host'=>$mails['host'],'port'=>$mails['port'],'faddress'=>$mails['faddress'],'fname'=>$mails['fname'],'encryption'=>$mails['encryption'],'username'=>$mails['username'],'password'=>$mails['password']]);      
        }

    }
}

?>
