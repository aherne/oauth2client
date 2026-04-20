# OAuth2 Client API

Lightweight OAuth2 login integration for PHP applications.

This package is now intentionally focused on authentication entry points rather than generic OAuth2 resource access. In practice, you configure one or more vendor drivers in XML, build a `Lucinda\OAuth2\Wrapper`, then use the resolved `Lucinda\OAuth2\Driver` to:

- generate the authorization URL
- exchange the authorization code for an access token
- optionally refresh the access token
- normalize vendor user data into a shared `Lucinda\OAuth2\UserInfo` object

## Installation

```console
composer require lucinda/oauth2-client
```

Requirements:

- PHP 8.1+
- `ext-SimpleXML`
- `lucinda/requester`

## Supported Drivers

The current package ships with these drivers:

- `Facebook`
- `GitHub`
- `Google`
- `LinkedIn`
- `Microsoft`

Provider endpoints and default scopes are defined internally in [endpoints.json](https://github.com/aherne/oauth2client/blob/v5.0/endpoints.json), so XML only needs client-side registration details.

## XML Configuration

The runtime entry point is [src/Wrapper.php](https://github.com/aherne/oauth2client/blob/v5.0/src/Wrapper.php). It parses the `<oauth2>` section, instantiates drivers, and indexes them by `callback`.

Each `<driver>` tag must include:

- `name`: one of the supported drivers above
- `client_id`: OAuth client/application id issued by the vendor
- `client_secret`: OAuth client/application secret issued by the vendor
- `callback`: relative callback/login route used to resolve the driver later

GitHub requires one extra attribute:

- `application_name`: mandatory for `GitHub`

Example:

```xml
<xml>
  <oauth2>
    <driver
      name="Facebook"
      client_id="YOUR_FACEBOOK_CLIENT_ID"
      client_secret="YOUR_FACEBOOK_CLIENT_SECRET"
      callback="login/facebook"
    />
    <driver
      name="Google"
      client_id="YOUR_GOOGLE_CLIENT_ID"
      client_secret="YOUR_GOOGLE_CLIENT_SECRET"
      callback="login/google"
    />
    <driver
      name="GitHub"
      client_id="YOUR_GITHUB_CLIENT_ID"
      client_secret="YOUR_GITHUB_CLIENT_SECRET"
      application_name="Your Application Name"
      callback="login/github"
    />
  </oauth2>
</xml>
```

Notes:

- `callback` must be unique per driver because `Wrapper` stores drivers by callback path.
- The XML consumed by `Wrapper` must contain `<oauth2>` directly. The current parser does not look up environment-specific child nodes.

## Entry Points

These are the only concepts most applications need to care about.

### `Lucinda\OAuth2\Wrapper`

Builds all configured drivers and exposes them by callback:

```php
use Lucinda\OAuth2\Wrapper;

$wrapper = new Wrapper(simplexml_load_file("configuration.xml"));
$driver = $wrapper->getDriver("login/google");
```

You can also retrieve all configured drivers:

```php
$drivers = $wrapper->getDriver();
```

### `Lucinda\OAuth2\Driver`

Each concrete vendor driver extends the shared [src/Driver.php](https://github.com/aherne/oauth2client/blob/v5.0/src/Driver.php) API and exposes the same login-oriented methods:

- `getAuthorizationCodeEndpoint(string $state = ""): string`
- `getAccessToken(string $authorizationCode): Lucinda\OAuth2\AccessToken\Response`
- `refreshAccessToken(string $refreshToken): Lucinda\OAuth2\AccessToken\Response`
- `getUserInfo(string $accessToken): Lucinda\OAuth2\UserInfo`

## Login Flow

### 1. Resolve driver by callback route

```php
use Lucinda\OAuth2\Wrapper;

$requestedPage = ltrim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) ?? "", "/");
$wrapper = new Wrapper(simplexml_load_file("configuration.xml"));
$driver = $wrapper->getDriver($requestedPage);
```

If no driver matches the requested callback, `getDriver()` returns `null`.

### 2. Redirect user to provider

```php
if (empty($_GET["code"])) {
    $authorizationUrl = $driver->getAuthorizationCodeEndpoint();
    header("Location: ".$authorizationUrl);
    exit;
}
```

If you need CSRF/state handling:

```php
$authorizationUrl = $driver->getAuthorizationCodeEndpoint($state);
```

### 3. Exchange code for token

```php
$tokenResponse = $driver->getAccessToken($_GET["code"]);

$accessToken = $tokenResponse->getAccessToken();
$refreshToken = $tokenResponse->getRefreshToken();
$expiresAt = $tokenResponse->getExpiresIn();
```

`AccessToken\Response` exposes:

- `getAccessToken()`
- `getTokenType()`
- `getExpiresIn()`
- `getRefreshToken()`
- `getScope()`

### 4. Refresh token when needed

```php
if ($tokenResponse->getRefreshToken() && $tokenResponse->getExpiresIn() <= time()) {
    $tokenResponse = $driver->refreshAccessToken($tokenResponse->getRefreshToken());
}
```

### 5. Load normalized user data

```php
$userInfo = $driver->getUserInfo($tokenResponse->getAccessToken());

$id = $userInfo->getId();
$name = $userInfo->getName();
$email = $userInfo->getEmail();
```

`getUserInfo()` is the key post-login convenience API in the new design: vendor-specific user endpoints are queried internally, then mapped to the shared `Lucinda\OAuth2\UserInfo` value object.

## Error Handling

Configuration and request validation errors surface as:

- `Lucinda\OAuth2\Client\Exception`

OAuth server-side failures surface as:

- `Lucinda\OAuth2\Server\Exception`

Examples of configuration failures enforced by the current code:

- missing `<driver>` tags
- missing `name`, `client_id`, `client_secret`, or `callback`
- missing `application_name` for GitHub
- configured driver class not implemented
- configured driver not present in `endpoints.json`

## Practical Example

```php
use Lucinda\OAuth2\Wrapper;

$requestedPage = ltrim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) ?? "", "/");
$wrapper = new Wrapper(simplexml_load_file("configuration.xml"));
$driver = $wrapper->getDriver($requestedPage);

if (!$driver) {
    http_response_code(404);
    exit("OAuth2 driver not found");
}

if (empty($_GET["code"])) {
    header("Location: ".$driver->getAuthorizationCodeEndpoint());
    exit;
}

$tokenResponse = $driver->getAccessToken($_GET["code"]);
$userInfo = $driver->getUserInfo($tokenResponse->getAccessToken());

echo $userInfo->getName();
```

## Tests

The project uses `lucinda/unit-testing`.

Useful files:

- [test.php](https://github.com/aherne/oauth2client/blob/v5.0/test.php): test runner
- [unit-tests.xml](https://github.com/aherne/oauth2client/blob/v5.0/unit-tests.xml): test suite configuration
- [tests](https://github.com/aherne/oauth2client/blob/v5.0/tests): unit tests for `src/`
- [tests_drivers](https://github.com/aherne/oauth2client/blob/v5.0/tests_drivers): unit tests for vendor drivers

Run the suite with:

```console
php test.php
```
