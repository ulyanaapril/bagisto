<?php

return [
    'invalid_vat_format' => 'Указанный идентификатор НДС имеет неправильный формат',
    'security-warning' => 'Обнаружена подозрительная активность !!!',
    'nothing-to-delete' => 'Ничего не удалять', 

    'layouts' => [
        'my-account' => 'Мой аккаунт',
        'profile' => 'Профиль',
        'address' => 'Адрес',
        'reviews' => 'Отзывы',
        'wishlist' => 'Список жеданий',
        'orders' => 'Заказы',
        'downloadable-products' => 'Загружаемые товары'
    ],

    'common' => [
        'error' => 'Что-то пошло не так, повторите попытку позже.',
        'image-upload-limit' => 'Максимальный размер загружаемого изображения составляет 2 МБ',
        'no-result-found' => 'Мы не смогли найти никаких записей.'
    ],

    'home' => [
        'page-title' => config ('app.name'). ' - Дом',
        'Feature-products' => 'Рекомендуемые товары',
        'new-products' => 'Новые товары',
        'verify-email' => 'Подтвердите свой адрес электронной почты',
        'Resend-verify-email' => 'Повторно отправить письмо с подтверждением'
    ],

    'header' => [
        'title' => 'Аккаунт',
        'dropdown-text' => 'Управление корзиной, заказами и списком желаний',
        'sign-in' => 'Войти',
        'sign-up' => 'Зарегистрироваться',
        'account' => 'Аккаунт',
        'cart' => 'Корзина',
        'profile' => 'Профиль',
        'wishlist' => 'Список желаний',
        'cart' => 'Корзина',
        'logout' => 'Выйти',
        'search-text' => 'Искать здесь продукты'
    ],

    'minicart' => [
        'view-cart' => 'Просмотреть корзину',
        'checkout' => 'Расчет',
        'cart' => 'Корзина',
        'zero' => '0'
    ],

    'footer' => [
        'subscribe-newsletter' => 'Подписаться на рассылку новостей',
        'subscribe' => 'Подписаться',
        'locale' => 'Локаль',
        'currency' => 'Валюта',
    ],

    'subscription' => [
         'unsubscribe' => 'Отписаться',
         'subscribe' => 'Подписаться',
         'subscribed' => 'Теперь вы подписаны на рассылку писем о подписке.',
         'not-subscribed' => 'Вы не можете подписаться на рассылку писем о подписке, попробуйте еще раз позже.',
         'already' => 'Вы уже подписаны на наш список подписок.',
         'unsubscribed' => 'Вы отказались от подписки на рассылку писем.',
         'already-unsub' => 'Вы уже отписались.',
         'not-subscribed' => 'Ошибка! В настоящее время электронная почта не может быть отправлена, повторите попытку позже.'
    ],

    'search' => [
        'no-results' => 'Результатов не найдено',
        'page-title' => config ('app.name'). ' - Поиск',
        'found-results' => 'Результаты поиска найдены',
        'found-result' => 'Результат поиска найден',
        'analysed-keywords' => 'Анализируемые ключевые слова',
        'image-search-option' => 'Параметр поиска изображений'
    ],

    'reviews' => [
        'title' => 'Заголовок',
        'add-review-page-title' => 'Добавить отзыв',
        'write-review' => 'Написать отзыв',
        'review-title' => 'Дайте вашему обзору заголовок',
        'product-review-page-title' => 'Обзор продукта',
        'rating-reviews' => 'Рейтинг и обзоры',
        'submit' => 'ОТПРАВИТЬ',
        'delete-all' => 'Все отзывы удалены успешно',
        'ratingreviews' => ':rating Ratings & :review Reviews',
        'star' => 'Звезда',
        'percentage' => ':percentage %',
        'id-star' => 'звезда',
        'name' => 'Имя',
    ],

    'customer' => [
        'compare'           => [
            'text'                  => 'Сравнить',
            'compare_similar_items' => 'Сравнить похожие товары',
            'add-tooltip'           => 'Добавить товар в список сравнения',
            'added'                 => 'Товар успешно добавлен в список сравнения',
            'already_added'         => 'Товар уже добавлен в список сравнения',
            'removed'               => 'Товар успешно удален из списка сравнения',
            'removed-all'           => 'Все элементы успешно удалены из списка сравнения',
            'empty-text'            => "В вашем списке сравнения нет товаров",
            'product_image'         => 'Изображение продукта',
            'actions'               => 'Действия',
        ],

        'signup-text' => [
            'account_exists' => 'Уже есть учетная запись',
            'title' => 'Войти'
        ],

        'signup-form' => [
            'page-title' => 'Создать новую учетную запись клиента',
            'title' => 'Зарегистрироваться',
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'email' => 'Электронная почта',
            'password' => 'Пароль',
            'confirm_pass' => 'Подтвердите пароль',
            'button_title' => 'Зарегистрироваться',
            'agree' => 'Согласен',
            'terms' => 'Условия',
            'conditions' => 'Условия',
            'using' => 'используя этот веб-сайт',
            'agreement' => 'Соглашения',
            'subscribe-to-newsletter' => 'Подпишитесь на рассылку новостей',
            'success' => 'Аккаунт успешно создан.',
            'success-verify' => 'Учетная запись успешно создана, электронное письмо отправлено для проверки.',
            'success-verify-email-unsent' => 'Аккаунт успешно создан, но проверочное электронное письмо не отправлено.',
            'failed' => 'Ошибка! Не могу создать учетную запись, пожалуйста, попробуйте еще раз позже. ',
            'already-verified' => 'Ваша учетная запись уже подтверждена или попробуйте отправить новое письмо с подтверждением еще раз.',
            'verify-not-sent' => 'Ошибка! Проблема с отправкой подтверждающего электронного письма, повторите попытку позже. ',
            'verify-sent' => 'Письмо с подтверждением отправлено',
            'Verified' => 'Ваша учетная запись подтверждена, попробуйте войти сейчас.',
            'verify-failed' => 'Мы не можем проверить вашу учетную запись электронной почты.',
            'dont-have-account' => 'У вас нет учетной записи у нас.',
            'customer-registration' => 'Клиент успешно зарегистрирован'
        ],

        'login-text' => [
            'no_account' => 'Нет учетной записи',
            'title' => 'Зарегистрироваться', 
        ],

        'login-form' => [
            'page-title' => 'Вход для клиентов',
            'title' => 'Войти',
            'email' => 'Электронная почта',
            'пароль' => 'Пароль',
            'forgot_pass' => 'Забыли пароль?',
            'button_title' => 'Войти',
            'remember' => 'Запомнить меня',
            'footer' => '© Copyright: year Webkul Software, Все права защищены',
            'invalid-creds' => 'Проверьте свои учетные данные и повторите попытку.',
            'verify-first' => 'Сначала проверьте свою учетную запись электронной почты.',
            'not -active' => 'Ваша активация требует одобрения администратора',
            'resend-verification' => 'Отправить письмо с подтверждением еще раз'
        ],

        'forgot-password' => [
            'title' => 'Восстановить пароль',
            'email' => 'Электронная почта',
            'submit' => 'Отправить электронное письмо для сброса пароля',
            'page_title' => 'Забыли пароль?'
        ],

        'reset-password' => [
            'title' => 'Сбросить пароль',
            'email' => 'Зарегистрированный адрес электронной почты',
            'пароль' => 'Пароль',
            'confirm-password' => 'Подтвердить пароль',
            'back-link-title' => 'Вернуться к входу',
            'submit-btn-title' => 'Сбросить пароль'
        ],

        'account' => [
            'dashboard' => 'Редактировать профиль',
            'menu' => 'Меню',

            'general' => [
                'no' => 'Нет',
                'yes' => 'Да',
            ],

            'profile' => [
                'index' => [
                    'page-title' => 'Профиль',
                    'title' => 'Профиль',
                    'edit' => 'Редактировать',
                ],

                'edit-success' => 'Профиль успешно обновлен.',
                'edit-fail' => 'Ошибка! Профиль не может быть обновлен, повторите попытку позже. ',
                'unmatch' => 'Старый пароль не совпадает.',

                'fname' => 'Имя',
                'lname' => 'Фамилия',
                'gender' => 'Пол',
                'other' => 'Другой',
                'male' => 'Мужской',
                'female' => 'Женский',
                'dob' => 'Дата рождения',
                'phone' => 'Телефон',
                'email' => 'Электронная почта',
                'opassword' => 'Старый пароль',
                'password' => 'Пароль',
                'cpassword' => 'Подтвердить пароль',
                'submit' => 'Обновить профиль',

                'edit-profile' => [
                    'title' => 'Редактировать профиль',
                    'page-title' => 'Редактировать профиль'
                ]
            ],

            'address' => [
                'index' => [
                    'page-title' => 'Адрес',
                    'title' => 'Адрес',
                    'add' => 'Добавить адрес',
                    'edit' => 'Редактировать',
                    'empty' => 'Здесь нет сохраненных адресов, попробуйте создать его, нажав кнопку создания.',
                    'create' => 'Создать адрес',
                    'delete' => 'Удалить',
                    'make-default' => 'Использовать по умолчанию',
                    'default' => 'По умолчанию',
                    'contact' => 'Контакт',
                    'confirm-delete' => 'Вы действительно хотите удалить этот адрес?',
                    'default-delete' => 'Адрес по умолчанию не может быть изменен.',
                    'enter-password' => 'Введите свой пароль.',
                ],

                'create' => [
                    'page-title' => 'Добавить адрес',
                    'company_name' => 'Название компании',
                    'first_name' => 'Имя',
                    'last_name' => 'Фамилия',
                    'vat_id' => 'Идентификатор НДС',
                    'vat_help_note' => '[Примечание: используйте код страны с идентификатором НДС. Например. INV01234567891] ',
                    'title' => 'Добавить адрес',
                    'street-address' => 'Почтовый адрес',
                    'country' => 'Страна',
                    'state' => 'Область',
                    'select-state' => 'Выберите регион, штат или провинцию',
                    'city' => 'Город',
                    'postcode' => 'Почтовый индекс',
                    'phone' => 'Телефон',
                    'submit' => 'Сохранить адрес',
                    'success' => 'Адрес успешно добавлен.',
                    'error' => 'Адрес не может быть добавлен.' 
                ],

                'edit' => [
                    'page-title' => 'Изменить адрес',
                    'company_name' => 'Название компании',
                    'first_name' => 'Имя',
                    'last_name' => 'Фамилия',
                    'vat_id' => 'Идентификатор НДС',
                    'title' => 'Изменить адрес',
                    'street-address' => 'Почтовый адрес',
                    'submit' => 'Сохранить адрес',
                    'success' => 'Адрес успешно обновлен.',
                ],
                'delete' => [
                    'success' => 'Адрес успешно удален',
                    'failure' => 'Адрес не может быть удален',
                    'wrong-password' => 'Неверный пароль!'
                ]
            ],

            'order' => [
                'index' => [
                    'page-title' => 'Заказы',
                    'title' => 'Заказы',
                    'order_id' => 'Идентификатор заказа',
                    'date' => 'Дата',
                    'status' => 'Статус',
                    'total' => 'Итого',
                    'order_number' => 'Номер заказа',
                    'processing' => 'Обработка',
                    'completed' => 'Завершено',
                    'cancelled' => 'Отменено',
                    'closed' => 'Закрыто',
                    'pending' => 'Ожидание',
                    'pending-payment' => 'Ожидающий платеж',
                    'fraud' => 'Мошенничество'
                ],

                'view' => [
                    'page-tile' => 'Заказ номер #:order_id',
                    'info' => 'Информация',
                    'placed-on' => 'Размещено',
                    'products-ordered' => 'Товары заказаны',
                    'invoices' => 'Счета',
                    'shipments' => 'Отгрузки',
                    'SKU' => 'SKU',
                    'product-name' => 'Имя',
                    'qty' => 'Количество',
                    'item-status' => 'Статус товара',
                    'item-ordered' => 'Заказано (:qty_ordered)',
                    'item-invoice' => 'Счет (:qty_invoiced)',
                    'item-shipped' => 'Отправлено (:qty_shipped)',
                    'item-canceled' => 'Отменено (:qty_canceled)',
                    'item-refunded' => 'Возвращено (:qty_refunded)',
                    'price' => 'Цена',
                    'total' => 'Итого',
                    'subtotal' => 'Промежуточный итог',
                    'shipping-Handling' => 'Доставка и обработка',
                    'tax' => 'Налог',
                    'discount' => 'Скидка',
                    'tax-percent' => 'Налоговый процент',
                    'tax-amount' => 'Сумма налога',
                    'discount-amount' => 'Размер скидки',
                    'grand-total' => 'Общая сумма',
                    'total-paid' => 'Всего оплачено',
                    'total-refunded' => 'Всего возмещено',
                    'total-due' => 'Итого к оплате',
                    'shipping-address' => 'Адрес доставки',
                    'billing-address' => 'Платежный адрес',
                    'shipping-method' => 'Способ доставки',
                    'payment-method' => 'Способ оплаты',
                    'individual-invoice' => 'Счет #:invoice_id',
                    'individual-shipment' => 'Номер отправки #:shipment_id',
                    'print' => 'Печать',
                    'invoice-id' => 'Номер счета',
                    'order-id' => 'Номер отправки',
                    'order-date' => 'Дата заказа',
                    'bill-to' => 'Выставить счет',
                    'ship-to' => 'Отправить',
                    'contact' => 'Контакт',
                    'refunds' => 'Возврат',
                    'individual-refund' => 'Возврат #:refund_id',
                    'adjustment-refund' => 'Возврат корректировки',
                    'adjustment-fee' => 'лата за корректировку',
                    'cancel-btn-title' => 'Отменить',
                    'tracking-number' => 'Номер для отслеживания',
                    'cancel-confirm-msg' => 'Вы действительно хотите отменить этот заказ?'
                ]
            ],

            'wishlist' => [
                'page-title' => 'Список желаний',
                'title' => 'Список желаний',
                'deleteall' => 'Удалить все',
                'moveall' => 'Переместить все товары в корзину',
                'move-to-cart' => 'Переместить в корзину',
                'error' => 'Невозможно добавить продукт в список желаний из-за неизвестных проблем, пожалуйста, проверьте позже',
                'add' => 'Товар успешно добавлен в список желаний',
                'remove' => 'Товар успешно удален из списка желаний',
                'add-wishlist-text' => 'Добавить продукт в список желаний',
                'remove-wishlist-text' => 'Удалить продукт из списка желаний',
                'moved' => 'Товар успешно перемещен в корзину',
                'option-missing' => 'Параметры продукта отсутствуют, поэтому элемент нельзя переместить в список желаний.',
                'move-error' => 'Товар не может быть перемещен в список желаний. Повторите попытку позже',
                'success' => 'Товар успешно добавлен в список желаний',
                'failure' => 'Товар не может быть добавлен в список желаний. Повторите попытку позже',
                'already' => 'Товар уже присутствует в вашем списке желаний',
                'removed' => 'Товар успешно удален из списка желаний',
                'remove-fail' => 'Элемент не может быть удален из списка желаний, попробуйте еще раз позже',
                'empty' => 'У вас нет товаров в вашем списке желаний',
                'remove-all-success' => 'Все элементы из вашего списка желаний были удалены',
            ],

            'downloadable_products' => [
                'title' => 'Продукты для скачивания',
                'order-id' => 'Идентификатор заказа',
                'date' => 'Дата',
                'name' => 'Заголовок',
                'status' => 'Статус',
                'pending' => 'Ожидание',
                'available' => 'Доступен',
                'expired' => 'Срок действия истек',
                'remaining-downloads' => 'Оставшиеся загрузки',
                'unlimited' => 'Безлимитный',
                'download-error' => 'Срок действия ссылки для скачивания истек.',
                'payment-error' => 'Платеж не был произведен для этой загрузки.'
            ],

            'review' => [
                'index' => [
                    'title' => 'Отзывы',
                    'page-title' => 'Отзывы'
                ],

                'view' => [
                    'page-tile' => 'Отзыв #:id',
                ]
            ]
        ]
    ],

    'products' => [
        'layered-nav-title' => 'Фильтровать по',
        'price-label' => 'до',
        'remove-filter-link-title' => 'Clear All',
        'filter-to' => 'до',
        'sort-by' => 'Сортировать по',
        'from-a-z' => 'от А до Я',
        'from-z-a' => 'от Я до A',
        'newest-first' => 'Сначала самые новые',
        'oldest-first' => 'Сначала самые старые',
        'cheapest-first' => 'Сначала самые дешевые',
        'expensive-first' => 'Сначала самые дорогие',
        'show' => 'Показать',
        'pager-info' => 'Показано :showing из :total товаров',
        'description' => 'Описание',
        'specification' => 'Спецификация',
        'total-reviews' => ':total отзывов',
        'total-rating' => ':total_rating рейтинг и :total_reviews отзывы',
        'by' => 'По :name',
        'up-sell-title' => 'Мы нашли другие товары, которые могут вам понравиться!',
        'related-product-title' => 'Сопутствующие товары',
        'cross-sell-title' => 'Больше вариантов',
        'reviews-title' => 'Рейтинги и отзывы',
        'write-review-btn' => 'Написать отзыв',
        'choose-option' => 'Выбрать вариант',
        'sale' => 'Распродажа',
        'new' => 'Новый',
        'empty' => 'В этой категории нет товаров',
        'add-to-cart' => 'В корзину',
        'book-now' => 'Забронировать сейчас',
        'buy-now' => 'Купить сейчас',
        'whoops' => 'Упс!',
        'quantity' => 'Количество',
        'in-stock' => 'Есть в наличии',
        'out-of-stock' => 'Нет в наличии',
        'view-all' => 'Просмотреть все',
        'select-above-options' => 'Сначала выберите указанные выше параметры.',
        'less-amount' => 'Количество не может быть меньше единицы.',
        'samples' => 'Образцы',
        'links' => 'Ссылки',
        'sample' => 'Образец',
        'name' => 'Имя',
        'qty' => 'Кол-во',
        'start-at' => 'Начиная с',
        'customize-options' => 'Параметры настройки',
        'choose-selection' => 'Выбрать выбор',
        'your-customization' => 'Ваша настройка',
        'total-amount' => 'Общая сумма',
        'none' => 'Нет',
        'available-for-order' => 'Доступно для заказа',
        'settings' => 'Настройки',
        'compare_options' => 'Сравнить параметры',
        'wishlist-options' => 'Параметры списка желаний',
        'offers' => 'Купить :qty за :price каждую и сэкономить  :discount%',
    ],

    // 'reviews' => [
    //     'empty' => 'You Have Not Reviewed Any Of Product Yet'
    // ]

    'buynow' => [
        'no-options' => 'Пожалуйста, выберите параметры перед покупкой этого продукта'
    ],

    'checkout' => [
        'cart' => [
            'integrity' => [
                'missing_fields' => 'Некоторые обязательные поля отсутствуют для этого продукта.',
                'missing_options' => 'Для этого продукта отсутствуют параметры.',
                'missing_links' => 'Для этого продукта отсутствуют ссылки для скачивания.',
                'qty_missing' => 'По крайней мере один товар должен иметь более 1 количества.',
                'qty_impossible' => 'Нельзя добавить более одного из этих продуктов в корзину.'
            ],
            'create-error' => 'Возникла проблема при создании корзины.',
            'title' => 'Корзина',
            'empty' => 'Ваша корзина пуста',
            'update-cart' => 'Обновить корзину',
            'continue-shopping' => 'Продолжить покупки',
            'continue-to-checkout' => 'Перейти к оформлению',
            'remove' => 'Удалить',
            'remove-link' => 'Удалить',
            'move-to-wishlist' => 'Переместить в список желаний',
            'move-to-wishlist-success' => 'Товар успешно перемещен в список желаний.',
            'move-to-wishlist-error' => 'Невозможно переместить товар в список желаний, повторите попытку позже.',
            'add-config-warning' => 'Пожалуйста, выберите опцию перед добавлением в корзину.',
            'quantity' => [
                'quantity' => 'Количество',
                'success' => 'Товар(ы) корзины успешно обновлен.',
                'illegal' => 'Количество не может быть меньше единицы.',
                'inventory_warning' => 'Запрошенное количество недоступно, повторите попытку позже.',
                'error' => 'Не удается обновить товары в данный момент, повторите попытку позже.'
            ],

            'item' => [
                'error_remove' => 'Нет товаров для удаления из корзины.',
                'success' => 'Товар был успешно добавлен в корзину.',
                'success-remove' => 'Товар был успешно удален из корзины.',
                'error-add' => 'Товар не может быть добавлен в корзину, повторите попытку позже.',
                'inactive' => 'Товар неактивен и был удален из корзины.',
                'inactive-add' => 'Неактивный товар нельзя добавить в корзину.',
            ],
            'quantity-error' => 'Запрошенное количество недоступно.',
            'cart-subtotal' => 'Итого по корзине',
            'cart-remove-action' => 'Вы действительно хотите это сделать?',
            'partial-cart-update' => 'Обновлены только некоторые товары',
            'link-missing' => '',
            'event' => [
                'expired' => 'Срок действия истек.'
            ],
            'minimum-order-message' => 'Минимальная сумма заказа :amount'
        ],

        'onepage' => [
            'title' => 'Оформить заказ',
            'information' => 'Информация',
            'shipping' => 'Доставка',
            'payment' => 'Оплата',
            'complete' => 'Завершено',
            'review' => 'Обзор',
            'billing-address' => 'Платежный адрес',
            'sign-in' => 'Войти',
            'company-name' => 'Название компании',
            'first-name' => 'Имя',
            'last-name' => 'Фамилия',
            'email' => 'Электронная почта',
            'address1' => 'Почтовый адрес',
            'city' => 'Город',
            'state' => 'Состояние',
            'select-state' => 'Выберите регион, штат или провинцию',
            'postcode' => 'Почтовый индекс',
            'phone' => 'Телефон',
            'country' => 'Страна',
            'order-summary' => 'Сводка заказа',
            'shipping-address' => 'Адрес доставки',
            'use_for_shipping' => 'Отправить по этому адресу',
            'continue' => 'Продолжить',
            'shipping-method' => 'Выбрать способ доставки',
            'payment-methods' => 'Выбрать способ оплаты',
            'payment-method' => 'Способ оплаты',
            'summary' => 'Сводка заказа',
            'price' => 'Цена',
            'quantity' => 'Количество',
            'billing-address' => 'Платежный адрес',
            'shipping-address' => 'Адрес доставки',
            'contact' => 'Контакт',
            'place-order' => 'Разместить заказ',
            'new-address' => 'Добавить новый адрес',
            'save_as_address' => 'Сохранить этот адрес',
            'apply-coupon' => 'Применить купон',
            'amt-payable' => 'Сумма к оплате',
            'got' => 'Получил',
            'free' => 'Бесплатно',
            'coupon-used' => 'Купон использован',
            'applied' => 'Применено',
            'back' => 'Назад',
            'cash-desc' => 'Наличными при доставке',
            'money-desc' => 'Денежный перевод',
            'paypal-desc' => 'Стандартный Paypal',
            'free-desc' => 'Это бесплатная доставка',
            'flat-desc' => 'Это фиксированная ставка',
            'password' => 'Пароль',
            'login-exist-message' => 'У вас уже есть учетная запись у нас, войдите или продолжите как гость.',
            'enter-coupon-code' => 'Введите код купона'
        ],

        'total' => [
            'order-summary' => 'Сводка заказа',
            'sub-total' => 'Предметы',
            'grand-total' => 'Общая сумма',
            'delivery-charge' => 'Стоимость доставки',
            'tax' => 'Налог',
            'discount' => 'Скидка',
            'price' => 'price',
            'disc-amount' => 'Сумма со скидкой',
            'new-grand-total' => 'Новый общий итог',
            'coupon' => 'Купон',
            'coupon-applied' => 'Примененный купон',
            'remove-coupon' => 'Удалить купон',
            'cannot-apply-coupon' => 'Невозможно применить купон',
            'invalid-coupon' => 'Код купона недействителен.',
            'success-coupon' => 'Код купона успешно применен.',
            'coupon-apply-issue' => 'Код купона не может быть применен.'
        ],

        'success' => [
            'title' => 'Заказ успешно размещен',
            'thanks' => 'Спасибо за заказ!',
            'order-id-info' => 'Номер заказа #:order_id',
            'info' => 'Мы отправим вам электронное письмо с деталями вашего заказа и информацией об отслеживании'
        ]
    ],

    'mail' => [
        'order' => [
            'subject' => 'Подтверждение нового заказа',
            'heading' => 'Подтверждение заказа!',
            'dear' => 'Уважаемый :customer_name',
            'dear-admin' => 'Уважаемый :admin_name',
            'greeting' => 'Спасибо за Ваш заказ :order_id созданный в :created_at',
            'greeting-admin' => 'Номер заказа :order_id созданный в :created_at',
            'summary' => 'Итог по заказу',
            'shipping-address' => 'Адрес доставки',
            'billing-address' => 'Платежный адрес',
            'contact' => 'Контакт',
            'shipping' => 'Способ доставки',
            'payment' => 'Способ оплаты',
            'price' => 'Цена',
            'quantity' => 'Количество',
            'subtotal' => 'Промежуточный итог',
            'shipping-handling' => 'Доставка и обработка',
            'tax' => 'Налог',
            'discount' => 'Скидка',
            'grand-total' => 'Общая сумма',
            'final-summary' => 'Спасибо за проявленный интерес к нашему магазину, мы вышлем вам номер для отслеживания, как только он будет отправлен',
            'help' => 'Если вам нужна помощь, свяжитесь с нами по адресу :support_email',
            'thanks' => 'Спасибо!',

            'comment' => [
                'subject' => 'Добавлен новый комментарий к вашему заказу, номер #:order_id',
                'dear' => 'Уважаемый :customer_name',
                'final-summary' => 'Спасибо за проявленный интерес к нашему магазину!',
                'help' => 'Если вам нужна помощь, свяжитесь с нами по адресу :support_email',
                'thanks' => 'Спасибо!',
            ],

            'cancel' => [
                'subject' => 'Подтверждение отмены заказа',
                'heading' => 'Отмена заказа',
                'dear' => 'Уважаемый :customer_name',
                'greeting' => 'Ваш заказ, номер :order_id placed создан в :created_at был отменен',
                'summary' => 'Итог по заказу',
                'shipping-address' => 'Адрес доставки',
                'billing-address' => 'Платежный адрес',
                'contact' => 'Контакт',
                'shipping' => 'Способ доставки',
                'payment' => 'Способ оплаты',
                'subtotal' => 'Промежуточный итог',
                'shipping-handling' => 'Доставка и обработка',
                'tax' => 'Налог',
                'discount' => 'Скидка',
                'grand-total' => 'Общая сумма',
                'final-summary' => 'Спасибо за проявленный интерес к нашему магазину!',
                'help' => 'Если вам нужна помощь, свяжитесь с нами по адресу :support_email',
                'thanks' => 'Спасибо!',
            ]
        ],

        'invoice' => [
            'heading' => 'Номер счета #:invoice_id за заказ, номер #:order_id',
            'subject' => 'Счет за заказ, номер #:order_id',
            'summary' => 'Итог по счету',
        ],

        'shipment' => [
            'heading' => 'Доставка #:shipment_id была создана для заказа, номер #:order_id',
            'inventory-heading' => 'Новая доставка #:shipment_id для заказа, номер #:order_id была создана',
            'subject' => 'Доставка для заказа #:order_id',
            'inventory-subject' => 'Новая доставка была создана, для  заказа, номер #:order_id',
            'summary' => 'Итог по доставке',
            'carrier' => 'Перевозчик',
            'tracking-number' => 'Номер для отслеживания',
            'greeting' => 'Заказ, №:order_id был создан :created_at',
        ],

        'refund' => [
            'heading' => 'Ваш возврат #:refund_id по заказу, №#:order_id',
            'subject' => 'Возврат денег за ваш заказ, №#:order_id',
            'summary' => 'Итог по возврату',
            'adjustment-refund' => 'Возврат корректировки',
            'adjustment-fee' => 'Плата за корректировку '
        ],

        'forget-password' => [
            'subject' => 'Пароль для сброса пароля клиента',
            'dear' => 'Уважаемый :name',
            'info' => 'Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи',
            'reset-password' => 'Сбросить пароль',
            'final-summary' => 'Если вы не запрашивали сброс пароля, дальнейшие действия не требуются',
            'thanks' => 'Спасибо!'
        ],

        'update-password' => [
            'subject' => 'Пароль обновлен',
            'dear' => 'Уважаемый :name',
            'info' => 'Вы получили это письмо, потому что обновили свой пароль.',
            'thanks' => 'Спасибо!'
        ],

        'customer' => [
            'new' => [
                'dear' => 'Уважаемый :customer_name',
                'username-email' => 'Логин/Email',
                'subject' => 'Регистрация нового клиента ',
                'password' => 'Пароль',
                'summary' => 'Ваш аккаунт был создан. Данные вашей учетной записи приведены ниже: ',
                'thanks' => 'Спасибо!',
            ],

            'registration' => [
                'subject' => 'Регистрация нового клиента',
                'customer-registration' => 'Клиент успешно зарегистрирован',
                'dear' => 'Уважаемый :customer_name',
                'greeting' => 'Добро пожаловать и благодарим вас за регистрацию у нас!',
                'summary' => 'Ваша учетная запись была успешно создана, и вы можете войти в систему, используя свой адрес электронной почты и пароль. После входа в систему вы сможете получить доступ к другим услугам, включая просмотр прошлых заказов, списков желаний и редактирование информации вашей учетной записи.',
                'thanks' => 'Спасибо!',
            ],

            'verification' => [
                'heading' => config('app.name') . ' - Email подтверждение',
                'subject' => 'EMail подтверждение',
                'verify' => 'Подтверждение профиля',
                'summary' => 'Это письмо для подтверждения того, что введенный вами адрес электронной почты является вашим.
                Пожалуйста, нажмите кнопку Подтвердить свою учетную запись ниже, чтобы подтвердить свою учетную запись. '
            ],

            'subscription' => [
                'subject' => 'Email для подписки',
                'greeting' => ' Поздравляем ' . config('app.name') . ' - Вы подписались на нашу рассылку',
                'unsubscribe' => 'Отписаться',
                'summary' => 'Спасибо, что добавли меня в свой почтовый ящик. Вы давно не читали ' . config('app.name') . ' email, и мы не хотим перегружать ваш почтовый ящик. Если вы все еще не хотите получать последние новости, обязательно нажмите кнопку ниже.'
            ]
        ]
    ],

    'webkul' => [
        'copy-right' => '© Copyright :year Webkul Software, Все права защищены',
    ],

    'response' => [
        'create-success' => ':name успешно создан.',
        'update-success' => ':name успешно обновлен.',
        'delete-success' => ':name успешно удален.',
        'submit-success' => ':name успешно отправлено.'
    ],
];
