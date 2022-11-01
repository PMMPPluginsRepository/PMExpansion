<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block;

use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier as BID;
use pocketmine\block\BlockToolType;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo as Info;
use pocketmine\item\Item;
use pocketmine\item\ToolTier;
use pocketmine\utils\CloningRegistryTrait;

/**
 * @method static Azalea AZALEA()
 * @method static AzaleaLeaves AZALEA_LEAVES()
 * @method static AzaleaLeavesFlowered AZALEA_LEAVES_FLOWERED()
 * @method static FloweringAzalea FLOWERING_AZALEA()
 * @method static Target TARGET()
 * @method static Sculk SCULK()
 * @method static SculkCatalyst SCULK_CATALYST()
 * @method static SculkSensor SCULK_SENSOR()
 * @method static SculkShrieker SCULK_SHRIEKER()
 * @method static EndPortalFrame END_PORTAL_FRAME()
 * @method static EndPortal END_PORTAL()
 * @method static EndGateway END_GATEWAY()
 * @method static Moss MOSS()
 * @method static MossCarpet MOSS_CARPET()
 * @method static Border BORDER()
 * @method static Allow ALLOW()
 * @method static Deny DENY()
 */

final class ExtraVanillaBlocks{
	use CloningRegistryTrait;

	protected static function register(string $name, Block $block): void{
		self::_registryRegister($name, $block);
	}

	/**
	 * @return Block[]
	 * @phpstan-return array<string, Block>
	 */
	public static function getAll(): array{
		return self::_registryGetAll();
	}

	protected static function setup() : void{
		$instantBlockInfo = new Info(BlockBreakInfo::instant());
		$indestructibleInfo = new Info(BlockBreakInfo::indestructible());

		$leavesBreakInfo = new Info(new class(0.2, BlockToolType::HOE) extends BlockBreakInfo{
			public function getBreakTime(Item $item) : float{
				if($item->getBlockToolType() === BlockToolType::SHEARS){
					return 0.0;
				}
				return parent::getBreakTime($item);
			}
		});
		self::register('azalea', new Azalea(new BID(Azalea::getFixedTypeId()), 'Azalea', $instantBlockInfo));
		self::register('azalea_leaves', new AzaleaLeaves(new BID(AzaleaLeaves::getFixedTypeId()), 'Azalea Leaves', $leavesBreakInfo));
		self::register('azalea_leaves_flowered', new AzaleaLeavesFlowered(new BID(AzaleaLeavesFlowered::getFixedTypeId()), 'Azalea Leaves Flowered', $leavesBreakInfo));
		self::register('flowering_azalea', new FloweringAzalea(new BID(FloweringAzalea::getFixedTypeId()), 'Flowering Azalea', $instantBlockInfo));

		$sculkInfo = new Info(BlockBreakInfo::tier(3.0, BlockToolType::HOE, ToolTier::WOOD()));
		self::register('sculk', new Sculk(new BID(Sculk::getFixedTypeId()), 'Sculk', $sculkInfo));
		self::register('sculk_catalyst', new SculkCatalyst(new BID(SculkCatalyst::getFixedTypeId()), 'Sculk Catalyst', $sculkInfo));
		self::register('sculk_sensor', new SculkSensor(new BID(SculkSensor::getFixedTypeId()), 'Sculk Sensor', $sculkInfo));
		self::register('sculk_shrieker', new SculkShrieker(new BID(SculkShrieker::getFixedTypeId()), 'Sculk Shrieker', $sculkInfo));

		self::register('end_portal_frame', new EndPortalFrame(new BID(BlockTypeIds::END_PORTAL_FRAME), "End Portal Frame", $indestructibleInfo));
		self::register('end_portal', new EndPortal(new BID(EndPortal::getFixedTypeId()), 'End Portal', $indestructibleInfo));
		self::register('end_gateway', new EndGateway(new BID(EndGateway::getFixedTypeId()), 'End Gateway', $indestructibleInfo));

		self::register('target', new Target(new BID(Target::getFixedTypeId()), 'Target', $instantBlockInfo));

		self::register('moss', new Moss(new BID(Moss::getFixedTypeId()), 'Moss Block', $instantBlockInfo));
		self::register('moss_carpet', new MossCarpet(new BID(MossCarpet::getFixedTypeId()), 'Moss Carpet', $instantBlockInfo));

		self::register('border', new Border(new BID(Border::getFixedTypeId()), 'Border Block', $indestructibleInfo));
		self::register('allow', new Allow(new BID(Allow::getFixedTypeId()), 'Allow', $indestructibleInfo));
		self::register('deny', new Deny(new BID(Deny::getFixedTypeId()), 'Deny', $indestructibleInfo));
	}
}