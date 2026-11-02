<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConfigMailSettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //if (Schema::hasTable('mail')) {
            // $mail = DB::table('mail')->first();
            // if ($mail) //checking if table is not empty
            // {
            //     $config = array(
            //         'driver'     => $mail->driver,
            //         'host'       => $mail->host,
            //         'port'       => $mail->port,
            //         'from'       => array('address' => $mail->faddress, 'name' => $mail->fname),
            //         'encryption' => $mail->encryption,
            //         'username'   => $mail->username,
            //         'password'   => $mail->password,
            //         'sendmail'   => '/usr/sbin/sendmail -bs',
            //         'pretend'    => false,
            //     );
            //     Config::set('mail', $config);
            // } else {
            //     $from = Config::get('mail.from');
            //     $from_address = $from['address'];
            //     $from_name = $from['name'];
            //     $oldsettings = array(
            //         'driver' => Config::get('mail.driver'),
            //         'host' => Config::get('mail.host'),
            //         'port' => Config::get('mail.port'),
            //         'from' => array('address' => $from_address, 'name' => $from_name),
            //         'encryption' => Config::get('mail.encryption'),
            //         'username' => Config::get('mail.username'),
            //         'password' => Config::get('mail.password'),
            //         'sendmail'   => '/usr/sbin/sendmail -bs',
            //         'pretend'    => false,
            //     );
            //     Config::set('mail', $oldsettings);
            // }
        //}
    }
}
