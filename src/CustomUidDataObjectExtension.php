<?php

declare(strict_types=1);

namespace zauberfisch\UidDataObject;

use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBVarchar;

class CustomUidDataObjectExtension extends DataExtension {
	private static $db = [
		'UID' => DBVarchar::class,
	];
	private static $indexes = [
		"UID" => true,
	];
	private static $custom_uid_prefix = '';
	private static $custom_uid_suffix = '';
	private static $custom_uid_length = 8;

	public function onBeforeWrite() {
		parent::onBeforeWrite();
		if (!$this->owner->UID) {
			$prefix = $this->owner->config()->uninherited('custom_uid_prefix');
			$length = $this->owner->config()->uninherited('custom_uid_length');
			$suffix = $this->owner->config()->uninherited('custom_uid_suffix');
			do {
				$hash = '';
				while (strlen($hash) < $length) {
					$hash .= md5(uniqid("", true));
				}
				$uid = $prefix . substr($hash, 0, $length) . $suffix;
			} while ($this->owner->get()->filter('UID', $uid)->exists());
			$this->owner->UID = $uid;
		}
	}

	public function onBeforeDuplicate() {
		$this->owner->UID = "";
	}
}
