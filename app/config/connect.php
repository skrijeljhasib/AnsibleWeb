<?php
use LightSaml\Credential\X509Certificate;
use LightSaml\Model\Metadata\AssertionConsumerService;
use LightSaml\Model\Metadata\IdpSsoDescriptor;
use LightSaml\Model\Metadata\KeyDescriptor;
use LightSaml\Model\Metadata\SingleLogoutService;
use LightSaml\Model\Metadata\SingleSignOnService;
use LightSaml\Model\Metadata\SpSsoDescriptor;
use LightSaml\SamlConstants;
use ObjectivePHP\Package\Connect\Config\IdentityProviderParam;
use ObjectivePHP\Package\Connect\Config\PrivateKey;
use ObjectivePHP\Package\Connect\Config\ServiceProvider;
return [
    new ServiceProvider(
        (new SpSsoDescriptor())
            ->setID('http://stackstorm.test.flash-global.net:8888')
            ->addAssertionConsumerService(
                new AssertionConsumerService(
                    'http://stackstorm.test.flash-global.net:8888/acs',
                    SamlConstants::BINDING_SAML2_HTTP_POST
                )
            )
            ->addSingleLogoutService(
                new SingleLogoutService(
                    'http://stackstorm.test.flash-global.net:8888/logout',
                    SamlConstants::BINDING_SAML2_HTTP_POST
                )
            )
            ->addKeyDescriptor(new KeyDescriptor(
                KeyDescriptor::USE_SIGNING,
                X509Certificate::fromFile(__DIR__ . '/keys/sp.crt')
            ))
            ->addKeyDescriptor(new KeyDescriptor(
                KeyDescriptor::USE_ENCRYPTION,
                X509Certificate::fromFile(__DIR__ . '/keys/sp.crt')
            ))
    ),
    new IdentityProviderParam(
        'default',
        (new IdpSsoDescriptor())
            ->setID('http://connect.test.flash-global.net:8080')
            ->setWantAuthnRequestsSigned(true)
            ->addSingleSignOnService(
                new SingleSignOnService('http://connect.test.flash-global.net:8080/sso', SamlConstants::BINDING_SAML2_HTTP_REDIRECT)
            )
            ->addSingleLogoutService(
                new SingleLogoutService('http://connect.test.flash-global.net:8080/logout', SamlConstants::BINDING_SAML2_HTTP_POST)
            )
            ->addKeyDescriptor(new KeyDescriptor(
                KeyDescriptor::USE_SIGNING,
                X509Certificate::fromFile(__DIR__ . '/keys/idp/idp.crt')
            ))
    ),
    new PrivateKey(file_get_contents(__DIR__ . '/keys/sp.pem')),
];
