# API v.3.0 client

**Table of Contents**

* [Installation](#installation)
* [Usage](#usage)
* [Error handling](#error-handling)
* [Methods](#methods)
  * [Domains](#domains)
     * [createDomainRegistration](#createdomainregistration)
     * [createDomainTransfer](#createdomaintransfer)
     * [domainDetails](#domaindetails)
     * [domainGenAuthCode](#domaingenauthcode)
     * [domainList](#domainlist)
     * [editDomain](#editdomain)
     * [searchDomain](#searchdomain)
     * [updateDomainDNS](#updatedomaindns)
     * [updateDomainRenew](#updatedomainrenew)
  * [DNS](#dns)
     * [checkDnsZone](#checkdnszone)
     * [dnsAddRecord](#dnsaddrecord)
     * [dnsDeleteRecord](#dnsdeleterecord)
     * [dnsGetRecords](#dnsgetrecords)
     * [dnsUpdateRecord](#dnsupdaterecord)
     * [publishDnsSec](#publishdnssec)
     * [unpublishDnsSec](#unpublishdnssec)
  * [Economy](#economy)
     * [priceList](#pricelist)
  * [Miscellaneous](#miscellaneous)
     * [createLabel](#createlabel)
     * [deleteLabel](#deletelabel)
     * [getLabels](#getlabels)
     * [updateLabel](#updatelabel)
  * [Request](#request)
     * [requestCancellation](#requestcancellation)
     * [requestDetails](#requestdetails)
     * [requestList](#requestlist)
     * [requestUpdate](#requestupdate)
  * [Contacts](#contacts)
     * [contactList](#contactlist)
     * [createContact](#createcontact)
     * [updateContact](#updatecontact)


### Installation
You need [composer](https://getcomposer.org/) to install this library.
```bash
composer require nameisp/api3-lib
```

### Usage
```php
use NameISP\API3\Client;

$api = new Client('YOUR_API_KEY');
```

If you want to use non-default another base URL for api, you could pass it as second parameter:
```php
$api = new Client('YOUR_API_KEY', 'https://example.com/api');
```

### Error handling
There are some types of exceptions, that you are able to catch:

**NameISP\API3\Exception\BaseException**

If you want to catch all exception types from this library, you should use this exception class:
```php
use NameISP\API3\Exception\BaseException;

try {
    $api->searchDomain('example.org');
} catch (BaseException $e) {
    // Do something...
}
```

This should be enought for a basic application. But if you need more control you could also catch these exceptions:

**NameISP\API3\Exception\ApiException**

When API returns non-succesful response. You are able to get API error code and message from that exception:
```php
use NameISP\API3\Exception\ApiException;

try {
    $api->searchDomain('example.org');
} catch (ApiException $e) {
    var_dump($e->getCode()); // int(2303)
    var_dump($e->getMessage()); // string(21) "Object does not exist"
}
```

**NameISP\API3\Exception\AuthFailedException**

This exception throws when authorization was failed with your API token.

**NameISP\API3\Exception\NetworkException**

Any network exception. If you need more details, you could get original [Guzzle](http://docs.guzzlephp.org/en/latest/index.html) exception by using `$e->getPrevious();`


# Methods
If some argument is null by default, it's optional, others are required.

If you want to unset boolean flag, you should pass `false` (e.g. `$setAutoRenew` argument in the `editDomain` method)

Almost all methods return an array as result. Some returns string. The [checkDnsZone](#checkdnszone) method returns boolean.

## Domains
### createDomainRegistration
```php
/**
 * @param string $domainName must be fully qualified domain name
 * @param int $itemYear
 * @param int $ownerId
 * @param int $adminId
 * @param int $techId
 * @param bool $autoRenew
 * @param bool $shieldWhoIs
 * @param bool $trustee
 * @param bool $tmchacceptance is only required if the domain name has a claim. If there is no claim this parameter is ignored.
 */
public function createDomainRegistration($domainName, $itemYear, $ownerId, $adminId = null, $techId = null, $autoRenew = null, $shieldWhoIs = null, $trustee = null, $tmchacceptance = null)
```
[Example](doc/createDomainRegistration.md)

### createDomainTransfer
```php
/**
 * @param string $domainName must be fully qualified domain name
 * @param string $auth
 */
public function createDomainTransfer($domainName, $auth = false)
```
[Example](doc/createDomainTransfer.md)

### domainDetails
```php
/**
 * @param int $itemid
 */
public function domainDetails($itemid)
```
[Example](doc/domainDetails.md)

### domainGenAuthCode
```php
/**
 * @param string $domainName
 */
public function domainGenAuthCode($domainName)
```
[Example](doc/domainGenAuthCode.md)

### domainList
```php
/**
 * @param string $domainName
 * @param int $limit default: 100, min: 1 max: 1000, invalid values are ignored and default value is used
 * @param int $start default: 0
 */
public function domainList($domainName = null, $limit = null, $start = null)
```
[Example](doc/domainList.md)

### editDomain
```php
/**
 * @param array|string $domainName must be fully qualified domain name
 * @param bool $setAutoRenew
 * @param bool $setShieldWhoIs
 * @param array $addLabel
 * @param array $removeLabel
 */
public function editDomain($domainName, $setAutoRenew = null, $setShieldWhoIs = null, array $addLabel = null, array $removeLabel = null)
```
Example:
```php
$api->editDomain('example.com', false, null, ['label']); // Returns an empty array on success
```

### searchDomain
```php
/**
 * @param array|string $domainName
 */
public function searchDomain($domainName)
```
[Example](doc/searchDomain.md)

### updateDomainDNS
```php
/**
 * @param string $domainName must be fully qualified domain name
 * @param array $nameServer
 */
public function updateDomainDNS($domainName, array $nameServer)
```
[Example](doc/updateDomainDNS.md)

### updateDomainRenew
```php
/**
 * @param string $domainName must be fully qualified domain name
 * @param int $itemYear
 */
public function updateDomainRenew($domainName, $itemYear)
```
[Example](doc/updateDomainRenew.md)


## DNS
### checkDnsZone
```php
/**
 * @param string $domainName
 * @param string $nameServer
 */
public function checkDnsZone($domainName, $nameServer)
```
Example:
```php
$api->checkDnsZone('example.com', 'ns1.nameisp.info'); // Returns true or false
```

### dnsAddRecord
```php
/**
 * DnsAddRecord
 *
 * Notes:
 * Using $type = redirect will create a redirect
 *  - Valid values for $redirectType are 301, 302 & frame. Default: 301
 *  - Forward url is to be entered in content ex. http://www.example.com
 * Using $type = mailforward will create a mailforward
 *  - Valid values for name is full qualified email address for the domainname
 *  - Forward email is to be entered in content ex. example@example.com
 *
 * @param string $domainName
 * @param string $name
 * @param string $type
 * @param string $content
 * @param int $ttl
 * @param int $prio
 * @param string $redirectType
 */
public function dnsAddRecord($domainName, $name, $type, $content, $ttl, $prio = null, $redirectType = null)
```
[Example](doc/dnsAddRecord.md)

### dnsDeleteRecord
```php
/**
 * @param int $recordId
 * @param string $domainName
 */
public function dnsDeleteRecord($recordId, $domainName)
```
Example:
```php
$api->dnsDeleteRecord(16992196,  'example.com'); // Returns an empty array on success
```

### dnsGetRecords
```php
/**
 * @param string $domainName
 */
public function dnsGetRecords($domainName)
```
[Example](doc/dnsGetRecords.md)

### dnsUpdateRecord
```php
/**
 * DnsUpdateRecord
 *
 * Notes:
 * Using type = redirect will create a redirect
 *  - Valid values for redirecttype are 301, 302 & frame. Default: 301
 *  - Forward url is to be entered in content ex. http://www.example.com
 *
 * @param int $recordId
 * @param string $domainName
 * @param string $name
 * @param string $type
 * @param string $content
 * @param int $ttl
 * @param int $prio
 * @param string $redirectType
 */
public function dnsUpdateRecord($recordId, $domainName, $name, $type, $content, $ttl, $prio = null, $redirectType = null)
```
Example:
```php
$api->dnsUpdateRecord(16992196, 'example.com', '*.example.com', 'A', '1.1.1.2', 3600); // Returns an empty array on success
```

### publishDnsSec
```php
/**
 * PublishDnsSec
 *
 * Notes:
 * $flags
 *  - 256 ZSK
 *  - 257 KSK
 * $alg can at the time of writing use one of the following integers:
 *  - 5 RSA/SHA-1
 *  - 7 RSASHA1-NSEC3-SHA1
 *  - 8 RSA/SHA-256
 *  - 10 RSA/SHA-512
 *  - 12 GOST R 34.10-2001
 *  - 13 ECDSA/SHA-256
 *  - 14 ECDSA/SHA-384
 *
 * @param string $domainName
 * @param string $dnsKey
 * @param int $flags
 * @param int $alg
 */
public function publishDnsSec($domainName, $dnsKey, $flags, $alg)
```
Example:
```php
$api->publishDnsSec('example.com', 'c6884357e49fd6b1fdede867c96aafb1', 256, 5); // Returns an empty array on success
```

### unpublishDnsSec
```php
/**
 * @param string $domainName
 */
public function unpublishDnsSec($domainName)
```
Example:
```php
$api->unPublishDnsSec('example.com'); // Returns an empty array on success
```

## Economy
### priceList
```php
/**
 * PriceList
 *
 * Note:
 * Using $print will ignore $limit and $start parameters, WARNING using $print = true will result with a large JSON object, it’s recommended to use $print = true together with $skipRules = true to limit the JSON size.
 *
 * @param bool $print
 * @param bool $skipRules
 * @param string $tldName
 * @param int $priceTypes
 * @param int $limit
 * @param int $start
 */
public function priceList($print = null, $skipRules = null, $tldName = null, $priceTypes = null, $limit = null, $start = null)
```
[Example](doc/priceList.md)

## Miscellaneous
### createLabel
```php
/**
 * @param string $label
 * @param integer $starred
 */
public function createLabel($label, $starred = null)
```
[Example](doc/createLabel.md)

### deleteLabel
```php
/**
 * @param int $labelId
 */
public function deleteLabel($labelId)
```
Example:
```php
$api->deleteLabel(2317); // Returns an empty array on success
```

### getLabels
```php
public function getLabels()
```
[Example](doc/getLabels.md)

### updateLabel
```php
/**
 * UpdateLabel
 *
 * Note:
 * Valid parameter values are: name, starred
 *
 * @param int $labelId
 * @param string $parameter
 * @param string $value
 */
public function updateLabel($labelId, $parameter, $value)
```
Example:
```php
$api->updateLabel(2317, 'name', 'updated label 1'); // Returns an empty array on success
```

## Request
### requestCancellation
```php
/**
 * RequestCancellation
 *
 * Note:
 * Only statuses with cancelable flag are cancelable.
 *
 * @param int $reqId
 */
public function requestCancellation($reqId)
```
[Example](doc/requestCancellation.md)

### requestDetails
```php
/**
 * @param int $reqId
 */
public function requestDetails($reqId)
```
[Example](doc/requestDetails.md)

### requestList
```php
/**
 * @param string $domainName
 * @param string $reqType
 * @param int $limit default: 100, min: 1 max: 1000, invalid values are ignored and default value is used
 * @param int $start default: 0
 */
public function requestList($domainName = null, $reqType = null, $limit = null, $start = null)
```
[Example](doc/requestList.md)

### requestUpdate
```php
/**
 * @param int $reqId
 * @param string $error parameter from error list
 */
public function requestUpdate($reqId, $error)
```
TODO: example


## Contacts
### contactList
```php
/**
 * ContactList
 *
 * Note:
 * Valid $filters keys:
 * firstname, lastname, organization, orgnr, address1, zipcode, city, countrycode, phone, fax, email
 *
 * @param array $filters key-value array with filters
 * @param int $limit default: 100, min: 1 max: 1000, invalid values are ignored and default value is used
 * @param int $start default: 0
 * @param string $searchString is a free text search parameter, use this parameter to search every field. If this parameter is used all other parameters are ignored
 */
public function contactList(array $filters = [], $searchString = null, $limit = null, $start = null)
```
[Example](doc/contactList.md)

### createContact
```php
/**
 * @param string $firstName
 * @param string $lastName
 * @param string $email
 * @param string $orgnr
 * @param string $address1
 * @param string $zipCode
 * @param string $city
 * @param string $countryCode
 * @param string $phone
 * @param string $organization
 * @param string $fax
 */
public function createContact($firstName, $lastName, $email, $orgnr, $address1, $zipCode, $city, $countryCode, $phone, $organization = null, $fax = null)
```
[Example](doc/createContact.md)

### updateContact
```php
/**
 * UpdateContact
 *
 * Notes:
 * Private persons can’t update $firstName or $lastName
 * Organization can’t update $organization
 * No one can update orgnr
 * if value does not exist, value is added else it is updated
 *
 * Possible $fields keys:
 * firstname, lastname, organization, address1, zipcode, city, countrycode, phone, fax, email
 *
 * @param int $contactId
 * @param array $fields
 */
public function updateContact($contactId, array $fields)
```
Example:
```php
$api->updateContact(1234181, [
    'city' => 'New York',
    'countrycode' => 'us'
]); // Returns an empty array on success
```
