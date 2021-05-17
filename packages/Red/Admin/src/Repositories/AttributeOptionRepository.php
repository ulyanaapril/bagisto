<?php

namespace Red\Admin\Repositories;

use Webkul\Attribute\Repositories\AttributeOptionRepository as AttributeOptionRepositoryBase;

class AttributeOptionRepository extends AttributeOptionRepositoryBase
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Red\Admin\Contracts\AttributeOption';
    }

}