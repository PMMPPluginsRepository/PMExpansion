<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\expansion;

use Closure;
use InvalidArgumentException;
use pocketmine\data\bedrock\item\ItemDeserializer;
use pocketmine\data\bedrock\item\ItemSerializer;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\network\mcpe\convert\GlobalItemTypeDictionary;
use pocketmine\network\mcpe\protocol\serializer\ItemTypeDictionary;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\world\format\io\GlobalItemDataHandlers;
use skh6075\pmexpansion\item\ExtraVanillaItems;
use Symfony\Component\Filesystem\Path;
use const pocketmine\BEDROCK_DATA_PATH;

final class ItemExpansion implements IExpansion{
	public static function synchronize() : void{
		foreach(ExtraVanillaItems::getAll() as $item){
			self::register($item);
		}
	}

	private static function register(
		Item $item,
		?int $runtimeId = null,
		?Closure $serialize = null,
		?Closure $deserialize = null
	): void{
		$namespace = self::reprocess($item->getName());

		$runtimeId ??= self::getRuntimeIdByName($namespace);
		$serialize ??= static fn() => new SavedItemData($namespace);
		$deserialize ??= static fn(SavedItemData $_) => clone $item;

		Closure::bind(
			closure: fn(ItemSerializer $serializer) => $serializer->itemSerializers[$item->getTypeId()][get_class($item)] = $serialize,
			newThis: null,
			newScope: ItemSerializer::class
		)(GlobalItemDataHandlers::getSerializer());

		Closure::bind(
			closure: fn(ItemDeserializer $deserializer) => $deserializer->deserializers[$namespace] = $deserialize,
			newThis: null,
			newScope: ItemDeserializer::class
		)(GlobalItemDataHandlers::getDeserializer());

		Closure::bind(
			closure: function(ItemTypeDictionary $dictionary) use ($item, $runtimeId, $namespace): void{
				$dictionary->stringToIntMap[$namespace] = $runtimeId;
				$dictionary->intToStringIdMap[$runtimeId] = $namespace;
				$dictionary->itemTypes[] = new ItemTypeEntry($namespace, $runtimeId, true);
			},
			newThis: null,
			newScope: ItemTypeDictionary::class
		)(GlobalItemTypeDictionary::getInstance()->getDictionary());

		StringToItemParser::getInstance()->override($namespace, fn() => clone $item);
	}

	private static function reprocess(string $name): string{
		return "minecraft:" . strtolower(str_replace(' ', '_', $name));
	}

	private static function getRuntimeIdByName(string $name): int{
		static $mappedJson = [];
		if($mappedJson === []){
			$mappedJson = self::reprocessKeys(json_decode(file_get_contents(Path::join(BEDROCK_DATA_PATH, "required_item_list.json")), true));
		}
		return $mappedJson[$name]["runtime_id"] ?? throw new InvalidArgumentException("Not Found $name Runtime Id");
	}

	private static function reprocessKeys(array $data) : array{
		$new = [];
		foreach($data as $key => $value){
			$new[$key] = $value;
		}
		return $new;
	}
}