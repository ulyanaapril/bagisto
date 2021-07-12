<?php

namespace Red\DeliveryPoint\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class DeliveryPointDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('stores_departments as stores')
            ->select('stores.id', 'stores.active', 'stores.type', 'stores.address', 'stores.info');

        $this->addFilter('type', 'stores.type');
        $this->addFilter('stores_id', 'stores.id');

        $this->setQueryBuilder($queryBuilder);
    }

    /**
     * add columns
     */
    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => 'ID',
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'address',
            'label'      => trans('delivery-point::app.address'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'active',
            'label'      => trans('delivery-point::app.active'),
            'type'       => 'boolean',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
            'wrapper'    => function($value) {
                if ($value->active == 1) {
                    return trans('admin::app.datagrid.active');
                } else {
                    return trans('admin::app.datagrid.inactive');
                }
            },
        ]);

    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.deliverypoint.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.deliverypoint.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['name' => trans('delivery-point::app.delivery-point')]),
            'icon'         => 'icon trash-icon',
            'function'     => 'deleteFunction($event, "delete")'
        ]);

        $this->addMassAction([
            'type'   => 'delete',
            'label'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.deliverypoint.massdelete'),
            'method' => 'POST',
        ]);
    }
}