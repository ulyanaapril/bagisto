<?php

namespace Red\API\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Red\Admin\Models\AttributeOption;
use Red\Admin\Models\Category;
use Red\Admin\Repositories\AttributeOptionRepository;
use Red\Admin\Repositories\CategoryRepository;
use Throwable;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Repositories\AttributeRepository;
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
     * @var array
     */
    protected $permutation = [
        23 => 1,
        24 => 6
    ];

    /**
     * Create a new controller instance.
     *
     * @param CategoryRepository $categoryRepository
     * @param AttributeRepository $attribute
     * @param AttributeOptionRepository $attributeOption
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        AttributeRepository $attribute,
        AttributeOptionRepository $attributeOption
    )
    {
        $this->guard = request()->has('token') ? 'admin-api' : 'admin';

        $this->_config = request('_config');

        $this->categoryRepository = $categoryRepository;
        $this->attributeRepository = $attribute;
        $this->attributeOptionRepository = $attributeOption;

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
            $firstElement['name'] = $firstElement['name'] .'_ru';
            $firstElement['composition'] = $firstElement['name'] .'_ru';
            $firstElement['description'] = $firstElement['description'] . '_ru';
            $firstElement['short_description'] = $firstElement['short_description'] .'_ru';
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
        $optionBrand = AttributeOption::where(['id' => $article['brand'], 'attribute_id' => $attributeBrand->id])->first();

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
}
