<p align="center">
<a href="http://www.bagisto.com"><img src="https://bagisto.com/wp-content/themes/bagisto/images/logo.png" alt="Total Downloads"></a>
</p>

<p align="center">
<a href="https://packagist.org/packages/bagisto/bagisto"><img src="https://poser.pugx.org/bagisto/bagisto/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/bagisto/bagisto"><img src="https://poser.pugx.org/bagisto/bagisto/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/bagisto/bagisto"><img src="https://poser.pugx.org/bagisto/bagisto/license.svg" alt="License"></a>
<a href="https://github.com/bagisto/bagisto/actions"><img src="https://github.com/bagisto/bagisto/workflows/CI/badge.svg" alt="Backers on Open Collective"></a>
<a href="#backers"><img src="https://opencollective.com/bagisto/backers/badge.svg" alt="Backers on Open Collective"></a>
<a href="#sponsors"><img src="https://opencollective.com/bagisto/sponsors/badge.svg" alt="Sponsors on Open Collective"></a>
</p>

## Topics
1. [Introduction](#introduction)
2. [Documentation](#documentation)
3. [Requirements](#requirements)
4. [Installation & Configuration](#installation-and-configuration)
5. [License](#license)
6. [Security Vulnerabilities](#security-vulnerabilities)
7. [Miscellaneous](#miscellaneous)

### Documentation

#### Bagisto Documentation [https://devdocs.bagisto.com](https://devdocs.bagisto.com)

### Requirements

* **SERVER**: Apache 2 or NGINX.
* **RAM**: 3 GB or higher.
* **PHP**: 7.3 or higher.
* **For MySQL users**: 5.7.23 or higher.
* **For MariaDB users**: 10.2.7 or Higher.
* **Node**: 8.11.3 LTS or higher.
* **Composer**: 1.6.5 or higher.

## Розгортання проекту

* Склонити проект з репозиторію.
~~~
git clone https://gitlab.com/ulyanaapril/bagisto.git
~~~

* Створити файл .env з вмістом зі зразка

* Розгорнути платформу на новому сервері: обнулить базу даних і заповнить її демо даними. Всі дані товару та користувачі будуть видалені.
!!!Не виконувати на робочій базі
~~~
php artisan bagisto:install
~~~

* На робочій базі для встановлення залежностей виконати
~~~
> composer install --no-dev
~~~

* Запустити якщо на сервері помилка про відсутність модуля. 
Тільки для установки платформи на новому сервері
~~~
apt-get install php7.4-intl
~~~

* Очистка і генерація кешу і конфігурації. Запустити після внесення змін до коду
~~~
php artisan config:cache
php artisan config:clear
php artisan cache:clear
~~~

* Після розгорнення проекту обновляємо символічні посилання для зображень товару, 
якщо картинки не відображаються після завантаження(видаляться всі зображення, використовувати тільки при розгорненні на новому сервері):

1. Спочатку видаляємо PUBLIC папку/файл з root/storage/app/. 
2. Потім видаляємо STORAGE папку з root/publc. 
3. Потім спочатку створюємо PUBLIC папку в root/storage/app. 
4. Потім виконуємо php artisan storage:link.
5. Виконуємо chmod -R 777 storage щоб надати доступ до створеної папки.

* Якщо відбулись зміни в компоненті vue генеруємо публічні файли. 
Тільки для локального сервера!!! Потрібні згенеровані файли комітимо на прод сервер. 
~~~
npm run prod
php artisan vendor:publish --force
~~~

* Запускаємо локально сервер php artisan serve

* Вхід в адмінку при стандартному розгортанні проекту
~~~
email:admin@example.com
password:admin123
~~~


## Зміни в ядрі

   ProductFlatRepository
~~~
   $attributes = app('Webkul\Attribute\Repositories\AttributeRepository')->getModel()->whereIn('id', [11])->get();//price
   return $loadedCategoryAttributes[$category->id] = $attributes;
   return $loadedCategoryAttributes[$category->id] = $category->filterableAttributes;
~~~
   
   /home/php/projects/bagisto/packages/Webkul/Velocity/src/Helpers/AdminHelper.php
   ~~~
   if (! $category instanceof \Webkul\Category\Contracts\Category) {
               $id = !empty($category->id) ? $category->id : $category;
               $category = $this->categoryRepository->findOrFail($id);
   }
   
   ~~~
   /home/php/projects/bagisto/packages/Webkul/Admin/src/Http/Controllers/Sales/OrderController.php
   ~~~
   public function view($id)
       {
           $order = $this->orderRepository->findOrFail($id);
        		try { ... } 
   
   
        		nova poshta class 922
        		закоментувала
        		//        if (!($counterparty['RecipientAddress'] OR $counterparty['CityRef']))
   			//            throw new \Exception('RecipientAddress is required filed for recipient');
   
~~~
змінила у файлі /home/php/projects/bagisto/packages/Webkul/Velocity/src/Http/Controllers/Shop/ShopController.php
~~~
   
               private function getCategoryFilteredData($category)
       {
           $formattedChildCategory = [];
   
           foreach ($category->children as $child) {
               array_push($formattedChildCategory, $this->getCategoryFilteredData($child));
           }
   
           return [
               'id'                 => $category->id,
               'slug'               => $category->slug,
               'name'               => $category->name,
               'children'           => $formattedChildCategory,
               'category_icon_path' => $category->category_icon_path,
               'image'              => $category->image
           ];
       }
   
~~~
на
~~~
   
       private function getCategoryFilteredData($category)
       {
           $formattedChildCategory = [];
   
           foreach ($category->children as $child) {
               array_push($formattedChildCategory, $this->getCategoryFilteredData($child));
           }
   
           return [
               'id'                 => $category->id,
               'parent_id'           => $category->parent_id,//added
               'slug'               => $category->slug,
               'name'               => $category->name,
               'children'           => $formattedChildCategory,
               'category_icon_path' => $category->category_icon_path,
               'image'              => $category->image
           ];
       }
~~~
/home/php/projects/bagisto/packages/Webkul/Product/src/CacheFilters/Medium.php
~~~
/**
     * @param  \Intervention\Image\Image  $image
     * @return \Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {

        $image->resize(280, 350, function ($constraint) {
            $constraint->aspectRatio();
        });

        return $image->resizeCanvas(300, 300, 'center', false, '#fff');

    }
~~~