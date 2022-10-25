<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\item;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier as IID;
use pocketmine\utils\CloningRegistryTrait;

/**
 * @method static IceBomb ICE_BOMB()
 */

final class ExtraVanillaItems{
	use CloningRegistryTrait;

	protected static function register(string $name, Item $item): void{
		self::_registryRegister($name, $item);
	}

	/**
	 * @return Item[]
	 * @phpstan-return array<string, Item>
	 */
	public static function getAll(): array{
		return self::_registryGetAll();
	}

	protected static function setup() : void{
		self::register('ice_bomb', new IceBomb(new IID(IceBomb::getFixedTypeId()), "Ice Bomb"));
	}
}