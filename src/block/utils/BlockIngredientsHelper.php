<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block\utils;

use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\item\ItemTypeNames;
use pocketmine\item\Item;
use pocketmine\world\format\io\GlobalItemDataHandlers;

final class BlockIngredientsHelper{
	/**
	 * Composter block collects recyclable items
	 *
	 * @var int[]
	 * @phpstan-var array<string, int> namespace => chance
	 */
	private static array $composterIngredients;

	public static function composter(Item $item): int{
		if(empty(self::$composterIngredients)){
			self::$composterIngredients = [
				//30%
				ItemTypeNames::BEETROOT_SEEDS => 30,
				ItemTypeNames::DRIED_KELP => 30,
				ItemTypeNames::GLOW_BERRIES => 30,
//				BlockTypeNames::TALLGRASS => 30, //small grass
				BlockTypeNames::GRASS => 30, //Only Bedrock Edition
				BlockTypeNames::HANGING_ROOTS => 30,
				BlockTypeNames::MANGROVE_ROOTS => 30,
				ItemTypeNames::KELP => 30,
				BlockTypeNames::LEAVES => 30,
				ItemTypeNames::MELON_SEEDS => 30,
				ItemTypeNames::PUMPKIN_SEEDS => 30,
				BlockTypeNames::SAPLING => 30,
				BlockTypeNames::SEAGRASS => 30,
				BlockTypeNames::SMALL_DRIPLEAF_BLOCK => 30,
				ItemTypeNames::SWEET_BERRIES => 30,
				ItemTypeNames::WHEAT_SEEDS => 30,

				//50%
				BlockTypeNames::CACTUS => 50,
				BlockTypeNames::DRIED_KELP_BLOCK => 50,
				BlockTypeNames::AZALEA_LEAVES_FLOWERED => 50,
				BlockTypeNames::GLOW_LICHEN => 50,
				ItemTypeNames::MELON_SLICE => 50,
				BlockTypeNames::NETHER_SPROUTS => 50,
				ItemTypeNames::SUGAR_CANE => 50,
//				BlockTypeNames::DOUBLE_PLANT => 50, //tall grass
				BlockTypeNames::TWISTING_VINES => 50,
				BlockTypeNames::VINE => 50,
				BlockTypeNames::WEEPING_VINES => 50,

				//65%
				ItemTypeNames::APPLE => 65,
				BlockTypeNames::AZALEA => 65,
				ItemTypeNames::BEETROOT => 65,
				BlockTypeNames::BIG_DRIPLEAF => 65,
				ItemTypeNames::CARROT => 65,
				ItemTypeNames::COCOA_BEANS => 65,
//				TODO.. ferns
//				TODO.. flowers
//				TODO.. fungus
				BlockTypeNames::WATERLILY => 65,
				BlockTypeNames::MELON_BLOCK => 65,
				BlockTypeNames::MOSS_BLOCK => 65,
				BlockTypeNames::BROWN_MUSHROOM => 65,
				BlockTypeNames::RED_MUSHROOM => 65,
//				TODO.. mushroom steam
				ItemTypeNames::NETHER_WART => 65,
				ItemTypeNames::POTATO => 65,
				BlockTypeNames::PUMPKIN => 65,
				BlockTypeNames::CARVED_PUMPKIN => 65,
				BlockTypeNames::CRIMSON_ROOTS => 65,
				BlockTypeNames::WARPED_ROOTS => 65,
				BlockTypeNames::SEA_PICKLE => 65,
				BlockTypeNames::SHROOMLIGHT => 65,
				BlockTypeNames::SPORE_BLOSSOM => 65,
				ItemTypeNames::WHEAT => 65,

				//85%
				ItemTypeNames::BAKED_POTATO => 85,
				ItemTypeNames::BREAD => 85,
				ItemTypeNames::COOKIE => 85,
				BlockTypeNames::FLOWERING_AZALEA => 85,
				BlockTypeNames::HAY_BLOCK => 85,
//				TODO.. mushroom blocks
				BlockTypeNames::NETHER_WART_BLOCK => 85,
				BlockTypeNames::WARPED_WART_BLOCK => 85,

				//100%
				BlockTypeNames::CAKE => 100,
				ItemTypeNames::PUMPKIN_PIE => 100
			];
		}

		return self::$composterIngredients[GlobalItemDataHandlers::getSerializer()->serializeType($item)->getName()] ?? -1;
	}
}