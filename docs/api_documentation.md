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

## Add a Practitioner (to a Provider)
### Method: POST
>```
> /api/providers/{id}/add_practitioner
>```
### Body (**raw**)

```json
{
    "practitioner_id": 34
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
    "sending_practitioner_id":  1,
    "receiving_practitioner_id": 6
}
```
_Note: `sending_practitioner_id` and `receiving_practitioner_id` are optional parameters_

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

## Get All Practitioners
### Method: GET
>```
> /api/practitioners
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Get One Practitioner By Id
### Method: GET
>```
> /api/practitioners/{id}
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Update a Practitioner
### Method: PATCH
>```
> /api/practitioners/{id}
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

## Delete a Practitioner
### Method: DELETE
>```
> /api/practitioners/{id}
>```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Create a Practitioner
### Method: POST
>```
> /api/practitioners
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

## Get Sent Referrals (for a Practitioner)
### Method: POST
>```
> /api/practitioners/{id}/referrals_sent
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|your_token_here|string|

## Get Received Referrals (for a Practitioner)
### Method: POST
>```
> /api/practitioners/{id}/referrals_received
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

