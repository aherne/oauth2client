# OAuth2 Client API

Table of contents:

- [About](#about)
- [Registration](#registration)
- [Configuration](#configuration)
- [Execution](#execution)
    - [Initialization](#initialization)
    - [Querying Provider](#querying-provider)
- [Installation](#installation)
- [Unit Tests](#unit-tests)

## About 

This API, came by the idea of building a shared driver based on [IETF specs](https://tools.ietf.org/html/rfc6749) that abstracts communication with popular OAuth2 providers so you're no longer forced to work their bloated PHP clients. 

![diagram](https://www.lucinda-framework.com/oauth2-client-api.svg)

It has now become a fully matured time-tested API able to hide almost entirely communication complexity with these providers by this series of steps:

- **[registration](#registration)**: registering your site on oauth2 providers in order to be able to query them later on
- **[configuration](#configuration)**: setting up an XML file where one or more loggers are set for each development environment
- **[initialization](#initialization)**: creating a [Lucinda\OAuth2\Wrapper](https://github.com/aherne/oauth2client/blob/v4.0/src/Wrapper.php) instance based on above XML and current development environment then calling *getDriver()* method based on requested page
- **[querying provider](#querying-provider)**: use shared driver [Lucinda\OAuth2\Driver](https://github.com/aherne/oauth2client/blob/v4.0/src/Driver.php) instance resulting from method above to query respective provider

API is fully PSR-4 compliant, only requiring PHP 8.1+ interpreter, [Lucinda URL Requester](https://github.com/aherne/requester) and SimpleXML extension. To quickly see how it works, check:

- **[installation](#installation)**: describes how to install API on your computer, in light of steps above
- **[unit tests](#unit-tests)**: API has 100% Unit Test coverage, using [UnitTest API](https://github.com/aherne/unit-testing) instead of PHPUnit for greater flexibility
- **[example](#example)**: shows a example of API functionality based on unit test for [Lucinda\OAuth2\Wrapper](https://github.com/aherne/oauth2client/blob/v4.0/src/Wrapper.php)

## Registration

OAuth2 requires your site (the client) to be available on world-wide-web in order to communicate with provider (the server). To do so your site must be registered on provider's site, same way as a user would! Registration endpoints for supported providers are:

- Facebook: [https://developers.facebook.com/](https://developers.facebook.com/)
- Google: [https://console.developers.google.com](https://console.developers.google.com)
- Instagram: [https://www.instagram.com/developer/clients/register/](https://www.instagram.com/developer/clients/register/)
- LinkedIn: [https://www.linkedin.com/developer/apps/new](https://www.linkedin.com/developer/apps/new)
- GitHub: [https://github.com/settings/applications/new](https://github.com/settings/applications/new)
- VK: [https://vk.com/editapp?act=create](https://vk.com/editapp?act=create)
- Yahoo: [https://developer.yahoo.com/apps/create/](https://developer.yahoo.com/apps/create/)
- Yandex: [https://oauth.yandex.com/client/new](https://oauth.yandex.com/client/new)

Once you land there you will be asked on registration to fill a form in which you will need to supply:

- **redirect_uri**: (always) complete link to your site where OAUTH2 vendor should redirect authorization code to
- **scopes**: (sometimes) rights specific to vendor your site require on each site client's account
- **application_name**: (only by GitHub) unique name that identifies your site against others

Once registered, your site will get:

- **client_id**: public id that identifies your site on OAUTH2 vendor site
- **client_secret**: private key associated to your site on OAUTH2 vendor site, to use in authorization code - access token exchange

To learn more how each of them work, check [specialized article](https://www.lucinda-framework.com/blog/php-oauth2-integration-explained)

## Configuration

To configure this API you must have a XML with following tag:

```xml
<oauth2>
	<{ENVIRONMENT}>
		<driver name="..." client_id="..." client_secret="..." callback="..." scopes="..." {OPTIONS}/>
		...
	</{ENVIRONMENT}>
	...
</oauth2>
```

Where:

- **oauth2**: (mandatory) holds global oauth2 settings.
    - {ENVIRONMENT}: name of development environment (to be replaced with "local", "dev", "live", etc)
        - **driver**: stores information about a single oauth2 provider via attributes:
            - *name*: (mandatory) name of OAuth2 provider. Can be: Facebook, GitHub, Google, LinkedIn, Instagram, VK, Yahoo, Yandex!
            - *client_id*: (mandatory) public id that identifies your site on OAUTH2 vendor site (see: [registration](#registration))
            - *client_secret*: (mandatory) private key associated to your site on OAUTH2 vendor site, to use in authorization code - access token exchange (see: [registration](#registration))
            - *callback*: (mandatory) relative uri (page) in your site where OAUTH2 vendor should redirect authorization code to (see: [registration](#registration)). **Must be unique!**
            - *scopes*: (optional) rights levels on client's vendor account your site require (see: [registration](#registration))
            - {OPTIONS}: a list of extra attributes not part of specifications but required by certain providers:
                - *application*: (mandatory if *provider* = GitHub) name of your site (see: [registration](#registration))

Example:

```xml
<oauth2>
    <live>
        <driver name="Facebook" client_id="YOUR_CLIENT_ID" client_secret="YOUR_CLIENT_SECRET" callback="login/facebook" scopes="public_profile,email"/>
        <driver name="Google" client_id="YOUR_CLIENT_ID" client_secret="YOUR_CLIENT_SECRET" callback="login/google" scopes="https://www.googleapis.com/auth/plus.login,https://www.googleapis.com/auth/plus.profile.emails.read"/>
    </live>
</oauth2>
```

## Execution

### Initialization

Now that XML is configured, you can get driver whose login uri matches requested page by querying [Lucinda\OAuth2\Wrapper](https://github.com/aherne/oauth2client/blob/v4.0/src/Wrapper.php):

```php
$requestedPage = (!empty($_SERVER["REQUEST_URI"])?substr($_SERVER["REQUEST_URI"], 1):"");
$object = new Lucinda\OAuth2\Wrapper(simplexml_load_file(XML_FILE_NAME), DEVELOPMENT_ENVIRONMENT);
$driver = $object->getDriver($requestedPage);
```

Driver returned is a [Lucinda\OAuth2Client\Driver](https://github.com/aherne/oauth2client/blob/v4.0/src/Driver.php) instance, each corresponding to a "driver" tag whose callback matches requested page, each hiding complexity of vendor underneath through a common interface centered on oauth2 client operations. If no driver is found matching requested page, NULL is returned!

**NOTE**: because XML parsing is somewhat costly, it is recommended to save $object somewhere and reuse it throughout application lifecycle.

### Querying Provider

Once you obtain a driver, you able to query it automatically. First however you need to obtain an access token from provider in controller that handles all **REDIRECT_URI** (since this logic is same across vendors):

```php
if (empty($_GET["code"])) {
    // redirects to vendor in order to get authorization code
    $redirectURL = $driver->getAuthorizationCodeEndpoint();
    header("Location: ".$redirectURL);
    exit();
} else {
    // exchanges authorization code with an access token
    $accessTokenResponse = $driver->getAccessToken($_GET["code"]);
    
    // save $accessTokenResponse to storage
    // save $driver to storage
}
```

Once an access token is saved you can use it in current or future requests to authenticate resources requests on vendor. Before using it, you need to make sure token has not expired:

```php
// loads $accessTokenResponse from storage
if ($accessTokenResponse->getExpiresIn() && $accessTokenResponse->getExpiresIn()>time()) {
    $accessTokenResponse = $driver->refreshAccessToken($accessTokenResponse->getRefreshToken());
    // save $accessTokenResponse to storage
}
```

Then to retrieve any resource on vendor whose scope was approved by client:

```php
$accessToken = $accessTokenResponse->getAccessToken();
$information = $driver->getResource(accessToken, RESOURCE_URI, ?RESOURCE_FIELDS);
```

### Example

Assuming driver is:

```xml
<driver name="Facebook" client_id="YOUR_CLIENT_ID" client_secret="YOUR_CLIENT_SECRET" callback="login/facebook" scopes="public_profile,email"/>
```

If value of $_SERVER["REQUEST_URI"] is "login/facebook", in line of [Querying Provider](#querying-provider) above, first a check is made if "code" querystring param is present:

- NO: redirects to provider and asks client to approve access for public_profile and email visualization rights. If approved, vendor redirects to same page but with a "code" param
- YES: asks provider to exchange short lived authorization code (value of "code" param) with a long lived access token

Now that access token is obtained, developers can use it to retrieve public_profile and email information about client from vendor site:

```php
// load $driver from storage
// load $accessToken from storage
$userInformation = $driver->getResource($accessToken, "https://graph.facebook.com/v2.8/me", ["id","name","email"]);
```    

## Installation

First choose a folder, associate it to a domain then write this command there using console:

```console
composer require lucinda/oauth2-client
```

Then create a *configuration.xml* file holding configuration settings (see [configuration](#configuration) above) and a *index.php* file (see [initialization](#initialization) in project root with following code:

```php
require(__DIR__."/vendor/autoload.php");
$requestedPage = (!empty($_SERVER["REQUEST_URI"])?substr($_SERVER["REQUEST_URI"], 1):"");
$object = new Lucinda\OAuth2\Wrapper(simplexml_load_file("configuration.xml"), "local");
$driver = $object->getDriver($requestedPage);
```

Then make sure domain is available to world-wide-web and all request that point to it are rerouted to index.php:

```
RewriteEngine on
RewriteRule ^(.*)$ index.php
```

## Unit Tests

For tests and examples, check following files/folders in API sources:

- [test.php](https://github.com/aherne/oauth2client/blob/v4.0/test.php): runs unit tests in console
- [unit-tests.xml](https://github.com/aherne/oauth2client/blob/v4.0/unit-tests.xml): sets up unit tests and mocks "loggers" tag
- [tests](https://github.com/aherne/oauth2client/tree/v3.0.0/tests): unit tests for classes from [src](https://github.com/aherne/oauth2client/tree/v3.0.0/src) folder
- [tests_drivers](https://github.com/aherne/oauth2client/tree/v3.0.0/tests_drivers): unit tests for classes from [drivers](https://github.com/aherne/oauth2client/tree/v3.0.0/drivers) folder
