<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use Closure;
use pocketmine\block\Opaque;
use pocketmine\data\bedrock\block\BlockStateNames;
use pocketmine\data\bedrock\block\BlockTypeNames;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\data\runtime\RuntimeDataReader;
use pocketmine\data\runtime\RuntimeDataWriter;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use skh6075\pmexpansion\block\utils\BlockIngredientsHelper;
use skh6075\pmexpansion\block\utils\BlockTypeIdTrait;
use skh6075\pmexpansion\block\utils\IBlockState;
use skh6075\pmexpansion\world\particle\BoneMealParticle;
use skh6075\pmexpansion\world\sound\ComposterFillSound;
use skh6075\pmexpansion\world\sound\ComposterFillSuccessSound;
use skh6075\pmexpansion\world\sound\ComposterReadySound;

class Composter extends Opaque implements IBlockState{
	use BlockTypeIdTrait;

	public const FULL_LEVEL = 0x08;
	public const READY_LEVEL = 0x07;

	private int $fill_level = 0;

	public function getStateSerialize() : ?Closure{
		return static fn(Composter $block): BlockStateWriter => BlockStateWriter::create(BlockTypeNames::COMPOSTER)
			->writeInt(BlockStateNames::COMPOSTER_FILL_LEVEL, $block->fill_level);
	}

	public function getStateDeserialize() : ?Closure{
		return static fn(BlockStateReader $in): Composter => ExtraVanillaBlocks::COMPOSTER()
			->setFillLevel($in->readInt(BlockStateNames::COMPOSTER_FILL_LEVEL));
	}

	public function getRequiredStateDataBits(): int{ return 4; }

	protected function describeState(RuntimeDataReader|RuntimeDataWriter $w) : void{
		$w->int(4, $this->fill_level);
	}

	public function getFillLevel(): int{ return $this->fill_level; }

	public function setFillLevel(int $state): self{
		$this->fill_level = $state;
		return $this;
	}

	public function getFuelTime() : int{ return 300; }

	public function isFullFilled(): bool{ return $this->fill_level >= self::FULL_LEVEL; }

	private function canPutRecycledFuel(): bool{
		if($this->fill_level >= self::READY_LEVEL){
			return false;
		}

		if(++$this->fill_level >= self::READY_LEVEL){
			$this->position->getWorld()->scheduleDelayedBlockUpdate($this->position, 20);
		}else{
			$this->position->getWorld()->setBlock($this->position, $this);
		}

		$this->position->getWorld()->addSound($this->position, new ComposterFillSuccessSound());
		return true;
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($player === null){
			return false;
		}

		if($this->isFullFilled()){
			$this->fill_level = 0;
			$this->position->getWorld()->setBlock($this->position, $this);
			$this->position->getWorld()->addSound($this->position, new ComposterReadySound());
			$this->position->getWorld()->dropItem($this->position->add(0.5, 1.1, 0.5), VanillaItems::BONE_MEAL());
		}elseif($this->fill_level < self::READY_LEVEL && ($chance = BlockIngredientsHelper::composter($item)) > 0){
			$item->pop();
			$this->position->world->addParticle($this->position, new BoneMealParticle());
			if($this->fill_level > 0 && random_int(0, 100) <= $chance){
				$this->canPutRecycledFuel();
			}elseif($this->fill_level === 0){
				$this->canPutRecycledFuel();
			}

			$this->position->getWorld()->addSound($this->position, new ComposterFillSound());
		}

		return true;
	}

	public function onScheduledUpdate() : void{
		if($this->fill_level !== self::READY_LEVEL){
			return;
		}

		++$this->fill_level;
		$this->position->getWorld()->setBlock($this->position, $this);
		$this->position->getWorld()->addSound($this->position, new ComposterReadySound());
	}
}