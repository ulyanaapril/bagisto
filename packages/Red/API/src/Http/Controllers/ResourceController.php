<?php

namespace Red\API\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Red\Admin\Http\GoogleTranslation;
use Red\Admin\Models\AttributeOption;
use Red\Admin\Models\Category;
use Red\Admin\Models\Order;
use Red\Admin\Repositories\AttributeOptionRepository;
use Red\Admin\Repositories\CategoryRepository;
use Red\Admin\Repositories\OrderRepository;
use Throwable;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Core\Contracts\Validations\Slug;
use Webkul\Core\Tree;
use Webkul\Inventory\Repositories\InventorySourceRepository;
use Webkul\Product\Models\Product;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\ShipmentRepository;

class ResourceController extends Controller
{
    /**
     * Contains current guard
     *
     * @var array
     */
    protected $guard;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Repository object
     *
     * @var \Webkul\Core\Eloquent\Repository
     */
    protected $repository;

    /**
     * @var
     */
    protected $img;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * @var AttributeOptionRepository
     */
    protected $attributeOptionRepository;

    /**
     * @var InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * ShipmentRepository object
     *
     * @var \Webkul\Sales\Repositories\ShipmentRepository
     */
    protected $shipmentRepository;

    /**
     * OrderItemRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderItemRepository
     */
    protected $orderItemRepository;

    /**
     * @var array
     */
    protected $permutation = [
        23 => 1,
        24 => 6
    ];

    /**
     * @var GoogleTranslation
     */
    protected $trans;

    /**
     * @var
     */
    protected $inventorySourceRepository;

    /**
     * @var
     */
    protected $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @param CategoryRepository $categoryRepository
     * @param AttributeRepository $attribute
     * @param AttributeOptionRepository $attributeOption
     * @param GoogleTranslation $trans
     * @param OrderRepository $orderRepository
     * @param InventorySourceRepository $inventorySourceRepository
     * @param InvoiceRepository $invoiceRepository
     * @param ShipmentRepository $shipmentRepository
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        AttributeRepository $attribute,
        AttributeOptionRepository $attributeOption,
        GoogleTranslation $trans,
        OrderRepository $orderRepository,
        InventorySourceRepository $inventorySourceRepository,
        InvoiceRepository $invoiceRepository,
        OrderItemRepository $orderItemRepository,
        ShipmentRepository $shipmentRepository
    )
    {
        $this->guard = request()->has('token') ? 'admin-api' : 'admin';

        $this->_config = request('_config');

        $this->categoryRepository = $categoryRepository;
        $this->attributeRepository = $attribute;
        $this->attributeOptionRepository = $attributeOption;
        $this->orderRepository = $orderRepository;
        $this->trans = $trans;
        $this->inventorySourceRepository = $inventorySourceRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->shipmentRepository = $shipmentRepository;
        $this->orderItemRepository = $orderItemRepository;

        if (isset($this->_config['authorization_required']) && $this->_config['authorization_required']) {

            auth()->setDefaultDriver($this->guard);

            $this->middleware('auth:' . $this->guard);
        }

        if ($this->_config) {
            $this->repository = app($this->_config['repository']);
        }
    }

    /**
     * Returns a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = $this->repository->scopeQuery(function($query) {
            if (isset($this->_config['authorization_required']) && $this->_config['authorization_required']) {
//                $query = $query->where('customer_id', auth()->user()->id );
            }

            foreach (request()->except(['page', 'limit', 'pagination', 'sort', 'order', 'token']) as $input => $value) {
                $query = $query->whereIn($input, array_map('trim', explode(',', $value)));
            }

            if ($sort = request()->input('sort')) {
                $query = $query->orderBy($sort, request()->input('order') ?? 'desc');
            } else {
                $query = $query->orderBy('id', 'desc');
            }

            return $query;
        });

        if (is_null(request()->input('pagination')) || request()->input('pagination')) {
            $results = $query->paginate(request()->input('limit') ?? 10);
        } else {
            $results = $query->get();
        }

        return $this->_config['resource']::collection($results);
    }

    /***
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store()
    {
        try {
            $data = json_decode(request()->getContent(), true);

            $this->img = app('Webkul\Product\Repositories\ProductImageRepository');

            if (!empty($products = $data['products'])) {
                $this->createArticles($products);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Порожній масив Products',
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Your products has been synchronized successfully.',
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ]);
        }

    }

    /**
     * @param $products
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    private function createArticles($products) {
        $objConf = app('Webkul\Product\Type\Configurable');
        $objSimple = app('Webkul\Product\Type\Simple');

        $products = $this->groupBy($products, 'model');

        foreach ($products as $model => $group) {
            $productConf = Product::where(['type' => 'configurable', 'sku' => $model])->first();

            $firstElement = $this->fillDefaultData($group[0]);

            $validator = Validator::make($firstElement, [
                'type' => 'required',
                'attribute_family_id' => 'required',
                'sku' => ['required', new Slug],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Error',
                    'data' => $validator->errors(),
                ]);
            }

            if (empty($productConf)) {
                $productConf = $this->repository->create($firstElement);
            }

            foreach ($group as $item) {
                $item['channel'] = $firstElement['channel'];
                $item['locale'] = $firstElement['locale'];
                $item['categories'] = $firstElement['categories'];
                $item['url_key'] = $item['sku'];
                $inventories = [];
                if (is_array($item['inventories'])) {
                    foreach ($item['inventories'] as $key => $value) {
                        $channelInventorySourceIds = core()->getCurrentChannel()
                            ->inventory_sources()
                            ->where('status', 1)
                            ->where('code', $key)
                            ->pluck('id')
                            ->toArray();
                        if (!empty($channelInventorySourceIds)) {
                            $inventories[$channelInventorySourceIds[0]] = $value;
                        } else {
                            $inventorySourceData = [
                                'code' => $key,
                                'name' => $key,
                                'contact_email' => 'market@red.ua',
                                'contact_number' => '1111',
                                'contact_name' => 'ПІБ',
                                'country' => 'UA',
                                'state' => 'Kyiv',
                                'city' => 'Kyiv',
                                'street' => 'вулиця',
                                'postcode' => '1111'
                            ];
                            $inventorySource = $this->inventorySourceRepository->create($inventorySourceData);
                            $inventories[$inventorySource->id] = $value;
                            DB::table('channel_inventory_sources')->insert([
                                'channel_id'          => core()->getCurrentChannel()->id,
                                'inventory_source_id' => $inventorySource->id,
                            ]);
                        }

                    }
                }
                $item['inventories'] = $inventories;

                $variant = Product::where(['sku' => $item['sku']])->first();

                if (empty($variant)) {
                    $variant = $objConf->createVariant($productConf, $this->permutation, $item);
                }

                $objSimple->update($item, $variant->id);

            }

            $variants = $productConf->variants->toArray();
            $variants = array_column($variants, null, 'id');
            $firstElement['variants'] = $variants;

            $this->repository->update($firstElement, $productConf->id);

            $firstElement['locale'] = 'ru';
            $firstElement['name'] = $this->trans->justTranslate($firstElement['name']);
            $firstElement['composition'] = $this->trans->justTranslate($firstElement['composition']);
            $firstElement['description'] = $this->trans->justTranslate($firstElement['description']);
            $firstElement['short_description'] = $this->trans->justTranslate($firstElement['short_description']);
            $this->repository->update($firstElement, $productConf->id);

        }
    }

    /**
     * @param $array
     * @param $key
     * @return array
     */
    private function groupBy($array, $key) {
        $return = [];
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }

    /**
     * @param $article
     * @return mixed
     */
    private function fillDefaultData($article) {
        $attributeSeason = Attribute::where(['code' => 'season'])->first();
        if (empty($attributeSeason)) {
            $attributeSeason = $this->attributeRepository->create(['code' => 'season', 'admin_name' => 'Season', 'type' => 'select', 'is_configurable' => 1]);
        }
        $attributeBrand = Attribute::where(['code' => 'brand'])->first();
        if (empty($attributeBrand)) {
            $attributeBrand = $this->attributeRepository->create(['code' => 'brand', 'admin_name' => 'Brand', 'type' => 'select', 'is_configurable' => 1]);
        }
        $optionSeason = AttributeOption::where(['id' => $article['season'], 'attribute_id' => $attributeSeason->id])->first();
        $optionBrand = AttributeOption::where(['id_1c' => $article['brand_id'], 'attribute_id' => $attributeBrand->id])->first();
        if (empty($optionBrand)) {
            $optionBrand = $this->attributeOptionRepository->create([
                'attribute_id' => $attributeBrand->id,
                'admin_name' => $article['brand_name'],
                'id_1c' => $article['brand_id'],
                'sort_order' => 1
            ]);
        }

        $categories = [];
        foreach ($article['categories'] as $item) {
            $category = Category::where(['id' => $item])->first();
            if (!empty($category)) {
                $categories = array_merge($categories, $this->parentCategoryList($category));
            }
        }

        if (!empty($categories)) {
            $article['categories'] = array_unique($categories);
        } else {
            $article['categories'] = [];
        }

        $article['sku'] = $article['model'];
        $article['url_key'] = $article['model'];
        $article['type'] = 'configurable';
        $article['channel'] = 'default';
        $article['locale'] = 'uk';
        $article['visible_individually'] = 1;
        $article['attribute_family_id'] = 1;
        $article['featured'] = 1;
        $article['season'] = !empty($optionSeason->id) ? $optionSeason->id : '';
        $article['brand'] = !empty($optionBrand->id) ? $optionBrand->id : '';
        $article['super_attributes'] = [
            'color' => [],
            'size' => []
        ];
        $article['inventories'] = [];

        return $article;
    }

    /**
     * @param $category
     * @param array $ids
     * @return array
     */
    private function parentCategoryList($category, &$ids = []) {
        $ids[] = $category->id;
        if (!empty($category->parent_id && $category->parent->id !== 1)) {
            $parentCategory = Category::where(['id' => $category->parent_id])->first();
            if (!empty($parentCategory)) {
                $this->parentCategoryList($parentCategory, $ids);
            }
        }
        return $ids;

    }

    /**
     * @param $categories
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function createCategoryRacursively($categories)
    {
        $created = 0;
        while ($created < count($categories)) {
            foreach ($categories as $category) {
                if (!empty($category['parent_id'])) {
                    $parentCategory = Category::where(['red_id' => $category['parent_id']])->first();
                    if (!empty($parentCategory)) {
                        try {
                            $category['parent_id'] = $parentCategory->id;
                            $this->createUpdateCategory($category);
                            $created++;
                        } catch (Throwable $e) {
                            var_dump($e->getMessage());
                        }
                    }
                } else {
                    $category['parent_id'] = 1;
                    $this->createUpdateCategory($category);
                    $created++;
                }

            }
        }

    }

    /**
     * @param $category
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function createUpdateCategory($category){
        $attributes = [];
        if (!empty($attributeColor = Attribute::where(['code' => 'color'])->first())) {
            $attributes[] = $attributeColor->id;
        }
        if (!empty($attributeSize = Attribute::where(['code' => 'size'])->first())) {
            $attributes[] = $attributeSize->id;
        }
        if (!empty($attributeBrand = Attribute::where(['code' => 'brand'])->first())) {
            $attributes[] = $attributeBrand->id;
        }
        if (!empty($attributePrice = Attribute::where(['code' => 'price'])->first())) {
            $attributes[] = $attributePrice->id;
        }
        if (!empty($attributeSeason = Attribute::where(['code' => 'season'])->first())) {
            $attributes[] = $attributeSeason->id;
        }

        $uk = [
            'name' => $category['name_uk'],
            'description' => $category['description_uk'],
            'slug' => Str::slug($category['name_uk']),
            'meta_title' => $category['name_uk'],
            'meta_description' => $category['description_uk'],
        ];
        $ru = [
            'name' => $category['name_ru'],
            'description' => $category['description_ru'],
            'slug' => Str::slug($category['name_ru']),
            'meta_title' => $category['name_ru'],
            'meta_description' => $category['description_ru'],
        ];

        $data  = [
            "locales"          => [
                "uk" => $uk,
                "ru" => $ru,
            ],
            'uk'               => $uk,
            'ru'               => $ru,
            "status"           => !empty($category['status']) ? 1 : 0,
            "position"         => 0,
            "display_mode"     => "products_only",
            "image"            => ["image_1" => ""],
            "parent_id"        => !empty($category['parent_id']) ? $category['parent_id'] : null,
            "red_id"           => $category['id'],
            "attributes"       => $attributes
        ];

        $findCategory = Category::where(['red_id' => $category['id']])->first();
        if (!empty($findCategory)) {
            $this->categoryRepository->update($data, $findCategory->id);
        } else {
            $this->categoryRepository->create($data);
        }

    }


    /**
     * @param $productSku
     * @param $productId
     */
    private function createProductImage($productSku, $productId) {
        $dir = 'storage/import_product/' . $productSku . '/';
        $newDir = 'storage/product/' . $productId . '/';
        if (!File::isDirectory($newDir)) {
            File::makeDirectory($newDir);
        }
        $isDirectory = File::isDirectory($dir);

        if ($isDirectory === true) {
            $allFiles = File::allFiles($dir);
            if (!empty($allFiles)) {
                foreach ($allFiles as $file) {
                    $filename = $file->getFilename();
                    $path = $dir . $filename;
                    $newPath = $newDir . $filename;
                    if (!File::isFile($newPath)) {
                        File::copy($path, $newPath);
                        $relPath = 'product/' . $productId . '/' . $filename;
                        $this->img->create([
                            'path'       => $relPath,
                            'product_id' => $productId,
                        ]);
                    }
                }
            }
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories() {
        try {
            $categories = Category::all()->toArray();
            return response()->json([
                'status' => 200,
                'message' => 'ok',
                'data' => $categories
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ]);
        }

    }

    /**
     * Create sizes
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSizes() {
        try {
            $data = json_decode(request()->getContent(), true);
            if (!empty($data['sizes'])) {
                $attributeSize = Attribute::where(['code' => 'size'])->first();
                if (empty($attributeSize)) {
                    $attributeSize = $this->attributeRepository->create(['code' => 'size', 'admin_name' => 'Size', 'type' => 'select', 'is_configurable' => 1]);
                }

                foreach ($data['sizes'] as $size) {
                    $this->attributeOptionRepository->create([
                        'attribute_id' => $attributeSize->id,
                        'admin_name' => $size['admin_name'],
                        'sort_order' => $size['sort_order'],
                        'en' => ['label' => $size['admin_name']],
                        'uk' => ['label' => $size['admin_name']],
                        'ru' => ['label' => $size['admin_name']],
                    ]);
                }
            } else {
                throw new \Exception('Empty data');
            }

            return response()->json([
                'status' => 200,
                'message' => 'Sizes created successfully',
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSizes() {
        try {
            $attributeSize = Attribute::where(['code' => 'size'])->first();
            if (empty($attributeSize)) {
                throw new \Exception('Атрибут розмір не створений');
            }
            $sizes = AttributeOption::where(['attribute_id' => $attributeSize->id])->get();
            return response()->json([
                'status' => 200,
                'message' => 'ok',
                'data' => $sizes
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ]);
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getColors() {
        try {
            $attributeColor = Attribute::where(['code' => 'color'])->first();
            if (empty($attributeColor)) {
                throw new \Exception('Атрибут колір не створений');
            }
            $colors = AttributeOption::where(['attribute_id' => $attributeColor->id])->get();
            return response()->json([
                'status' => 200,
                'message' => 'ok',
                'data' => $colors
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBrands() {
        try {
            $attributeBrand = Attribute::where(['code' => 'brand'])->first();
            if (empty($attributeBrand)) {
                throw new \Exception('Атрибут бренд не створений');
            }
            $brands = AttributeOption::where(['attribute_id' => $attributeBrand->id])->get();
            return response()->json([
                'status' => 200,
                'message' => 'ok',
                'data' => $brands
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSeasons() {
        try {
            $attributeSeason = Attribute::where(['code' => 'season'])->first();
            if (empty($attributeSeason)) {
                throw new \Exception('Атрибут сезон не створений');
            }
            $seasons = AttributeOption::where(['attribute_id' => $attributeSeason->id])->get();
            return response()->json([
                'status' => 200,
                'message' => 'ok',
                'data' => $seasons
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Returns a individual resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $query = isset($this->_config['authorization_required']) && $this->_config['authorization_required'] ?
                $this->repository->where('customer_id', auth()->user()->id)->findOrFail($id) :
                $this->repository->findOrFail($id);

        return new $this->_config['resource']($query);
    }

    /**
     * Delete's a individual resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $wishlistProduct = $this->repository->findOrFail($id);

        $this->repository->delete($id);

        return response()->json([
            'message' => 'Item removed successfully.',
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrder()
    {
        $orders = json_decode(request()->getContent(), true);

        try {
            $mainInventorySourceId = core()->getCurrentChannel()
                ->inventory_sources()
                ->where('code', 'BF0000001')
                ->pluck('id')
                ->toArray();

            if (empty($mainInventorySourceId)) {
                throw new \Exception('Inventory source with code BF0000001 not found');
            }

            if (!empty($orders['orders'])) {
                foreach ($orders['orders'] as $item) {
                    $validator = Validator::make($item, [
                        'id' => 'required',
                        'status' => 'required'
                    ]);

                    if ($validator->fails()) {
                        return response()->json([
                            'message' => 'Error',
                            'data' => $validator->errors(),
                        ], 500);
                    }

                    $order = $this->orderRepository->find($item['id']);
                    if (!empty($order)) {
                        if ($item['status'] === 'assembled') {
                            $items = $order->items->toArray();
                            if (!empty($items)) {
                                $items = array_column($items, 'qty_ordered', 'id');
                                $invoices['invoice']['items'] = $items;

                                $haveProductToInvoice = false;

                                foreach ($invoices['invoice']['items'] as $itemId => $qty) {
                                    if ($qty) {
                                        $haveProductToInvoice = true;
                                        break;
                                    }
                                }

                                if ($order->canInvoice() && $haveProductToInvoice) {
                                    $this->invoiceRepository->create(array_merge($invoices, ['order_id' => $order->id]));
                                    $order->status = $item['status'];
                                }

                                if ($order->shipping_method == 'deliverypoint') {
                                    if ($order->canShip()) {
                                        $shippingItems = [];
                                        foreach ($invoices['invoice']['items'] as $itemId => $qty) {
                                            $shippingItems[$itemId] = [
                                                $mainInventorySourceId[0] => $qty
                                            ];
                                        }
                                        $shipping = [
                                            'shipment' => [
                                                'carrier_title' => '',
                                                'track_number' => '',
                                                'source' => $mainInventorySourceId[0],
                                                'items' => $shippingItems
                                            ]
                                        ];
                                        if (!$this->isInventoryValidate($shipping)) {
                                            throw new \Exception('Inventory source qty is not a valid for order #' . $item['id']);
                                        }
                                        $this->shipmentRepository->create(array_merge($shipping, ['order_id' => $order->id]));

                                        $order->status = 'completed';
                                    }
                                }
                            }

                            if (!$order->save()) {
                                throw new \Exception('Save error');
                            }
                        } else {
                            throw new \Exception('Unknown status');
                        }

                    }
                }
                return response()->json([
                    'status' => 200,
                    'message' => 'Orders successfully updated',
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * return orders by created date and status
     */
    public function getOrders () {
        $data = request()->all();
        try {
            if (!empty($data['status']) && !empty($data['from']) && !empty($data['to'])) {
                $from = date($data['from']);
                $to = date($data['to']);

                $orders = Order::with('all_items')->with('shipping_address')
                    ->select(['id', 'status', 'customer_first_name', 'customer_last_name', 'shipping_method', 'shipping_title', 'grand_total', 'created_at', 'updated_at'])
                    ->where(['status' => $data['status']])
                    ->whereBetween('created_at', [$from, $to])
                    ->get()->toArray();

                $channelInventorySourceIds = core()->getCurrentChannel()
                    ->inventory_sources()
                    ->get()
                    ->toArray();

                $inventorySources = array_column($channelInventorySourceIds, null, 'id');

                foreach ($orders as $keyOrder => $order) {
                    $orderItems = [];
                    foreach ($order['all_items'] as $key => $item) {
                        if ($item['type'] == 'simple' && !empty($item['parent_id'])) {
                            $orderItems['_' . $item['parent_id']]['sku'] = $item['sku'];
                        } else if ($item['type'] == 'configurable') {
                            $orderItems['_' . $item['id']]['qty'] = (string)$item['additional']['quantity'];
                            $orderItems['_' . $item['id']]['base_price'] = (string)$item['base_price'];
                        }
                    }

                    $warehouseId = $order['shipping_address'][0]['warehouse_ref'];
                    if ($order['shipping_method'] == 'deliverypoint' && !empty($warehouseId)) {
                        if (!empty($inventorySources[$warehouseId])) {
                            $orders[$keyOrder]['department_code'] = $inventorySources[$warehouseId]['code'];
                            $orders[$keyOrder]['department_name'] = $inventorySources[$warehouseId]['name'];
                        }

                    }

                    $orders[$keyOrder]['phone'] = $order['shipping_address'][0]['phone'];
                    unset($orders[$keyOrder]['shipping_address']);

                    $orders[$keyOrder]['all_items'] = $orderItems;
                    $orders[$keyOrder]['id'] = (string)$orders[$keyOrder]['id'];
                }

                return response()->json([
                    'status' => "200",
                    'orders' => $orders,
                ], 200);
            } else {
                throw new \Exception('Fill params: status, from, to. DateTime format: YYYY-MM-DD 00:00:00');
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => "500",
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * get delivery list
     */
    public function getDeliveryList () {
        try {
            $tree = Tree::create();

            foreach (config('core') as $item) {
                $tree->add($item);
            }

            $tree->items = core()->sortItems($tree->items);

            $deliveries = \Illuminate\Support\Arr::get($tree->items, 'sales.children.carriers.children');

            $deliveries = array_keys($deliveries);

            $array = [];
            foreach ($deliveries as $key => $value) {
                $array[(string)$value] = (string)$value;
            }

            return response()->json([
                'status' => "200",
                'deliveries' => $array,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => "500",
                'message' => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Checks if requested quantity available or not
     *
     * @param  array  $data
     * @return bool
     */
    public function isInventoryValidate(&$data)
    {
        if (! isset($data['shipment']['items'])) {
            return ;
        }

        $valid = false;

        $inventorySourceId = $data['shipment']['source'];

        foreach ($data['shipment']['items'] as $itemId => $inventorySource) {
            if ($qty = $inventorySource[$inventorySourceId]) {
                $orderItem = $this->orderItemRepository->find($itemId);

                if ($orderItem->qty_to_ship < $qty) {
                    return false;
                }

                if ($orderItem->getTypeInstance()->isComposite()) {
                    foreach ($orderItem->children as $child) {
                        if (! $child->qty_ordered) {
                            continue;
                        }

                        $finalQty = ($child->qty_ordered / $orderItem->qty_ordered) * $qty;

                        $availableQty = $child->product->inventories()
                            ->where('inventory_source_id', $inventorySourceId)
                            ->sum('qty');

                        if ($child->qty_to_ship < $finalQty || $availableQty < $finalQty) {
                            return false;
                        }
                    }
                } else {
                    $availableQty = $orderItem->product->inventories()
                        ->where('inventory_source_id', $inventorySourceId)
                        ->sum('qty');

                    if ($orderItem->qty_to_ship < $qty || $availableQty < $qty) {
                        return false;
                    }
                }

                $valid = true;
            } else {
                unset($data['shipment']['items'][$itemId]);
            }
        }

        return $valid;
    }

}
