<?php

namespace Spatie\LaravelSettings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Responsable;
use Spatie\LaravelSettings\Traits\SettingsTrait;
use Serializable;
use Spatie\LaravelSettings\Interfaces\Settings;

abstract class SettingsEloquent extends Model implements Arrayable, Jsonable, Responsable, Serializable, Settings
{
    use SettingsTrait;


    abstract public static function group(): string;

    public function toArray(): array
    {
        return $this->toCollection()->toArray();
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function toResponse($request)
    {
        return response()->json($this->toJson());
    }

    public function serialize(): string
    {
        return serialize($this->toArray());
    }

    public function unserialize($serialized): void
    {
        $values = unserialize($serialized);

        $this->loaded = false;
        $this->loadValues($values);
    }

    public function __get($name)
    {
        $this->loadValues();

        // return $this->$name;
        return $this->getAttribute($name);
    }
}
