<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\event\block\BlockGrowEvent;
use pocketmine\item\Fertilizer;
use pocketmine\item\Item;
use pocketmine\math\Axis;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\SpawnParticleEffectPacket;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;

class CaveVines extends BaseCaveVines{
	use BlockTypeIdTrait;

	public function getStateSerialize() : ?Closure{
		return static fn(CaveVines $block) : BlockStateWriter => BlockStateWriter::create(BlockTypeNames::CAVE_VINES)
			->writeInt(BlockStateNames::GROWING_PLANT_AGE, $block->getAge());
	}

	public function getStateDeserialize() : ?Closure{
		return static fn(BlockStateReader $in) : CaveVines => ExtraVanillaBlocks::CAVE_VINES()
			->setAge($in->readBoundedInt(BlockStateNames::GROWING_PLANT_AGE, 0, self::MAX_AGE));
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if(Facing::axis($face) === Axis::Y){
			return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
		}

		return false;
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($item instanceof Fertilizer){
			$this->grow();
			$this->position->world->broadcastPacketToViewers($this->position, SpawnParticleEffectPacket::create(
				dimensionId: DimensionIds::OVERWORLD,
				actorUniqueId: -1,
				position: $this->position->add(0.5, 0.5, 0.5),
				particleName: "minecraft:crop_growth_emitter",
				molangVariablesJson: ""
			));
			$item->pop();

			return true;
		}

		return false;
	}

	public function ticksRandomly() : bool{ return true; }

	public function onRandomTick() : void{
		if($this->age === self::MAX_AGE){
			$this->grow();
		}else{
			++$this->age;
			$this->position->world->setBlock($this->position, $this);
		}
	}

	private function grow(): void{
		$newState = ExtraVanillaBlocks::CAVE_VINES_BODY_WITH_BERRIES();
		if($this->isVinesHead()){
			$newState = ExtraVanillaBlocks::CAVE_VINES_HEAD_WITH_BERRIES();
		}

		$ev = new BlockGrowEvent($this, $newState);
		$ev->call();
		if(!$ev->isCancelled()){
			$this->position->world->setBlock($this->position, $ev->getNewState());
		}
	}

	private function isVinesHead(): bool{
		$down = $this->getSide(Facing::DOWN);
		return !$down->isSameType($this)
			&& !$down->isSameType(ExtraVanillaBlocks::CAVE_VINES_HEAD_WITH_BERRIES());
	}
}