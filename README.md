# Instructions

    Clone or Download and unzip file this repository to your preferred directory
    Open command terminal and navigate to the directory, i.e cd <path-to-directory>
    The 'api' and 'web' folders here, are the Laravel(backend) and Vue(frontend) apps respectively


## How to run
	
### Laravel
    From your command terminal 'cd' into the 'api' directory
    Type 'composer install' and click 'enter'
    After installation is complete, create a new file named '.env' and copy the contents of .env.example into it
    In the .env file, set your Database credentials (Host, Username, Password).
    At the end of the file, you can also change the USD_GBP_RATE variable. (It is used to set the conversion factor, in case of cross-currency transactions). When changes are made to the .env file, re-run "php artisan config:cache" to effect the new changes
    Finally run:
        php artisan key:generate
        php artisan migrate:fresh --seed
        php artisan serve (to launch the app on your local server)

### Vue
    Open a new command terminal and 'cd' into the 'web' directory
    Type 'npm install' and click 'enter'
    After installation is complete, create a new file named '.env' and copy the contents of .env.example into it
    In the .env file, set your API_BASE_URL variable to your "<server address>/api".
    You can also change your ENCRYPTION_KEY
    Then run:
        npm run dev (to launch the app in dev mode)
    In order to login use "1234567890" or "0987654321". You can also "open an account" and be issued new Account IDs
    To run tests:
        npm run test
