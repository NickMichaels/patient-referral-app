# patient-referal-app
A Symfony REST API linking patients to healthcare providers. Instead of scribbled down phone numbers from a PCP putting all of the onus on the patient to take next steps, healthcare providers can send patient referrals directly to another provider.

## Initial stories / requirements
* I want to be able to create, update and delete provider (PCP, clinic, hospital. specialist, etc.) records :white_check_mark:
* I want to be able to create, update and delete practitioner (doctors, nurses, physicians assistants, etc.) records :white_check_mark:
* I want to be able to create, update and delete patient (very limited scope - just a `name` column and `data` column - which would hold their "patient record" and could later be encoded and encrypted) records :white_check_mark:
* I want to be able to associate practitioners with providers :white_check_mark:
* I want to be able to send a patient to another provider via a patient referral :white_check_mark:

## Future Scope
* As a provider, I want to be able to add other providers to my network.
* As a provider, I want to be able to message other providers
* As a provider, I want to be able to to schedule patients from a referral
* As a patient, I want to be able to message providers

## Installation

1. Clone the repo

```
git clone git@github.com:NickMichaels/patient-referral-app.git
```

2. Make the .env file  

```
cp .env.dist .env
```

3. Run composer

```
composer install
```

4. Modify the database (example here is for MySql) 

```
DATABASE_URL="mysql://[db_user]:[db_pass]@127.0.0.1:3306/[unique_db_name]?charset=UTF8MB4&server_version=8.0"
```

5. Create the database  

```
php bin/console doctrine:database:create
```

6. Run migrations  

```
php bin/console doctrine:migrations:migrate
```

7. Configure JWT signatures _(there is a php bin/console lexik:jwt:generate-keypair command that is supposed to do this, but it's giving me issues on a fresh repo)_

```
mkdir config/jwt
chmod -R 777 config/jwt
openssl genrsa -out ./config/jwt/private.pem
openssl rsa -in config/jwt/private.pem -pubout > config/jwt/public.pem
```

8. Mod and run the AppFixtures file to create users, s, practitioners and patients

    - In function createUsers, modify the `$user->setEmail(“your_email_here@example.com”)` and `$user->setPassword('your_hashed_pw_here')` to use your own dummy data. you can follow the instruction in the comments to hash your own password using a symfony command.  

    ```
    $user = new User;
    $user->setEmail("your_email_here@example.com");
    $user->setRoles(["ROLE_USER"]);
    // You can run php bin/console security:hash-password
    // and follow the prompts to hash your own pw
    $user->setPassword('your_hashed_pw_here');
    
    ```

    - Run the fixture  

    ```
    php bin/console doctrine:fixtures:load --append
    ```

9. Modify the .env to point to the the new JWT keys  
 
```
###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=
###< lexik/jwt-authentication-bundle ###
```

10. Run the server  

```
symfony serve 
```

11. Get token  

Submit a request to the api/login_check endpoint with username and password for one of the users created earlier  

![Get API Token](assets/get_api_token.png)

Copy the token it gives you back. You can now use this to retrieve info via the API by setting it as a Bearer Token.  
 
![Use API Token](assets/use_api_token.png)

## API Documentation

[API Docs](https://github.com/NickMichaels/patient-referral-app/blob/main/docs/api_documentation.md)
