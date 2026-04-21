<?php

namespace App\Enums\Traits;

use App\Enums\Attributes\Label;
use BackedEnum;
use ReflectionAttribute;
use ReflectionClassConstant;

/** @mixin BackedEnum */
trait EnumTrait
{
    public static function options()
    {
        return array_map(
            fn (BackedEnum $enum) => $enum->serialize(),
            static::cases()
        );
    }

    public function serialize(): array
    {
        return [
            'label' => $this->label(),
            'name' => $this->name,
            'value' => $this->value,
        ];
    }

    public function label()
    {
        $attributes = new ReflectionClassConstant(static::class, $this->name)->getAttributes(Label::class);

        if (count($attributes) > 0) {
            /** @var ReflectionAttribute $attribute */
            $attribute = collect($attributes)->random();
            $instance = $attribute->newInstance();

            return $instance->label;
        }

        return null;
    }
}
