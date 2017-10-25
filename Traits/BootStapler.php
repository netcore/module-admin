<?php

namespace Modules\Admin\Traits;

trait BootStapler
{

    /**
     * BootStapler constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        if ($this->staplerConfig) {
            foreach ($this->staplerConfig as $name => $config) {
                $this->hasAttachedFile($name, $config);
            }
        }

        parent::__construct($attributes);
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        // Call the bootStapler() method to register stapler as an observer for this model.
        static::bootStapler();

        // Now, before the record is saved, set the filename attribute on the model:
        static::saving(function ($model) {
            foreach ($model->staplerConfig as $name => $config) {
                if ($model->isDirty($name . '_file_name') && !is_null($model->{$name . '_file_name'})) {
                    $pathInfo = pathinfo($model->{$name}->originalFileName());

                    if (isset($pathInfo['extension'])) {
                        $newFilename = time() . '.' . $pathInfo['extension'];

                        $model->{$name}->instanceWrite('file_name', $newFilename);
                    }
                }
            }
        });
    }

    /**
     * Overridden to prevent attempts to persist attachment attributes directly.
     *
     * Reason this is required: Laravel 5.5 changed the getDirty() behavior.
     *
     * {@inheritdoc}
     */
    protected function originalIsEquivalent($key, $current)
    {
        if (array_key_exists($key, $this->attachedFiles)) {
            return true;
        }

        return parent::originalIsEquivalent($key, $current);
    }
}
