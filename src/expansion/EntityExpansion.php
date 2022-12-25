<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\expansion;

use Closure;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\World;
use skh6075\pmexpansion\entity\projectile\IceBomb;
use function is_array;

final class EntityExpansion implements IExpansion{
	public static function synchronize() : void{
		self::register(IceBomb::class, function(World $world, CompoundTag $nbt): IceBomb{
			return new IceBomb(EntityDataHelper::parseLocation($nbt, $world), null, $nbt);
		}, ["IceBomb", EntityIds::ICE_BOMB]);
	}

	private static function register(
		string $entityClass,
		Closure $closure,
		string|array $savedEntityNames,
		?int $legacyEntityId = null
	): void{
		if(!is_array($savedEntityNames)){
			$savedEntityNames = [$savedEntityNames];
		}
		EntityFactory::getInstance()->register($entityClass, $closure, $savedEntityNames, $legacyEntityId);
	}
}
