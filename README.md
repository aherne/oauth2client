# oauth2client

This API came by the idea of building a driver based on IETF specifications that abstracts communication to all OAuth2 providers. Instead of having to download and install each provider's specific driver (eg: Facebook PHP API), as it usually happens, it thould be enough to install this API and load vendor-specific extension on top of it. Extension is needed because API core does not attempt to go above the layer of common IETF specifications/operations all providers must abide to. Everything above that falls into extension's scope: from OAuth2 provider info (eg: URLs and parameters to get authorization codes, access tokens and resources) to the way it formats response. To make developers' life easier, API comes with BOTH core as well as extensions for most common OAuth2 providers.

##How does core work?##

OAuth2\Driver class was built as an abstract hub of control that takes control of communication with OAuth2 provider through internal API components as well as logic that must be implemented by vendor-specific class that extends it. 

##How do extensions work?##

Each extensions corresponds to an individual OAuth2 provider and the way it implements the common specifications. By virtue of that, its class must extend OAuth2\Driver and implement the two abstract operations mentioned above. The only thing left in developers' responsibility is to create a client on provider's site (see "Client Creation URL" below) then investigate in provider's documentation the remote resources they will need for the project (see "Resources Documentation URL" below) along with scopes associated (necessary to be sent in authorization code request).

At this moment, most common OAuth2 providers are already supported:

- Facebook
- Google
- Instagram
- LinkedIn
- GitHub
- VK
- Yandex

Following popular providers are not supported:

- Twitter
- Yahoo
- Microsoft

Either because they haven't migrated to oauth2 (Twitter), or because their docs are broken (Yahoo) or because they force you to buy a certificate (Microsoft)

For more information, check my official documentation:

http://www.lucinda-framework.com/oauth2-client
