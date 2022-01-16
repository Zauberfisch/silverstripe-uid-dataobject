# SilverStripe UID DataObject Extension

Utility/DataExtension for adding an unique ID (UUID or custom UID) to a DataObject.

## Maintainer Contact

* Zauberfisch <code@zauberfisch.at>

## Requirements

* php >=7.4
* silverstripe/framework >=4
* ramsey/uuid >=4

## Installation

* `composer require "zauberfisch/silverstripe-uid-dataobject"`
* rebuild manifest (flush)

## Documentation

### Using UUID "Universally unique identifier" v4 (random)

This will generate unique IDs like `1ee9aa1b-6510-4105-92b9-7171bb2f3089`.

```php
<?php

class MyDataObject extends \SilverStripe\ORM\DataObject {
	private static $extensions = [
		\zauberfisch\UidDataObject\UuidDataObjectExtension::class,
	];
	private static $uuid_version = 4; // optional, default: 4

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeByName('UUID');
        $fields->addFieldsToTab( 'Root.Main', [
            new \SilverStripe\Forms\ReadonlyField('UUID', "Universally unique identifier"),
        ]);
        return $fields;
    }
}
```

### Using UUID "Universally unique identifier" v1 (time & MAC based)

This will generate unique IDs like `123e4567-e89b-12d3-a456-426614174000`.  
NOTE that UUID v1 uses the current time, along with the MAC address (or node) for a network interface on the local machine. This means it is possible to uniquely identify the machine on which this
UUID was created. Keep that in mind when using v1 UUIDs in any user facing context.

```php
<?php

class MyDataObject extends \SilverStripe\ORM\DataObject {
	private static $extensions = [
		\zauberfisch\UidDataObject\UuidDataObjectExtension::class,
	];
	private static $uuid_version = 1;

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeByName('UUID');
        $fields->addFieldsToTab( 'Root.Main', [
            new \SilverStripe\Forms\ReadonlyField('UUID', "Universally unique identifier"),
        ]);
        return $fields;
    }
}
```

### Using custom unique IDs

This will generate unique IDs like `abc-1ee9aa1b`.  
This is useful if want shorter unique IDs or your own optional prefix/suffix. An example usecase is for use in URLSegments like `/job-offers/abc-1ee9aa1b`.

```php
<?php

class MyDataObject extends \SilverStripe\ORM\DataObject {
	private static $extensions = [
		\zauberfisch\UidDataObject\CustomUidDataObjectExtension::class,
	];
		private static $custom_uid_prefix = 'abc-123'; // optional: ""
	private static $custom_uid_suffix = ''; // optional, default: ""
	private static $custom_uid_length = 8; // optional, default: 8

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeByName('UID');
        $fields->addFieldsToTab( 'Root.Main', [
            new \SilverStripe\Forms\ReadonlyField('UID', "My Unique ID"),
        ]);
        return $fields;
    }
}
```
