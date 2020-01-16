# Revolution Bar

PHP Wrapper to send contact and authentication and authorization to RDStation

## Instaling 

````
composer require sympla/revolution-bar
````

## Usage

### Authentication
get url to authentication on RDStation plataform


````php
require "vendor/autoload.php";

$authentication = new RDStation\Services\Authentication("YOUR_CLIENT_ID", "YOUR_URL_CALLBACK");
$authentication->getUrlAuthentication();
````

### Authorization
get authorization data for send contact to rdstation

````php
require "vendor/autoload.php";

$authorization = new RDStation\Services\Authorization("YOUR_CLIENT_ID", "YOUR_CLIENT_SECRET", "CODE_RDSTATION");
$authorizationResponse = $authorization->execute();
var_export($authorizationResponse->getAccessToken());
var_export($authorizationResponse->getExpireIn());
var_export($authorizationResponse->getRefreshToken());
````

### Refresh Token
Refreshing an expired token

````php
require "vendor/autoload.php";

$refreshToken = new RDStation\Services\RefreshToken("YOUR_CLIENT_ID", "YOUR_CLIENT_SECRET", "CODE_RDSTATION");
$authorizationResponse = $refreshToken->execute();
var_export($authorizationResponse->getAccessToken());
var_export($authorizationResponse->getExpireIn());
var_export($authorizationResponse->getRefreshToken());
````

### Send Contact
With an UPSERT like behavior, this endpoint is capable of both updating the properties
of a Contact or creating a new Contact. Whatever is used as an identifier cannot appear 
in the request payload as a field. This will result in a BAD_REQUEST error.

````php
require "vendor/autoload.php";

$contactIdentifier = RDStation\Request\ContactIdentifier::EMAIL;

$contactRequest = new RDStation\Request\ContactRequest(ContactIdentifier::EMAIL, [
    "YOUR_CUSTOMER_FIELD"   => "VALUE",   
    "YOUR_CUSTOMER_FIELD_2" => "VALUE",
]);

$contactRequest->setEmail("email@email.com");
$contactRequest->setName("NAME'S LEAD");
$contactRequest->setBio("BIO");
$contactRequest->setCity("BELO HORIZONTE");
$contactRequest->setCountry("BRASIL");
$contactRequest->setFacebook("FACEBOOK_LEAD");
$contactRequest->setJobTitle("JOB_TITLE");
$contactRequest->setLinkedin("LINKDEDIN LEAD");
$contactRequest->setMobilePhone("(31)99999-9999");
$contactRequest->setPersonalPhone("(31)99999-9999");
$contactRequest->setState("MG");
$contactRequest->setWebsite("https://lead_website.com");


$contact = new RDStation\Services\Contact($contactRequest, "YOUR_ACCESS_TOKEN");
var_export($contact->save());
````