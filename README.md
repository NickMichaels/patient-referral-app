# patient-referal-app
A REST API linking patients to healthcare providers

## Initial stories / requirements
* As an admin, I want to be able to create, update and delete provider (PCP, clinic, hospital. specialist, etc.) records :white_check_mark:
* As an admin, I want to be able to create, update and delete practitioner (doctors, nurses, physicians assistants, etc.) records :white_check_mark:
* As an admin, I want to be able to create, update and delete patient (very limited scope - just a `name` column and `data` column - which would hold their "patient record" and could later be encoded and encrypted) records :white_check_mark:
* As a provider, I want to be able to associate practitioners with providers :white_check_mark:
* As a provider, I want to be able to send a patient to another provider via a patient referral :white_check_mark:

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

8. Mod and run the AppFixtures file to create users, s, practicioners and patients

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

# API Documentation

## API Login (aka get your token here)
### Method: POST
>```
> /api/login_check
>```
### Body (**raw**)

```json
{
    "username": "{user_email}",
    "password": "{user_password}"
}
```

## Get All Providers
### Method: GET
>```
> /api/providers
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Get Provider By Id
### Method: GET
>```
> /api/providers/{id}
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Update a Provider
### Method: PATCH
>```
> /api/providers/{id}
>```
### Body (**raw**)

```json
{
    "name": "Robs Health House"
}
```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Delete a Provider
### Method: DELETE
>```
> /api/providers/{id}
>```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Create a Provider
### Method: POST
>```
> /api/providers
>```
### Body (**raw**)

```json
{
    "name": "Bobs Health Clinic",
    "address_line1": "123 Main St",
    "city": "Longmont",
    "state": "CO",
    "zip": 80501,
    "email": "test@example.com",
    "phone": "123-415-9876"
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Add a Practicioner (to a Provider)
### Method: POST
>```
> /api/providers/{id}/add_practicioner
>```
### Body (**raw**)

```json
{
    "practicioner_id": 34
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Send a Patient Referral
### Method: POST
>```
> /api/providers/{id}/send_referral
>```
### Body (**raw**)

```json
{
    "patient_id": 3,
    "receiving_provider_id": 6,
    "sending_practicioner_id":  1,
    "receiving_practicioner_id": 6
}
```
_Note: `sending_practicioner_id` and `receiving_practicioner_id` are optional parameters_

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Get Sent Referrals (for a Provider)
### Method: POST
>```
> /api/providers/{id}/referrals_sent
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Get Received Referrals (for a Provider)
### Method: POST
>```
> /api/providers/{id}/referrals_received
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Get All Practicioners
### Method: GET
>```
> /api/practicioners
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Get One Practicioner By Id
### Method: GET
>```
> /api/practicioners/{id}
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Update a Practicioner
### Method: PATCH
>```
> /api/practicioners/{id}
>```
### Body (**raw**)

```json
{
    "name": "John Smith"
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Delete a Practicioner
### Method: DELETE
>```
> /api/practicioners/{id}
>```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Create a Practicioner
### Method: POST
>```
> /api/practicioners
>```
### Body (**raw**)

```json
{
    "name": "Adam Caruthers",
    "job_title": "Doctor",
    "specialty": "Internal Medicine",
    "license_number": "AB964239",
    "email": "test@example.com",
    "phone": "123-415-9876"
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Get Sent Referrals (for a Practicioner)
### Method: POST
>```
> /api/practicioners/{id}/referrals_sent
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Get Received Referrals (for a Practicioner)
### Method: POST
>```
> /api/practicioners/{id}/referrals_received
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Get All Patients
### Method: GET
>```
> /api/patients
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Get One Patient By Id
### Method: GET
>```
> /api/patients/{id}
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Update a Patient
### Method: PATCH
>```
> /api/patients/{id}
>```
### Body (**raw**)

```json
{
    "name": "John Smith"
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Delete a Patient
### Method: DELETE
>```
> /api/patients/{id}
>```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Create a Patient
### Method: POST
>```
> /api/patients
>```
### Body (**raw**)

```json
{
    "name": "Les Claypool",
    "data": {
        "accountNumber": "GF245398",
        "DOB": "1970-01-01",
        "address": "123 First St Citysville PA 15212"
    },
    "email": "test@example.com",
    "phone": "123-415-9876"
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

