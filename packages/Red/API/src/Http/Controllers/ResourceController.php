<?php

namespace Red\API\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Webkul\Core\Contracts\Validations\Slug;
use Webkul\Product\Models\Product;

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
     * @var array
     */
    protected $permutation = [
        23 => 1,
        24 => 6
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->guard = request()->has('token') ? 'admin-api' : 'admin';

        $this->_config = request('_config');

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

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store()
    {
        try {
            $data = json_decode(request()->getContent(), true);

            $objConf = app('Webkul\Product\Type\Configurable');
            $objSimple = app('Webkul\Product\Type\Simple');
            $this->img = app('Webkul\Product\Repositories\ProductImageRepository');

            $data = $this->groupBy($data, 'model');

            foreach ($data as $model => $group) {
                $productConf = Product::whereHas('attribute_values', function ($query) use ($model) {
                    return $query->where(['attribute_id' =>  30, 'text_value' => $model]);
                })->where(['type' => 'configurable'])->first();

                $firstSku = $group[0]['sku'];
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

                    $variant = Product::where(['sku' => $item['sku']])->first();

                    if (empty($variant)) {
                        $variant = $objConf->createVariant($productConf, $this->permutation, $item);
                    }

                    $objSimple->update($item, $variant->id);

                    $this->createProductImage($variant->sku, $variant->id);

                }

                $variants = $productConf->variants->toArray();
                $variants = array_column($variants, null, 'id');
                $firstElement['variants'] = $variants;

                $this->repository->update($firstElement, $productConf->id);

                $this->createProductImage($firstSku, $productConf->id);

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
     * @param $firstElement
     * @return mixed
     */
    private function fillDefaultData($firstElement) {
        $firstElement['sku'] = $firstElement['sku'] . '-1';
        $firstElement['barcode'] = $firstElement['barcode'] . '-1';
        $firstElement['url_key'] = $firstElement['url_key'] . '-1';
        $firstElement['product_number'] = $firstElement['product_number'] . '-1';
        $firstElement['type'] = 'configurable';
        $firstElement['channel'] = 'default';
        $firstElement['locale'] = 'uk';
        $firstElement['visible_individually'] = 1;
        $firstElement['attribute_family_id'] = 1;
        $firstElement['featured'] = 1;
        $firstElement['super_attributes'] = [
            'color' => [],
            'size' => []
        ];

        return $firstElement;
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
}
