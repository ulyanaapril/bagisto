<?php

namespace Red\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Red\Admin\Models\AttributeOption;
use Red\Admin\Models\Brands;
use Red\Admin\Models\Category;
use Red\Admin\Repositories\CategoryRepository;
use Throwable;
use Red\Admin\Repositories\AttributeOptionRepository;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Models\Product;
use Webkul\Product\Repositories\ProductRepository;

class ImportController extends Controller
{

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var AttributeOptionRepository
     */
    protected $attributeOptionRepository;

    /**
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * @var ProductRepository
     */
    protected $repository;

    /**
     * ImportController constructor.
     * @param CategoryRepository $categoryRepository
     * @param AttributeOptionRepository $attributeOption
     * @param ProductRepository $repository
     * @param AttributeRepository $attribute
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        AttributeOptionRepository $attributeOption,
        ProductRepository $repository,
        AttributeRepository $attribute)
    {
        $this->middleware('admin');
        $this->categoryRepository = $categoryRepository;
        $this->attributeOptionRepository = $attributeOption;
        $this->repository = $repository;
        $this->attributeRepository = $attribute;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function import()
    {
        try {
            ini_set('max_execution_time', 1000);
            $client = new Client();
            $response = $client->request('GET', 'https://www.red.ua/ajax/get-articles/');
            $response = $response->getBody()->getContents();
            $response = json_decode($response);

            if (!empty($categories = $response->categories)) {
                $this->createCategoryRacursively($categories);
            }

            if (!empty($response->sizesCategories)) {
                $attributeSizeCategory = Attribute::where(['code' => 'size_category'])->first();
                if (empty($attributeSizeCategory)) {
                    $attributeSizeCategory = $this->attributeRepository->create(['code' => 'size_category', 'admin_name' => 'Size category', 'type' => 'select', 'is_configurable' => 1]);
                }
                foreach ($response->sizesCategories as $sizeCategory) {
                    $option = AttributeOption::where(['red_id' => $sizeCategory->id, 'attribute_id' => $attributeSizeCategory->id]);
                    if (empty($option)) {
                        $this->attributeOptionRepository->create([
                            'attribute_id' => $attributeSizeCategory->id,
                            'admin_name' => $sizeCategory->name,
                            'red_id' => $sizeCategory->id,
                            'sort_order' => 1,
                        ]);
                    }
                }
            }
            if (!empty($response->sizes)) {
                $attributeSize = Attribute::where(['code' => 'size'])->first();
                if (empty($attributeSize)) {
                    $attributeSize = $this->attributeRepository->create(['code' => 'size', 'admin_name' => 'Size', 'type' => 'select', 'is_configurable' => 1]);
                }

                foreach ($response->sizes as $size) {
                    $option = AttributeOption::where(['red_id' => $size->id, 'attribute_id' => $attributeSize->id])->first();
                    if (empty($option)) {
                        $this->attributeOptionRepository->create([
                            'attribute_id' => $attributeSize->id,
                            'admin_name' => $size->size,
                            'id_1c' => $size->id_1c,
                            'red_id' => $size->id,
                            'sort_order' => 1
                        ]);
                    }
                }
            }

            if (!empty($response->colors)) {
                $attributeColor = Attribute::where(['code' => 'color'])->first();
                if (empty($attributeColor)) {
                    $attributeColor = $this->attributeRepository->create(['code' => 'color', 'admin_name' => 'Color', 'type' => 'select', 'is_configurable' => 1]);
                }

                foreach ($response->colors as $color) {
                    $option = AttributeOption::where(['red_id' => $color->id, 'attribute_id' => $attributeColor->id])->first();
                    if (empty($option)) {
                        $this->attributeOptionRepository->create([
                            'attribute_id' => $attributeColor->id,
                            'admin_name' => $color->name,
                            'id_1c' => $color->id_1c,
                            'red_id' => $color->id,
                            'swatch_value' => $color->color,
                            'sort_order' => 1,
                            'uk' => ['label' => !empty($color->name_uk) ? $color->name_uk : '']
                        ]);
                    }
                }
            }

            if (!empty($response->brands)) {
                $attributeBrand = Attribute::where(['code' => 'brand'])->first();
                if (empty($attributeBrand)) {
                    $attributeBrand = $this->attributeRepository->create(['code' => 'brand', 'admin_name' => 'Brand', 'type' => 'select', 'is_configurable' => 1]);
                }

                foreach ($response->brands as $brand) {
                    $option = AttributeOption::where(['red_id' => $brand->id, 'attribute_id' => $attributeBrand->id])->first();
                    if (empty($option)) {
                        $this->attributeOptionRepository->create([
                            'attribute_id' => $attributeBrand->id,
                            'admin_name' => $brand->name,
                            'red_id' => $brand->id,
                            'sort_order' => 1
                        ]);

                        Brands::updateOrCreate(['name' => $brand->name], [
                            'red_id' => $brand->id,
                            'greyd' => $brand->greyd,
                            'name' => $brand->name,
                            'country_brand' => $brand->country_brand,
                            'image' => $brand->image,
                            'logo' => $brand->logo,
                            'text' => $brand->text,
                            'text_uk' => $brand->text_uk,
                            'track' => $brand->track
                        ]);
                    }
                }
            }

            if (!empty($response->sex)) {//не використовується
                $attributeSex = Attribute::where(['code' => 'sex'])->first();
                if (empty($attributeSex)) {
                    $attributeSex = $this->attributeRepository->create(['code' => 'sex', 'admin_name' => 'Sex', 'type' => 'select', 'is_configurable' => 1]);
                }

                foreach ($response->sex as $item) {
                    $option = AttributeOption::where(['red_id' => $item->id, 'attribute_id' => $attributeSex->id])->first();
                    if (empty($option)) {
                        $this->attributeOptionRepository->create([
                            'attribute_id' => $attributeSex->id,
                            'admin_name' => $item->name,
                            'id_1c' => $item->id_1c,
                            'red_id' => $item->id,
                            'sort_order' => 1,
                        ]);
                    }
                }
            }

            if (!empty($response->sezons)) {
                $attributeSeason = Attribute::where(['code' => 'season'])->first();
                if (empty($attributeSeason)) {
                    $attributeSeason = $this->attributeRepository->create(['code' => 'season', 'admin_name' => 'Season', 'type' => 'select', 'is_configurable' => 1]);
                }

                foreach ($response->sezons as $sezon) {
                    $option = AttributeOption::where(['red_id' => $sezon->id, 'attribute_id' => $attributeSeason->id])->first();
                    if (empty($option)) {
                        $this->attributeOptionRepository->create([
                            'attribute_id' => $attributeSeason->id,
                            'admin_name' => $sezon->name,
                            'id_1c' => $sezon->id_1c,
                            'red_id' => $sezon->id,
                            'sort_order' => 1,
                            'uk' => ['label' => !empty($sezon->name_uk) ? $sezon->name_uk : '']
                        ]);
                    }
                }
            }

            if (!empty($response->articles)) {
                $this->createArticle($response->articles);
            }


//            Session::put('success', 'The data successfully import in database!!!');

            $route = route('admin.catalog.products.index');
            header('Location: ' . $route, true, 302);
            exit;


        } catch (\Exception $e) {
            die($e->getMessage());
        }

    }


    /**
     * @param $articles
     * @return bool
     */
    private function createArticle($articles)
    {
        $objConf = app('Webkul\Product\Type\Configurable');
        $objSimple = app('Webkul\Product\Type\Simple');
        $this->img = app('Webkul\Product\Repositories\ProductImageRepository');

        $attributeColor = Attribute::where(['code' => 'color'])->first();
        $attributeSize = Attribute::where(['code' => 'size'])->first();

        $i = 0;
        foreach ($articles as $article) {
            $i++;
            if ($i > 20) {
                break;
            }
            $article = json_decode(json_encode($article), true);

            $productConf = Product::where(['sku' => $article['code']])->first();

            $dataConf = $this->fillDefaultData($article);

            if (empty($productConf)) {
                $productConf = $this->repository->create($dataConf);
            }

            if (!empty($article['articleSizes'])) {
                foreach ($article['articleSizes'] as $item) {
                    $optionColor = AttributeOption::where(['red_id' => $item['id_color'], 'attribute_id' => $attributeColor->id])->first();
                    $optionSize = AttributeOption::where(['red_id' => $item['id_size'], 'attribute_id' => $attributeSize->id])->first();

                    $item['channel'] = $dataConf['channel'];
                    $item['locale'] = $dataConf['locale'];
                    $item['sku'] = $item['code'];
                    $item['name'] = $dataConf['model'] . ' ' . $dataConf['brand'];//?cat
                    $item['price'] = $dataConf['price'];
                    $item['weight'] = 100;
                    $item['status'] = !empty($dataConf['status']) ? 1 : 0;//?
                    $item['inventories'] = ['1' => $item['count']];

                    $variant = Product::where(['sku' => $item['code']])->first();

                    if (empty($variant)) {
                        if ($item['count'] > 0) {
                            $variant = $objConf->createVariant(
                                $productConf,
                                [
                                    $attributeColor->id => !empty($optionColor->id) ? $optionColor->id : '',
                                    $attributeSize->id => !empty($optionSize->id) ? $optionSize->id : ''
                                ],
                                $item
                            );
                        }
                    }

                    if (!empty($variant)) {
                        $objSimple->update($item, $variant->id);
                    }

                }
            }

            $variants = $productConf->variants->toArray();
            $variants = array_column($variants, null, 'id');
            $dataConf['variants'] = $variants;

            $this->repository->update($dataConf, $productConf->id);

            if (!empty($article['images'])) {
                foreach ($article['images'] as $img) {
                    if ($this->downloadFile($img['image']) == true) {
                        $this->createProductImage($img['image'], $productConf->id);
                    }
                }
            }

        }


        return true;

    }

    /**
     * @param $fileName
     * @return bool
     */
    private function downloadFile($fileName) {
        $dir = 'storage/import_product/';
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir);
        }
        $storagePath = Storage::disk('local')->getAdapter()->getPathPrefix();
        $storagePath .= "public/import_product/$fileName";
        $redHost = env('RED_HOST', null);
        $redPort = env('RED_PORT', null);
        $redUsername = env('RED_USERNAME', null);
        $redPassword = env('RED_PASSWORD', null);
        $connection = ssh2_connect($redHost, $redPort);

        if (ssh2_auth_password($connection, $redUsername, $redPassword)) {
            if ($sftp = ssh2_sftp($connection)) {
                $remotePath = "ssh2.sftp://$sftp/home/www.red.ua/www/files/org/$fileName";
                $fileExists = file_exists($remotePath);
                if($fileExists) {
                    $remoteStream = @fopen($remotePath, 'r');
                    $localStream = @fopen($storagePath, 'w');
                    if ($remoteStream && $localStream) {
                        if (stream_copy_to_stream($remoteStream, $localStream)) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;

    }

    /**
     * @param $fileName
     * @param $productId
     */
    private function createProductImage($fileName, $productId) {
        $dir = 'storage/import_product/';
        $newDir = 'storage/product/' . $productId . '/';
        if (!File::isDirectory($newDir)) {
            File::makeDirectory($newDir);
        }
        $isFile = File::isFile($dir . $fileName);

        if ($isFile == true) {
            $path = $dir . $fileName;
            $newPath = $newDir . $fileName;
            if (!File::isFile($newPath)) {
                File::copy($path, $newPath);
                $relPath = 'product/' . $productId . '/' . $fileName;
                $this->img->create([
                    'path'       => $relPath,
                    'product_id' => $productId,
                ]);
            }
        }

    }

    /**
     * @param $article
     * @return mixed
     */
    private function fillDefaultData($article) {
        $attributeSeason = Attribute::where(['code' => 'season'])->first();
        $attributeBrand = Attribute::where(['code' => 'brand'])->first();
        $optionSeason = AttributeOption::where(['red_id' => $article['sezon'], 'attribute_id' => $attributeSeason->id])->first();
        $optionBrand = AttributeOption::where(['red_id' => $article['brand_id'], 'attribute_id' => $attributeBrand->id])->first();

        $category = Category::where(['red_id' => $article['category_id']])->first();
        if (!empty($category)) {
            $article['categories'] = $category->id;
        }
        $article['name'] = $article['model'] . ' ' . $article['brand'];
        $article['short_description'] = $article['short_text'];
        $article['description'] = $article['long_text'];
        $article['sku'] = $article['code'];
        $article['inventories'] = ['1' => $article['stock']];
        $article['url_key'] = $article['id'];
        $article['product_number'] = $article['id'];
        $article['type'] = 'configurable';
        $article['channel'] = 'default';
        $article['locale'] = 'uk';
        $article['visible_individually'] = 1;
        $article['attribute_family_id'] = 1;
        $article['featured'] = 1;
        $article['composition'] = $article['sostav'];
        $article['season'] = !empty($optionSeason->id) ? $optionSeason->id : '';
        $article['brand'] = !empty($optionBrand->id) ? $optionBrand->id : '';
        $article['super_attributes'] = [
            'color' => [],
            'size' => []
        ];

        return $article;
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
                if (!empty($category->parent_id)) {
                    $parentCategory = Category::where(['red_id' => $category->parent_id])->first();
                    if (!empty($parentCategory)) {
                        try {
                        $category->parent_id = $parentCategory->id;
                        if ($parentCategory->id != 1) {
                            $category->action = Str::slug($category->name);
                        }
                        $this->createUpdateCategory($category);
                        $created++;
                        } catch (Throwable $e) {
                            var_dump($e->getMessage());
                        }
                    }
                } else {
                    $category->parent_id = 1;
                    $category->action = Str::slug($category->name);
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
            'name' => !empty($category->name_uk) ? $category->name_uk : $category->name,
            'description' => !empty($category->description_uk) ? $category->description_uk : $category->description,
            'slug' => !empty($category->action) ? $category->action : Str::slug($category->name),
            'meta_title' => !empty($category->title_uk) ? $category->title_uk : $category->title,
            'meta_description' => !empty($category->description_uk) ? $category->description_uk : $category->description,
        ];
        $en = [
            'name' => $category->name,
            'description' => $category->description,
            'slug' => !empty($category->action) ? $category->action : Str::slug($category->name),
            'meta_title' => $category->title,
            'meta_description' => $category->description,
        ];

        $data  = [
            "locales"          => [
                                    "uk" => $uk,
                                    "en" => $en,
            ],
            'uk'               => $uk,
            'en'               => $en,
            "status"           => !empty($category->active) ? 1 : 0,
            "position"         => 0,
            "display_mode"     => "products_only",
            "image"            => ["image_1" => ""],
            "parent_id"        => !empty($category->parent_id) ? $category->parent_id : null,
            "red_id"           => $category->id,
            "attributes"       => $attributes
        ];

        $findCategory = Category::where(['red_id' => $category->id])->first();
        if (!empty($findCategory)) {
            $this->categoryRepository->update($data, $findCategory->id);
        } else {
            $this->categoryRepository->create($data);
        }

    }
}