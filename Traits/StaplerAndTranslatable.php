<?php

namespace Modules\Admin\Traits;

trait StaplerAndTranslatable
{
    /**
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        // Translatable
        if (str_contains($key, ':')) {
            list($key, $locale) = explode(':', $key);
        } else {
            $locale = $this->locale();
        }

        if ($this->isTranslationAttribute($key)) {
            if ($this->getTranslation($locale) === null) {
                return;
            }

            return $this->getTranslation($locale)->$key;
        }

        // Stapler. EloquentTrait.
        if (array_key_exists($key, $this->attachedFiles)) {
            return $this->attachedFiles[$key];
        }

        return parent::getAttribute($key);
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setAttribute($key, $value)
    {
        // Stapler. EloquentTrait.
        if (array_key_exists($key, $this->attachedFiles)) {
            if ($value) {
                $attachedFile = $this->attachedFiles[$key];
                $attachedFile->setUploadedFile($value);
            }

            return;
        }

        // Translatable
        if (str_contains($key, ':')) {
            list($key, $locale) = explode(':', $key);
        } else {
            $locale = $this->locale();
        }

        if ($this->isTranslationAttribute($key)) {
            $this->getTranslationOrNew($locale)->$key = $value;
        } else {
            return parent::setAttribute($key, $value);
        }
    }

}
