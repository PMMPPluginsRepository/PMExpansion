<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\item;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier as IID;
use pocketmine\utils\CloningRegistryTrait;

/**
 * @method static IceBomb ICE_BOMB()
 * @method static EnderEye ENDER_EYE()
 * @method static GlowBerries GLOW_BERRIES()
 * @method static Kelp KELP()
 * @method static Chain CHAIN()
 * @method static Camera CAMERA()
 * @method static Campfire CAMPFIRE()
 * @method static SoulCampfire SOUL_CAMPFIRE()
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
		self::register('ender_eye', new EnderEye(new IID(EnderEye::getFixedTypeId()), "Ender Eye"));
		self::register('glow_berries', new GlowBerries(new IID(GlowBerries::getFixedTypeId()), "Glow Berries"));
		self::register('kelp', new Kelp(new IID(Kelp::getFixedTypeId()), "Kelp"));
		self::register('chain', new Chain(new IID(Chain::getFixedTypeId()), 'Chain'));
		self::register('camera', new Camera(new IID(Camera::getFixedTypeId()), 'Camera'));
		self::register('campfire', new Campfire(new IID(Campfire::getFixedTypeId()), 'Campfire'));
		self::register('soul_campfire', new SoulCampfire(new IID(SoulCampfire::getFixedTypeId()), 'Soul Campfire'));
	}
}