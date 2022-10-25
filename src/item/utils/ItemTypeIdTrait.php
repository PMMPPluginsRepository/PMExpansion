<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\item\utils;

use pocketmine\item\ItemTypeIds;

trait ItemTypeIdTrait{
	private static ?int $fixedTypeId = null;

	public static function getFixedTypeId(): int{
		return self::$fixedTypeId ??= ItemTypeIds::newId();
	}
}