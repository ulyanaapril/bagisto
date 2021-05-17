<?php

namespace Red\Admin\Repositories;

use Illuminate\Support\Facades\Event;

class CategoryRepository extends \Webkul\Category\Repositories\CategoryRepository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Red\Admin\Contracts\Category';
    }

    /**
     * @param array $data
     * @return \Webkul\Category\Contracts\Category
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function create(array $data)
    {
        Event::dispatch('catalog.category.create.before');

        if (isset($data['locale']) && $data['locale'] == 'all') {
            $model = app()->make($this->model());

            foreach (core()->getAllLocales() as $locale) {
                foreach ($model->translatedAttributes as $attribute) {
                    if (isset($data[$attribute])) {
                        $data[$locale->code][$attribute] = $data[$attribute];
                        $data[$locale->code]['locale_id'] = $locale->id;
                    }
                }
            }
        }

        if (!empty($data['locales'])) {
            $model = app()->make($this->model());
            $locales = core()->getAllLocales();

            foreach ($locales as $locale) {
                if (isset($data['locales'][$locale->code])) {
                    foreach ($model->translatedAttributes as $attribute) {
                        if (isset($data['locales'][$locale->code][$attribute])) {
                            $data[$locale->code][$attribute] = $data['locales'][$locale->code][$attribute];
                            $data[$locale->code]['locale_id'] = $locale->id;
                        }
                    }
                }
            }
        }

        $category = $this->model->create($data);

        $this->uploadImages($data, $category);

        if (isset($data['attributes'])) {
            $category->filterableAttributes()->sync($data['attributes']);
        }

        Event::dispatch('catalog.category.create.after', $category);

        return $category;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Red\Admin\Contracts\Category
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $category = $this->find($id);

        Event::dispatch('catalog.category.update.before', $id);

        $category->update($data);

        $this->uploadImages($data, $category);

        if (isset($data['attributes'])) {
            $category->filterableAttributes()->sync($data['attributes']);
        }

        Event::dispatch('catalog.category.update.after', $id);

        return $category;
    }

}