<?php

namespace Modules\Admin\Traits;

trait SyncTranslations
{

    /**
     * Store translations
     *
     * @param array $values
     */
    public function storeTranslations($values)
    {
        $array = [];

        foreach ($values as $lang => $data) {
            $newElement = [
                'locale' => $lang
            ];

            foreach ($this->translatedAttributes as $attribute) {
                if (isset($values[$lang][$attribute])) {
                    $newElement[$attribute] = $values[$lang][$attribute];
                }
            }

            $array[] = $newElement;
        }

        $this->translations()->createMany($array);
    }

    /**
     * Update translations
     *
     * @param array $values
     */
    public function updateTranslations(array $values)
    {
        $array = [];

        foreach ($values as $lang => $data) {
            $array['locale'] = $lang;

            foreach ($this->translatedAttributes as $attribute) {
                if (isset($values[$lang][$attribute])) {
                    $array[$attribute] = $values[$lang][$attribute];
                }
            }

            $translation = $this->translations()->where('locale', $lang)->first();

            if (!$translation) {
                $translation = $this->translations()->create($array);
            } else {
                $translation->fill($array);
                $translation->save();
            }
        }
    }

}
