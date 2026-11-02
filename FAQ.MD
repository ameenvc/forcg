1. PHP artisan error - table found
Sol: https://stackoverflow.com/questions/41209349/requirevendor-autoload-php-failed-to-open-stream

2. FRC email format error
3. Trying to get property compsize of non-object
Sol: php artisan db:seed

4. Document explorer folders get locked
sol: make a fresh pull from github to server

5. Document explorer internal storage error
sol: public/files folder is missing

6. button not working($ not defined after using jquery)
sol: window.onload = function(){
  // Your code here
};

7. The provided account key '' is not a valid base64 string. It has to pass the check 'base64_decode(<user_account_key>, true)'.
sol: AZURE_STORAGE_CONNECTION_STRING="connectionStringGoesHere"
Including quotes worked for me.

8. The resource you are looking for has been removed, had its name change, or is temporarily not available
sol: placing web.config file in the public folder (the below code)
<configuration>
  <system.webServer>
    <rewrite>
      <rules>
        <rule name="Imported Rule 1" stopProcessing="true">
          <match url="^(.*)/$" ignoreCase="false" />
          <conditions>
            <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
          </conditions>
          <action type="Redirect" redirectType="Permanent" url="/{R:1}" />
        </rule>
        <rule name="Imported Rule 2" stopProcessing="true">
          <match url="^" ignoreCase="false" />
          <conditions>
            <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
            <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
          </conditions>
          <action type="Rewrite" url="index.php" />
        </rule>
      </rules>
    </rewrite>
  </system.webServer>
</configuration>

9. $ is not defined
sol: change the jquery link from http to https
10: class ZipArchive not found while uploading excel files
sol: https://stackoverflow.com/questions/49976471/laravel-excel-class-ziparchive-not-found + apache restart

10. can not login to admin panel
sol: the password of user is different in admins and users table, we should copy from users table to admins table

11. SQLSTATE[HY000] [2002] An attempt was made to access a socket in a way forbidden by its access permissions.
   (SQL: select * from `error_logs` where `error` = *Error:* SQLSTATE[HY000
  ] [2002] An attempt was made to access a socket in a way forbidden by its access permissions.
  sol: remove if (Schema::hasTable('user_gates'))  in app/providers file

12. elfinder https error when deploying to another server
sol: please check https://github.com/barryvdh/laravel-elfinder and do the commands in this website again (commands of installation and configuration)

below are the commands
- php artisan elfinder:publish
- php artisan vendor:publish --provider='Barryvdh\Elfinder\ElfinderServiceProvider' --tag=config
- php artisan vendor:publish --provider='Barryvdh\Elfinder\ElfinderServiceProvider' --tag=views

13. Mixed Content: The page at xxx was loaded over HTTPS, but requested an insecure yyy
sol: please add <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> in header
another sol: please add FORCE_HTTPS=true in .env file and then below code in boot function of AppServiceProvider
 if (env('FORCE_HTTPS', false)) { // Default value should be false for local server
    URL::forceScheme('https');
}

14. deployment error as below
Problem 1
    - Root composer.json requires php ^7.4.15 but your php version (8.1.14) does not satisfy that requirement.
  Problem 2
    - dompdf/dompdf is locked to version v0.8.6 and an update of this package was not requested.
    - dompdf/dompdf v0.8.6 requires php ^7.1 -> your php version (8.1.14) does not satisfy that requirement.
  Problem 3
    - laravel/ui is locked to version v2.0.0 and an update of this package was not requested.
    - laravel/ui v2.0.0 requires php ^7.2.5 -> your php version (8.1.14) does not satisfy that requirement.
  Problem 4
    - dompdf/dompdf v0.8.6 requires php ^7.1 -> your php version (8.1.14) does not satisfy that requirement.
    - barryvdh/laravel-dompdf v0.8.7 requires dompdf/dompdf ^0.8 -> satisfiable by dompdf/dompdf[v0.8.6].
    - barryvdh/laravel-dompdf is locked to version v0.8.7 and an update of this package was not requested.

sol: It is not related to upgrading your php version to 8.1.14, only you should change "php": "^7.2.5" to "php": "^7.2.5|^8.0"
You should change this is both composer.lock file and composer.json files for the packages that this error came (like here dompdf/dompdf, laravel/ui, barryvdh/laravel-dompdf)

15. A person should be in both admins and users table so that he/she can see both privileges and his/her email should be the same in both users and admins table

16. Fixing deployment issue when says composer exceed time limit
sol: composer config --global process-timeout 2000
run this command then deploy (it can be 2000 or 3000)

17. file_exists(): open_basedir restriction in effect
sol: it is because of the symlink already existing, run below commands:
- rm -rf public/storage
- chmod -R 755 storage/app/public
- php artisan storage:link

18. Target class not exists after running php artisan db:seed
- first run composer dumpautoload

19. If a record not deleted somewhere and loading issue happens, it is 302 redirect issue(deployment issue)
- Sol: Please remove return redirect from controller of that page

20. mail issue -> Expected response code 250 but got "550"
- Sol: Please remove "->from($address = 'system@quickqm.com', $name = $this->data['company'] . '/cgbod')" from mail function

21. page unresponsive issue
- Sol: Try to check all foreach loops inside foreach loops
