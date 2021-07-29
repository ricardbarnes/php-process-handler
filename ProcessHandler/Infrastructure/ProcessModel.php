<?php

namespace Core\Shared\Libs\ProcessHandler\Infrastructure;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * @method static where(string $FIELD_CLASS_NAME, string $className)
 * @method static create(array $array)
 */
class ProcessModel extends Model
{
    const FIELD_CLASS = 'class_name';
    const FIELD_IS_RUNNING = 'is_running';
    const FIELD_UPDATED_AT = 'updated_at';
    const FIELD_CREATED_AT = 'created_at';

    protected $table = 'process_monitor';
    protected $guarded = [];
}
