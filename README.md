# oauth2client

This API came by the idea of building a driver based on IETF specifications that abstracts communication to all OAuth2 providers. Instead of having to download and install each provider's specific driver (eg: Facebook PHP API), as it usually happens, it thould be enough to install this API and load vendor-specific extension on top of it. Extension is needed because API core does not attempt to go above the layer of common IETF specifications/operations all providers must abide to. Everything above that falls into extension's scope: from OAuth2 provider info (eg: URLs and parameters to get authorization codes, access tokens and resources) to the way it formats response. To make developers' life easier, API comes with BOTH core as well as extensions for most common OAuth2 providers.

At this moment, most common OAuth2 providers are already supported: Facebook, Google, Instagram, LinkedIn, GitHub, VK, Yandex. Following popular providers are not: Twitter (because they haven't migrated to OAuth2), Yahoo (because their documentation is broken) and Microsoft (because they force you to buy a certificate in order to test it).

In the end, what this API achieves is operativity with an OAuth2 vendor in lines as few as this:

```php
$ci = new OAuth2\ClientInformation(CLIENT_ID, CLIENT_SECRET, REDIRECT_URI);
$driver = new FacebookDriver($ci);
if(!empty($_GET["code"])) {
	// uses authorization code to get an oauth2 token
	$tokenInfo = $driver->getAccessToken($_GET["code"]);
	// get remote user info
	var_dump($driver->getResource(RESOURCE_URI, RESOURCE_FIELDS));
} else {
	// redirects to provider for authorization code request (eg: using scopes required to get remote user info)
	$redirectURL = $driver->getAuthorizationCode(SCOPES);
	header("Location: ".$redirectURL);
	exit();
}
```

More information here:<br/>
http://www.lucinda-framework.com/oauth2-client
