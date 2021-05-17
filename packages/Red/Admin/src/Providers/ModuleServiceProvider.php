<?php

namespace Red\Admin\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Red\Admin\Models\Category::class,
        \Red\Admin\Models\CategoryTranslation::class,
        \Red\Admin\Models\AttributeOption::class,
    ];
}