<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block\utils;

use pocketmine\block\BlockTypeIds;

trait BlockTypeIdTrait{
	private static ?int $fixedTypeId = null;

	public static function getFixedTypeId(): int{
		return self::$fixedTypeId ??= BlockTypeIds::newId();
	}
}