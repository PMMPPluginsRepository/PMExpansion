<?php

declare(strict_types=1);

namespace skh6075\pmexpansion\block\utils;

use Closure;

interface IBlockState{

	public function getStateSerialize(): ?Closure;

	public function getStateDeserialize(): ?Closure;
}