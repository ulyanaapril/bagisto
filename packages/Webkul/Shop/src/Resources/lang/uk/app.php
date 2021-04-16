<?php

return [
    'invalid_vat_format' => 'Даний ідентифікатор ПДВ має неправильний формат',
    'security-warning' => 'Знайдено підозрілу активність !!!',
    'nothing-to-delete' => 'Нічого для видалення',

    'layouts' => [
        'my-account' => 'Мій аккаунт',
        'profile' => 'Профіль',
        'address' => 'Адреса',
        'reviews' => 'Відгуки',
        'wishlist' => 'Список бажань',
        'orders' => 'Замовлення',
        'downloadable-products' => 'Завантажувані продукти'
    ],

    'common' => [
        'error' => 'Щось пішло не так, будь ласка, спробуйте пізніше.',
        'image-upload-limit' => 'Максимальний розмір зображення для завантаження - 2 МБ',
        'no-result-found' => 'Ми не змогли знайти жодного запису.'
    ],

    'home' => [
        'page-title' => config('app.name') . '- Дім',
        'Featured-products' => 'Рекомендовані продукти',
        'new-products' => 'Нові продукти',
        'verify-email' => 'Підтвердьте свій рахунок електронної пошти',
        'resend-verify-email' => 'Повторно надіслати електронний лист для підтвердження'
    ],

    'header' => [
        'title' => 'Обліковий запис',
        'dropdown-text' => 'Керувати кошиком, замовленнями та списком бажань',
        'sign-in' => 'Увійти',
        'sign-up' => 'Зареєструватися',
        'account' => 'Обліковий запис',
        'cart' => 'Кошик',
        'profile' => 'Профіль',
        'wishlist' => 'Список бажань',
        'logout' => 'Вийти',
        'search-text' => 'Шукати продукти тут'
    ],

    'minicart' => [
        'view-cart' => 'Переглянути кошик',
        'checkout' => 'Оформити замовлення',
        'cart' => 'Кошик',
        'zero' => '0'
    ],

    'footer' => [
        'subscribe-newsletter' => 'Підписатися на розсилку',
        'subscribe' => 'Підписатися',
        'locale' => 'Локаль',
        'currency' => 'Валюта',
    ],

    'subscription' => [
        'unsubscribe' => 'Скасувати підписку',
        'subscribe' => 'Підписатися',
        'subscribed' => 'Ви тепер передплачені на електронні листи з передплатою.',
        'not-subscribed' => 'Ви не можете підписатися на електронні листи з підпискою, спробуйте пізніше.',
        'already' => 'Ви вже підписалися на наш список підписок.',
        'unsubscribed' => 'Ви скасували підписку на підписки.',
        'already-unsub' => 'Ви вже відписалися.',
        'not-subscribed' => 'Помилка! Наразі неможливо надіслати пошту, спробуйте пізніше. '
    ],

    'search' => [
        'no-results' => 'Результатів не знайдено',
        'page-title' => config('app.name') . '- Пошук',
        'found-results' => 'результати пошуку',
        'found-result' => 'Результат пошуку знайдений',
        'analysed-keywords' => 'Проаналізовані ключові слова',
        'image-search-option' => 'Параметр пошуку зображень'
    ],

    'reviews' => [
        'title' => 'Заголовок',
        'add-review-page-title' => 'Додати відгук',
        'write-review' => 'Написати відгук',
        'review-title' => 'Дайте рецензії заголовок',
        'product-review-page-title' => 'Огляд товару',
        'rating-reviews' => 'Рейтинг та відгуки',
        'submit' => 'ПОДАТИ',
        'delete-all' => 'Усі відгуки вдало видалено',
        'ratingreviews' => ':rating Ratings & :review Reviews',
        'star' => 'Зірка',
        'percentage' => ':percentage%',
        'id-star' => 'зірка',
        'name' => "Ім\'я",
    ],

    'customer' => [
        'compare' => [
            'text' => 'Порівняти',
            'compare_s similar_items' => 'Порівняти схожі елементи',
            'add-tooltip' => 'Додати товар до списку порівняння',
            'added' => 'Елемент успішно додано до списку порівняння',
            'already_added' => 'Елемент вже додано до списку порівняння',
            'remove' => 'Елемент успішно видалено зі списку порівняння',
            'remove-all' => 'Усі елементи успішно видалено зі списку порівняння',
            'empty-text' => "У вас немає елементів у вашому списку порівняння",
            'product_image' => 'Зображення продукту',
            'actions' => 'Дії',
        ],

        'signup-text' => [
            'account_exists' => 'Уже є рахунок',
            'title' => 'Увійти'
        ],

        'signup-form' => [
            'page-title' => 'Створити новий обліковий запис клієнта',
            'title' => 'Зареєструватися',
            'firstname' => "Ім\'я",
            'lastname' => 'Прізвище',
            'email' => 'Електронна пошта',
            'password' => 'Пароль',
            'confirm_pass' => 'Підтвердити пароль',
            'button_title' => 'Зареєструватися',
            'accept' => 'Погодьтесь',
            'terms' => 'Умови',
            'conditions' => "Умови",
            'using' => 'за допомогою цього веб-сайту',
            'agreement' => 'Угода',
            'subscribe-to-newsletter' => 'Підписатися на розсилку',
            'success' => 'Обліковий запис створено успішно.',
            'success-verify' => 'Обліковий запис створено успішно, на перевірку надіслано електронне повідомлення.',
            'success-verify-email-unsent' => 'Створення облікового запису діяв успішно, але електронний лист для підтвердження не надіслано. ',
            'failed' => 'Помилка! Не вдається створити свій обліковий запис, прошу спробувати пізніше. ',
            'already-verify' => 'Ваш обліковий запис уже підтверджено Або спробуйте надіслати новий електронний лист із підтвердженням ще раз.',
            'verify-not-sent' => 'Помилка! Проблема з надсиланням електронного листа з підтвердженням, спробуйте пізніше. ',
            'verify-sent' => 'Електронний лист для підтвердження надіслано',
            'verify' => 'Ваш рахунок підтверджено, спробуйте увійти зараз.',
            'verify-failed' => 'Ми не можемо підтвердити ваш поштовий рахунок.',
            'dont-have-account' => 'У вас немає облікового запису у нас.',
            'customer-registration' => 'Клієнт зареєстрований успішно'
        ],

        'login-text' => [
            'no_account' => 'Не мати облікового запису',
            'title' => 'Зареєструватися',
        ],

        'login-form' => [
            'page-title' => 'Вхід клієнта',
            'title' => 'Увійти',
            'email' => 'Електронна пошта',
            'password' => 'Пароль',
            'Forgot_pass' => 'Забули пароль?',
            'button_title' => 'Увійти',
            'remember' => "Запам\'ятати мене",
            'footer' => '© Авторське право :year Red, Усі права захищені',
            'invalid-creds' => 'Перевірте свої облікові дані та повторіть спробу.',
            'verify-first' => 'Спочатку підтвердьте свій рахунок електронної пошти.',
            'not-enabled' => 'Ваша активація вимагає схвалення адміністратора',
            'resend-verify' => 'Повторно надіслати підтвердження ще раз'
        ],

        'forgot-password' => [
            'title' => 'Відновити пароль',
            'email' => 'Електронна пошта',
            'submit' => 'Надіслати пароль для скидання пароля',
            'page_title' => 'Забули пароль?'
        ],

        'reset-password' => [
            'title' => 'Скинути пароль',
            'email' => 'Зареєстрована електронна пошта',
            'password' => 'Пароль',
            'confirm-password' => 'Підтвердити пароль',
            'back-link-title' => 'Повернутися до входу',
            'submit-btn-title' => 'Скинути пароль'
        ],

        'account' => [
            'dashboard' => 'Редагувати профіль',
            'menu' => 'Меню',

            'general' => [
                'no' => 'Ні',
                'yes' => 'Так',
            ],

            'profile' => [
                'index' => [
                    'page-title' => 'Профіль',
                    'title' => 'Профіль',
                    'edit' => 'Редагувати',
                ],

                'edit-success' => 'Профіль успішно оновлено.',
                'edit-fail' => 'Помилка! Профіль неможливо оновити, повторіть спробу пізніше. ',
                'unmatch' => 'Старий пароль не відповідає.',

                'fname' => "Ім\'я",
                'lname' => 'Прізвище',
                'gender' => 'Стать',
                'other' => 'Інше',
                'male' => 'Чоловік',
                'female' => 'Жінка',
                'dob' => 'Дата народження',
                'phone' => 'Телефон',
                'email' => 'Електронна пошта',
                'opassword' => 'Старий пароль',
                'password' => 'Пароль',
                'cpassword' => 'Підтвердити пароль',
                'submit' => 'Оновити профіль',

                'edit-profile' => [
                    'title' => 'Редагувати профіль',
                    'page-title' => 'Редагувати профіль'
                ]
            ],

            'address' => [
                'index' => [
                    'page-title' => 'Адреса',
                    'title' => 'Адреса',
                    'add' => 'Додати адресу',
                    'edit' => 'Редагувати',
                    'empty' => 'У вас немає жодної збереженої адреси, спробуйте створити її, натиснувши кнопку додати.',
                    'create' => 'Створити адресу',
                    'delete' => 'Видалити',
                    'make-default' => 'Зробити за замовчуванням',
                    'default' => 'За замовчуванням',
                    'contact' => 'Контакт',
                    'confirm-delete' => 'Ви дійсно хочете видалити цю адресу?',
                    'default-delete' => 'Адресу за замовчуванням не можна змінити.',
                    'enter-password' => 'Введіть свій пароль.',
                ],

                'create' => [
                    'page-title' => 'Додати адресу',
                    'company_name' => 'Назва компанії',
                    'first_name' => "Ім\'я",
                    'last_name' => 'Прізвище',
                    'vat_id' => 'Ідентифікатор ПДВ',
                    'vat_help_note' => '[Примітка: Використовуйте код країни з ідентифікаційним номером ПДВ. Напр. INV01234567891] ',
                    'title' => 'Додати адресу',
                    'street-address' => 'Вулиця',
                    'country' => 'Країна',
                    'state' => 'Штат',
                    'select-state' => 'Вибрати регіон, штат або провінцію',
                    'city' => 'Місто',
                    'postcode' => 'Поштовий індекс',
                    'phone' => 'Телефон',
                    'submit' => 'Зберегти адресу',
                    'success' => 'Адресу успішно додано.',
                    'error' => 'Адресу не можна додати.'
                ],

                'edit' => [
                    'page-title' => 'Редагувати адресу',
                    'company_name' => 'Назва компанії',
                    'first_name' => "Ім\'я",
                    'last_name' => 'Прізвище',
                    'vat_id' => 'Ідентифікатор ПДВ',
                    'title' => 'Редагувати адресу',
                    'street-address' => 'Вулиця',
                    'submit' => 'Зберегти адресу',
                    'success' => 'Адресу успішно оновлено.',
                ],
                'delete' => [
                    'success' => 'Адресу успішно видалено',
                    'failure' => 'Адресу не можна видалити',
                    'wrong-password' => 'Неправильний пароль!'
                ]
            ],

            'order' => [
                'index' => [
                    'page-title' => 'Замовлення',
                    'title' => 'Замовлення',
                    'order_id' => 'Ідентифікатор замовлення',
                    'date' => 'Дата',
                    'status' => 'Статус',
                    'total' => 'Всього',
                    'order_number' => 'Номер замовлення',
                    'processing' => 'Обробка',
                    'completed' => 'Завершено',
                    'cancelled' => 'Скасовано',
                    'closed' => 'Закрито',
                    'pending' => 'Очікує',
                    'pending-payment' => 'Очікує на оплату',
                    'fraud' => 'Шахрайство'
                ],

                'view' => [
                    'page-tile' => 'Номер замовлення #:order_id',
                    'info' => 'Інформація',
                    'located-on' => 'Вміщено',
                    'products-order' => 'Замовлена ​​продукція',
                    'invoices' => 'Рахунки-фактури',
                    'shipments' => 'Відвантаження',
                    'SKU' => 'SKU',
                    'product-name' => 'Назва',
                    'qty' => 'Кількість',
                    'item-status' => 'Статус елемента',
                    'item-order' => 'Впорядковано (:qty_ordered)',
                    'item-invoice' => 'Рахунок-фактура (:qty_invoiced)',
                    'item-shipped' => 'відправлено (:qty_shipped)',
                    'item-cancelled' => 'Скасовано (:qty_canceled)',
                    'item-refunded' => 'Відшкодовано (:qty_refunded)',
                    'price' => "Ціна",
                    'total' => 'Всього',
                    'subtotal' => 'Проміжний підсумок',
                    'shipping-handling' => 'Доставка та обробка',
                    'tax' => 'податок',
                    'discount' => 'Знижка',
                    'tax-percent' => 'Податковий відсоток',
                    'tax-sum' => 'Сума податку',
                    'discount-amount' => 'Сума знижки',
                    'grand-total' => 'Загальний підсумок',
                    'total-paid' => 'Всього сплачено',
                    'total-refunded' => 'Загальна сума відшкодована',
                    'total-due' => 'Total dolg',
                    'shipping-address' => 'Адреса доставки',
                    'billing-address' => 'Адреса виставлення рахунку',
                    'shipping-method' => 'Спосіб доставки',
                    'Payment-method' => 'Спосіб оплати',
                    'individual-invoice' => 'Рахунок-фактура #:invoice_id',
                    'individual-shipment' => 'Номер доставки #:shipment_id',
                    'print' => 'Друк',
                    'invoice-id' => 'Ідентифікатор рахунка-фактури',
                    'order-id' => 'Ідентифікатор замовлення',
                    'order-date' => 'Дата замовлення',
                    'bill-to' => 'Оплатити',
                    'ship-to' => 'Відправити до',
                    'contact' => 'Контакт',
                    'refunds' => 'Відшкодування',
                    'individual-refund' => 'Повернення #:refund_id',
                    'correction-refund' => 'Відшкодування коригування',
                    'correction-fee' => 'Збір за коригування',
                    'cancel-btn-title' => 'Скасувати',
                    'tracking-number' => 'Номер відстеження',
                    'cancel-confirm-msg' => 'Ви впевнені, що хочете скасувати це замовлення?'
                ]
            ],

            'wishlist' => [
                'page-title' => 'Список бажань',
                'title' => 'Список бажань',
                'deleteall' => 'Видалити все',
                'moveall' => 'Перемістити всі товари до кошика',
                'move-to-cart' => 'Перемістити в кошик',
                'error' => 'Неможливо додати товар до списку бажань через невідомі проблеми, перевірте пізніше',
                'add' => 'Елемент успішно додано до списку бажань',
                'remove' => 'Елемент успішно видалено зі списку бажань',
                'add-wishlist-text' => 'Додати товар у список бажань',
                'remove-wishlist-text' => 'Видалити продукт зі списку бажань',
                'moved' => 'Елемент успішно переміщено в кошик',
                'option-missing' => 'Параметри товару відсутні, тому елемент не можна перемістити до списку бажань.',
                'move-error' => 'Елемент не можна перемістити до списку бажань, спробуйте пізніше',
                'success' => 'Елемент успішно додано до списку бажань',
                'failure' => 'Елемент не можна додати до списку бажань, спробуйте пізніше',
                'already' => 'Елемент вже присутній у вашому списку бажань',
                'remove-fail' => 'Елемент не можна видалити зі списку бажань, спробуйте пізніше',
                'empty' => 'У вас немає елементів у вашому списку бажань',
                'remove-all-success' => 'Усі елементи з вашого списку бажань були видалені',
            ],

            'downloadable_products' => [
                'title' => 'Завантажувані продукти',
                'order-id' => 'Ідентифікатор замовлення',
                'date' => 'Дата',
                'name' => 'Назва',
                'status' => 'Статус',
                'pending' => 'Очікує',
                'available' => 'Доступно',
                'expired' => 'Термін дії закінчився',
                'preostali-завантаження' => 'Залишкові завантаження',
                'unlimited' => 'Необмежено',
                'download-error' => 'Термін дії посилання для завантаження минув.',
                'payment-error' => 'Оплата за це завантаження не здійснена.'
            ],

            'review' => [
                'index' => [
                    'title' => 'Відгуки',
                    'page-title' => 'Відгуки'
                ],

                'view' => [
                    'page-tile' => 'Огляд #:id',
                ]
            ]
        ]
    ],

    'products' => [
        'layered-nav-title' => 'Покупки',
        'price-label' => '',
        'remove-filter-link-title' => 'Очистити все',
        'filter-to' => 'до',
        'sort-by' => 'Сортувати за',
        'from-a-z' => 'Від А-Я',
        'from-z-a' => 'Від Я-А',
        'newest-first' => 'Нові спочатку',
        'oldest-first' => 'Старіші спочатку',
        'cheapest-first' => 'Найдешевші спочатку',
        'expensive-first' => 'Дорожчі спочатку',
        'show' => 'Показати',
        'pager-info' => 'Показ :showing з :total елементів',
        'description' => 'Опис',
        'specification' => 'Специфікація',
        'total-reviews' => ':total відгуків',
        'total-rating' => ':total_rating загальної оцінки & :total_reviews відгуки',
        'by' => 'Автор :name',
        'up-sell-title' => 'Ми знайшли інші продукти, які можуть вам сподобатися!',
        'related-product-title' => 'Супутні товари',
        'cross-sell-title' => 'Інші варіанти',
        'reviews-title' => 'Оцінки та відгуки',
        'write-review-btn' => 'Написати відгук',
        'choose-option' => 'Вибрати варіант',
        'sale' => 'Продаж',
        'new' => 'Нове',
        'empty' => 'У цій категорії немає товарів',
        'add-to-cart' => 'Додати в кошик',
        'book-now' => "Забронювати зараз",
        'buy-now' => 'Купити зараз',
        'whoops' => 'Whoops!',
        'quantity' => 'Кількість',
        'in-stock' => 'В наявності',
        'out-of-stock' => 'Немає в наявності',
        'view-all' => 'Переглянути всі',
        'select-above-options' => 'Будь ласка, виберіть спочатку вищезазначені опції.',
        'less-quantity' => 'Кількість не може бути менше одиниці.',
        'sample' => 'Зразки',
        'links' => 'Посилання',
        'name' => "Ім\'я",
        'qty' => 'Кількість',
        'Start-at' => 'Починаючи з',
        'customize-options' => 'Налаштувати параметри',
        'choose-selection' => 'Вибрати виділення',
        'your-customization' => 'Ваше налаштування',
        'total-amount' => 'Загальна сума',
        'none' => 'None',
        'available-for-order' => 'Доступно для замовлення',
        'settings' => 'Налаштування',
        'compare_options' => 'Порівняти параметри',
        'wishlist-options' => 'Параметри списку бажань',
        'offers' => 'Придбати :qty шт. за :price за кожну і зберегти знижку :discount%',
    ],

    // 'reviews' => [
    // 'empty' => 'Ви ще нічого не переглянули'
    //]

    'buynow' => [
        'no-options' => 'Будь ласка, оберіть варіанти, перш ніж купувати цей продукт.'
    ],

    'checkout' => [
        'cart' => [
            'integrity' => [
                'missing_fields' => "Деякі обов'язкові поля відсутні для цього продукту.",
                'missing_options' => 'Для цього продукту відсутні параметри.',
                'missing_links' => 'Для цього продукту відсутні посилання для завантаження.',
                'qty_missing' => 'Принаймні в одному продукті повинно бути більше 1 кількості.',
                'qty_impossible' => 'Не можна додати більше одного з цих продуктів у кошик.'
            ],
            'create-error' => 'Виникла проблема під час створення екземпляра кошика.',
            'title' => 'Кошик',
            'empty' => 'Кошик порожній',
            'update-cart' => 'Оновити кошик',
            'continue-shopping' => 'Продовжити покупки',
            'continue-to-checkout' => 'Перейти до оплати',
            'remove' => 'Видалити',
            'remove-link' => 'Видалити',
            'move-to-wishlist' => 'Перейти до списку бажань',
            'move-to-wishlist-success' => 'Елемент успішно переміщено до списку бажань.',
            'move-to-wishlist-error' => 'Не вдається перемістити елемент до списку бажань, спробуйте пізніше.',
            'add-config-warning' => 'Будь ласка, виберіть опцію перед додаванням у кошик.',
            'quantity' => [
                'quantity' => 'Кількість',
                'success' => 'Елементи кошика успішно оновлені.',
                'незаконний' => 'Кількість не може бути меншою за одиницю.',
                'inventory_warning' => 'Запитана кількість недоступна, повторіть спробу пізніше.',
                'error' => 'Наразі не вдається оновити елементи, спробуйте пізніше.'
            ],

            'item' => [
                'error_remove' => 'Немає елементів для видалення з кошика.',
                'success' => 'Товар успішно додано до кошика.',
                'success-remove' => 'Елемент успішно видалено з кошика.',
                'error-add' => 'Не вдається додати товар у кошик, спробуйте пізніше.',
                'inactive' => 'Елемент неактивний і був вилучений з кошика.',
                'inactive-add' => 'Неактивний товар не можна додати в кошик.',
            ],
            'quantity-error' => 'Запитана кількість недоступна.',
            'cart-subtotal' => 'Підсумок кошика',
            'cart-remove-action' => 'Ви дійсно хочете це зробити?',
            'del-cart-update' => 'Оновлено лише деякі продукти',
            'link-missing' => '',
            'event' => [
                'expired' => 'Ця подія минула.'
            ],
            'minimum-order-message' => 'Мінімальна сума замовлення :amount'
        ],

        'onepage' => [
            'title' => 'Оформити замовлення',
            'information' => 'Інформація',
            'shipping' => "Доставка",
            'Payment' => "Оплата",
            'complete' => 'Повне',
            'review' => 'Огляд',
            'billing-address' => 'Адреса виставлення рахунку',
            'sign-in' => 'Увійти',
            'company-name' => 'Назва компанії',
            'first-name' => "Ім'я",
            'last-name' => 'Прізвище',
            'email' => 'Електронна пошта',
            'address1' => 'Вулиця',
            'city' => 'Місто',
            'state' => 'Штат',
            'select-state' => 'Вибрати регіон, штат або провінцію',
            'postcode' => 'Поштовий індекс',
            'phone' => 'Телефон',
            'country' => 'Країна',
            'order-summary' => 'Підсумок замовлення',
            'shipping-address' => 'Адреса доставки',
            'use_for_shipping' => 'Надіслати на цю адресу',
            'continue' => 'Продовжити',
            'shipping-method' => 'Вибрати спосіб доставки',
            'Payment-methods' => 'Вибрати спосіб оплати',
            'Payment-method' => 'Спосіб оплати',
            'summary' => 'Підсумок замовлення',
            'price' => "Ціна",
            'quantity' => 'Кількість',
            'contact' => 'Контакт',
            'place-order' => 'Розмістити замовлення',
            'new-address' => 'Додати нову адресу',
            'save_as_address' => 'Зберегти цю адресу',
            'apply-coupon' => 'Застосувати купон',
            'amt-payable' => 'Сума до сплати',
            'got' => 'Отримав',
            'free' => 'Безкоштовно',
            'coupon-used' => 'Купон використаний',
            'apply' => 'Застосовано',
            'back' => 'Назад',
            'cash-desc' => 'Наложенним платежем',
            'money-desc' => 'Грошовий переказ',
            'paypal-desc' => 'Стандарт Paypal',
            'free-desc' => 'Це безкоштовна доставка',
            'flat-desc' => 'Це фіксована ставка',
            'password' => 'Пароль',
            'login-exist-message' => 'У вас вже є обліковий запис у нас, увійдіть або продовжуйте як гість.',
            'enter-coupon-code' => 'Ввести код купона'
        ],

        'total' => [
            'order-summary' => 'Підсумок замовлення',
            'sub-total' => 'Елементи',
            'grand-total' => 'Загальний підсумок',
            'delivery-charges' => "Вартість доставки",
            'tax' => 'податок',
            'discount' => 'Знижка',
            'price' => "price",
            'disc-amount' => 'Сума зі знижкою',
            'new-grand-total' => 'Новий загальний підсумок',
            'coupon' => 'Купон',
            'coupon-apply' => 'Застосований купон',
            'remove-coupon' => 'Видалити купон',
            'cannot-apply-coupon' => 'Не вдається застосувати купон',
            'invalid-coupon' => 'Недійсний код купона.',
            'success-coupon' => 'Код купона успішно застосовано.',
            'coupon-apply-issue' => 'Код купона не можна застосувати.'
        ],

        'success' => [
            'title' => 'Замовлення успішно розміщено',
            'thanks' => 'Дякуємо за замовлення!',
            'order-id-info' => 'Ваш ідентифікатор замовлення #:order_id',
            'info' => 'Ми надішлемо вам електронного листа, деталі замовлення та інформацію про відстеження'
        ]
    ],

    'mail' => [
        'order' => [
            'subject' => 'Підтвердження нового замовлення',
            'heading' => 'Підтвердження замовлення!',
            'dear' => "Шановний :customer_name",
            'dear-admin' => 'Шановний :admin_name',
            'pozdrav' => 'Дякую за ваше замовлення :order_id розміщений на :created_at',
            'pozdrav-admin' => 'Ідентифікатор замовлення :order_id розміщений на :created_at',
            'summary' => 'Підсумок замовлення',
            'shipping-address' => 'Адреса доставки',
            'billing-address' => 'Адреса виставлення рахунку',
            'contact' => 'Контакт',
            'shipping' => 'Спосіб доставки',
            'Payment' => "Спосіб оплати",
            'price' => "Ціна",
            'quantity' => 'Кількість',
            'subtotal' => 'Проміжний підсумок',
            'shipping-handling' => 'Доставка та обробка',
            'tax' => 'податок',
            'discount' => 'Знижка',
            'grand-total' => 'Загальний підсумок',
            'final-summary' => 'Дякуємо, що виявили інтерес до нашого магазину, ми надішлемо вам номер відстеження, як тільки він буде надісланий',
            'help' => "Якщо вам потрібна будь-яка допомога, зв\'яжіться з нами за адресою :support_email",
            'thanks' => 'Дякую!',

            'comment' => [
                'subject' => 'До вашого замовлення додано новий коментар #:order_id',
                'dear' => "Шановний :customer_name",
                'final-summary' => 'Дякуємо за прояв інтересу до нашого магазину',
                'help' => "Якщо вам потрібна будь-яка допомога, зв\'яжіться з нами за адресою :support_email",
                'thanks' => 'Дякую!',
            ],

            'cancel' => [
                'subject' => 'Підтвердження скасування замовлення',
                'heading' => 'Замовлення скасовано',
                'dear' => "Шановний :customer_name",
                'pozdrav' => 'Ваше замовлення з ідентифікатором замовлення :order_id розміщено на :created_at скасовано',
                'summary' => 'Підсумок замовлення',
                'shipping-address' => 'Адреса доставки',
                'billing-address' => 'Адреса виставлення рахунку',
                'contact' => 'Контакт',
                'shipping' => 'Спосіб доставки',
                'Payment' => "Спосіб оплати",
                'subtotal' => 'Проміжний підсумок',
                'shipping-handling' => 'Доставка та обробка',
                'tax' => 'податок',
                'discount' => 'Знижка',
                'grand-total' => 'Загальний підсумок',
                'final-summary' => 'Дякуємо за прояв інтересу до нашого магазину',
                'help' => "Якщо вам потрібна будь-яка допомога, зв\'яжіться з нами за адресою :support_email",
                'thanks' => 'Дякую!',
            ]
        ],

        'invoice' => [
            'heading' => 'Ваш рахунок-фактура #:invoice_id для замовлення #:order_id',
            'subject' => 'Рахунок для вашого замовлення #:order_id',
            'summary' => 'Підсумок рахунку-фактури',
        ],

        'shipment' => [
            'heading' => 'Номер відвантаження #:shipment_id створено для замовлення #:order_id',
            'inventory-heading' => 'Нове відправлення #:shipment_id створено для замовлення #:order_id',
            'subject' => 'Відправка для вашого замовлення #:order_id',
            'inventory-subject' => 'Створено нову партію замовлення #:order_id',
            'summary' => 'Підсумок відвантаження',
            'carrier' => 'Перевізник',
            'tracking-number' => 'Номер відстеження',
            'pozdrav' => 'Замовлення :order_id розміщено на :created_at',
        ],

        'refund' => [
            'heading' => 'Ваш номер відшкодування #:refund_id для замовлення #:order_id',
            'subject' => 'Відшкодування за замовлення #:order_id',
            'summary' => 'Підсумок відшкодування',
            'adjustment-refund' => 'Відшкодування коригування',
            'correction-fee' => 'Збір за коригування'
        ],

        'forget-password' => [
            'subject' => 'Скидання пароля клієнта',
            'dear' => "Шановний :name",
            'info' => 'Ви отримали цей електронний лист, оскільки ми отримали запит на скидання пароля для вашого облікового запису',
            'reset-password' => 'Скинути пароль',
            'final-summary' => 'Якщо ви не просили скидання пароля, подальших дій не потрібно',
            'thanks' => 'Дякую!'
        ],

        'update-password' => [
            'subject' => 'Пароль оновлений',
            'dear' => "Шановний :name",
            'info' => 'Ви отримали цей електронний лист, оскільки оновили свій пароль.',
            'thanks' => 'Дякую!'
        ],

        'customer' => [
            'new' => [
                'dear' => "Шановний :customer_name",
                'username-email' => "Ім\'я користувача / Електронна адреса",
                'subject' => 'Реєстрація нового клієнта',
                'password' => 'Пароль',
                'summary' => 'Ваш обліковий запис створено. Інформація про ваш рахунок наведена нижче: ',
                'thanks' => 'Дякую!',
            ],

            'registration' => [
                'subject' => 'Реєстрація нового клієнта',
                'customer-registration' => 'Клієнт зареєстрований успішно',
                'dear' => "Шановний :customer_name",
                'pozdrav' => 'Ласкаво просимо і дякуємо, що зареєструвались у нас!',
                'summary' => 'Ваш обліковий запис успішно створено, і ви можете ввійти, використовуючи свою адресу електронної пошти та облікові дані пароля. Увійшовши, ви зможете отримати доступ до інших служб, включаючи перегляд минулих замовлень, списків бажань та редагування інформації про ваш рахунок. ',
                'thanks' => 'Дякую!',
            ],

            'verification' => [
                'heading' => config('app.name') . '- Підтвердження електронної пошти',
                'subject' => 'Пошта для підтвердження',
                'verify' => 'Підтвердити свій рахунок',
                'summary' => 'Це пошта для підтвердження того, що введена вами електронна адреса є вашою.
Будь ласка, натисніть кнопку Перевірити свій рахунок нижче, щоб підтвердити свій рахунок. '
            ],

            'subscription' => [
                'subject' => 'Електронна адреса передплати',
                'pozdrav' => 'Ласкаво просимо до' . config('app.name') . '- Підписка на електронну пошту',
                'unsubscribe' => 'Скасувати підписку',
                'summary' => 'Дякую, що помістили мене у свою поштову скриньку. Минув якийсь час з того часу, як ви прочитали ' . config('app.name') . 'електронною поштою, і ми не хочемо перевантажувати вашу поштову скриньку. Якщо ви все одно не хочете отримувати
                останні новини з маркетингу електронною поштою, тоді натисніть кнопку нижче. '
            ]
        ]
    ],

    'webkul' => [
        'copy-right' => '© Авторське право :year Red, Усі права захищені',
    ],

    'response' => [
        'create-success' => ":name створено успішно.",
        'update-success' => ':name оновлено успішно.',
        'delete-success' => ':name успішно видалено.',
        'submit-success' => ':name підтверджено успішно.'
    ],
];