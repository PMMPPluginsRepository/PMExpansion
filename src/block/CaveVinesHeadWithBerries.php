<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\data\bedrock\block\BlockStateNames;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\item\ExtraVanillaItems;
use skh6075\pmexpansion\world\sound\block\CaveVinesPickBerriesSound;

class CaveVinesHeadWithBerries extends BaseCaveVines{
	use BlockTypeIdTrait;

	public function getStateSerialize() : ?Closure{
		return static fn(CaveVinesHeadWithBerries $block) : BlockStateWriter => BlockStateWriter::create(BlockTypeNames::CAVE_VINES_HEAD_WITH_BERRIES)
			->writeInt(BlockStateNames::GROWING_PLANT_AGE, $block->getAge());
	}

	public function getStateDeserialize() : ?Closure{
		return static fn(BlockStateReader $in) : CaveVinesHeadWithBerries => ExtraVanillaBlocks::CAVE_VINES_HEAD_WITH_BERRIES()
			->setAge($in->readBoundedInt(BlockStateNames::GROWING_PLANT_AGE, 0, self::MAX_AGE));
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($player !== null){
			$world = $this->position->world;
			$world->dropItem($this->position->add(0.5, 0.5, 0.5), ExtraVanillaItems::GLOW_BERRIES());
			$world->addSound($this->position, new CaveVinesPickBerriesSound());
			$world->setBlock($this->position, ExtraVanillaBlocks::CAVE_VINES());

			return true;
		}

		return false;
	}

	public function getLightLevel() : int{ return 14; }

	public function getDropsForCompatibleTool(Item $item) : array{ return [ExtraVanillaItems::GLOW_BERRIES()]; }

	public function getDrops(Item $item) : array{ return [$this->asItem()]; }
}