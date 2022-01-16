<?php

declare(strict_types=1);

namespace zauberfisch\UidDataObject;

use Ramsey\Uuid\Uuid;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBVarchar;

class UuidDataObjectExtension extends DataExtension {
	private static $uuid_version = 4;
	private static $db = [
		'UUID' => DBVarchar::class . '(36)',
	];
	private static $indexes = [
		"UUID" => true,
	];

	public function onBeforeWrite() {
		parent::onBeforeWrite();
		if (!$this->owner->UUID) {
			$version = (int)$this->owner->config()->get('uuid_version');
			if ($version === 1) {
				$uuid = Uuid::uuid1()->toString();
			} else if ($version === 4) {
				$uuid = Uuid::uuid4()->toString();
			} else {
				throw new \Exception("UUID Version $version not supported");
			}
			if ($this->owner->get()->filter('UUID', $uuid)->exists()) {
				throw new \Exception("UUID collision for '$uuid'");
			}
			$this->owner->UUID = $uuid;
		}
	}

	public function onBeforeDuplicate() {
		$this->owner->UUID = "";
	}
}
