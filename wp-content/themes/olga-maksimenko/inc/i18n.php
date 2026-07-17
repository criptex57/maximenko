<?php
/** Lightweight frontend localization for Russian, Ukrainian and English. */

defined( 'ABSPATH' ) || exit;

function olga_languages() {
	return array(
		'ru' => array( 'label' => 'RU', 'locale' => 'ru_RU', 'html' => 'ru' ),
		'uk' => array( 'label' => 'UA', 'locale' => 'uk', 'html' => 'uk' ),
		'en' => array( 'label' => 'EN', 'locale' => 'en_US', 'html' => 'en' ),
	);
}

function olga_current_language() {
	static $language = null;
	if ( null !== $language ) {
		return $language;
	}
	$rewrite_language = get_query_var( 'olga_lang' );
	$value    = $rewrite_language ? sanitize_key( $rewrite_language ) : ( isset( $_GET['lang'] ) ? sanitize_key( wp_unslash( $_GET['lang'] ) ) : 'ru' );
	$language = array_key_exists( $value, olga_languages() ) ? $value : 'ru';
	return $language;
}

function olga_register_language_routes() {
	add_rewrite_tag( '%olga_lang%', '(uk|en)' );
	add_rewrite_tag( '%olga_section%', '(about|projects|services|process|contacts)' );
	add_rewrite_rule( '^(uk|en)/(about|projects|services|process|contacts)/?$', 'index.php?olga_lang=$matches[1]&olga_section=$matches[2]', 'top' );
	add_rewrite_rule( '^(uk|en)/portfolio/?$', 'index.php?post_type=project&olga_lang=$matches[1]', 'top' );
	add_rewrite_rule( '^(uk|en)/projects/([^/]+)/?$', 'index.php?project=$matches[2]&olga_lang=$matches[1]', 'top' );
	add_rewrite_rule( '^(uk|en)/services/([^/]+)/?$', 'index.php?service=$matches[2]&olga_lang=$matches[1]', 'top' );
	add_rewrite_rule( '^(uk|en)/pages/([^/]+)/?$', 'index.php?pagename=$matches[2]&olga_lang=$matches[1]', 'top' );
	add_rewrite_rule( '^(uk|en)/?$', 'index.php?olga_lang=$matches[1]', 'top' );

	if ( '3' !== get_option( 'olga_language_routes_version' ) ) {
		flush_rewrite_rules( false );
		update_option( 'olga_language_routes_version', '3' );
	}
}
add_action( 'init', 'olga_register_language_routes', 20 );

function olga_current_section() {
	$section = sanitize_key( get_query_var( 'olga_section' ) );
	return in_array( $section, array( 'about', 'projects', 'services', 'process', 'contacts' ), true ) ? $section : '';
}

function olga_translations() {
	return array(
		'skip_content' => array( 'ru' => 'Перейти к содержимому', 'uk' => 'Перейти до вмісту', 'en' => 'Skip to content' ),
		'home' => array( 'ru' => 'Главная', 'uk' => 'Головна', 'en' => 'Home' ),
		'home_aria' => array( 'ru' => 'На главную', 'uk' => 'На головну', 'en' => 'Go to homepage' ),
		'primary_nav' => array( 'ru' => 'Основное меню', 'uk' => 'Головне меню', 'en' => 'Primary navigation' ),
		'mobile_nav' => array( 'ru' => 'Мобильное меню', 'uk' => 'Мобільне меню', 'en' => 'Mobile navigation' ),
		'language_nav' => array( 'ru' => 'Выбор языка', 'uk' => 'Вибір мови', 'en' => 'Language selector' ),
		'open_menu' => array( 'ru' => 'Открыть меню', 'uk' => 'Відкрити меню', 'en' => 'Open menu' ),
		'discuss_project' => array( 'ru' => 'Обсудить проект', 'uk' => 'Обговорити проєкт', 'en' => 'Discuss a project' ),
		'discuss_task' => array( 'ru' => 'Обсудить задачу', 'uk' => 'Обговорити завдання', 'en' => 'Discuss the brief' ),
		'role_default' => array( 'ru' => 'Дизайнер интерьеров', 'uk' => 'Дизайнерка інтер’єрів', 'en' => 'Interior designer' ),
		'name_default' => array( 'ru' => 'Ольга Максименко', 'uk' => 'Ольга Максименко', 'en' => 'Olga Maksimenko' ),
		'site_title' => array( 'ru' => 'Ольга Максименко — дизайнер интерьеров', 'uk' => 'Ольга Максименко — дизайнерка інтер’єрів', 'en' => 'Olga Maksimenko — interior designer' ),
		'project_image_alt' => array( 'ru' => 'Современный интерьер — проект %s', 'uk' => 'Сучасний інтер’єр — проєкт %s', 'en' => 'Contemporary interior — %s project' ),
		'hero_alt' => array( 'ru' => 'Ольга Максименко работает над планировкой интерьера', 'uk' => 'Ольга Максименко працює над плануванням інтер’єру', 'en' => 'Olga Maksimenko working on an interior layout' ),
		'hero_title_1' => array( 'ru' => 'Интерьеры', 'uk' => 'Інтер’єри', 'en' => 'Interiors' ),
		'hero_title_2' => array( 'ru' => 'в которых хочется жить', 'uk' => 'у яких хочеться жити', 'en' => 'made to live in' ),
		'hero_text' => array( 'ru' => 'Создаю пространства, где эстетика становится частью повседневной жизни.', 'uk' => 'Створюю простори, де естетика стає частиною повсякденного життя.', 'en' => 'I create spaces where aesthetics become part of everyday life.' ),
		'view_projects' => array( 'ru' => 'Смотреть проекты', 'uk' => 'Дивитися проєкти', 'en' => 'View projects' ),
		'manifesto' => array( 'ru' => 'Манифест', 'uk' => 'Маніфест', 'en' => 'Manifesto' ),
		'manifesto_title' => array( 'ru' => 'Тишина.<br><em>Свет.</em><br>Характер.', 'uk' => 'Тиша.<br><em>Світло.</em><br>Характер.', 'en' => 'Silence.<br><em>Light.</em><br>Character.' ),
		'about_text_default' => array( 'ru' => 'Я создаю интерьеры, в которых эстетика работает вместе с функциональностью. Для меня дизайн — это создание целостного пространства, соответствующего образу жизни человека.', 'uk' => 'Я створюю інтер’єри, у яких естетика працює разом із функціональністю. Для мене дизайн — це цілісний простір, що відповідає способу життя людини.', 'en' => 'I create interiors where aesthetics and function work together. For me, design means shaping a complete space around the way a person lives.' ),
		'about_approach' => array( 'ru' => 'О подходе', 'uk' => 'Про підхід', 'en' => 'My approach' ),
		'interior_detail_alt' => array( 'ru' => 'Деталь авторского интерьера', 'uk' => 'Деталь авторського інтер’єру', 'en' => 'Bespoke interior detail' ),
		'interior_visual_alt' => array( 'ru' => 'Авторская визуализация интерьера', 'uk' => 'Авторська візуалізація інтер’єру', 'en' => 'Bespoke interior visualization' ),
		'interior_modern_alt' => array( 'ru' => 'Современный интерьер', 'uk' => 'Сучасний інтер’єр', 'en' => 'Contemporary interior' ),
		'stat_experience' => array( 'ru' => 'лет создаю пространства', 'uk' => 'років створюю простори', 'en' => 'years creating spaces' ),
		'stat_projects' => array( 'ru' => 'реализованных историй', 'uk' => 'реалізованих історій', 'en' => 'completed stories' ),
		'stat_full_cycle' => array( 'ru' => 'от первой линии до жизни', 'uk' => 'від першої лінії до життя', 'en' => 'from first line to real life' ),
		'selected_works' => array( 'ru' => 'Избранные проекты', 'uk' => 'Обрані проєкти', 'en' => 'Selected works' ),
		'projects_title' => array( 'ru' => 'Пространства<br>как <em>искусство.</em>', 'uk' => 'Простори<br>як <em>мистецтво.</em>', 'en' => 'Spaces<br>as <em>art.</em>' ),
		'projects_intro' => array( 'ru' => 'Шесть историй, шесть характеров. Листайте медленно.', 'uk' => 'Шість історій, шість характерів. Гортайте повільно.', 'en' => 'Six stories, six characters. Take your time.' ),
		'interior' => array( 'ru' => 'Интерьер', 'uk' => 'Інтер’єр', 'en' => 'Interior' ),
		'explore' => array( 'ru' => 'Смотреть', 'uk' => 'Дивитися', 'en' => 'Explore' ),
		'all_projects' => array( 'ru' => 'Все проекты', 'uk' => 'Усі проєкти', 'en' => 'All projects' ),
		'expertise' => array( 'ru' => 'Экспертиза', 'uk' => 'Експертиза', 'en' => 'Expertise' ),
		'services_title' => array( 'ru' => 'От идеи<br>до <em>касания.</em>', 'uk' => 'Від ідеї<br>до <em>дотику.</em>', 'en' => 'From idea<br>to <em>touch.</em>' ),
		'services_intro' => array( 'ru' => 'Выберите уровень погружения в проект.', 'uk' => 'Оберіть рівень занурення в проєкт.', 'en' => 'Choose the level of involvement your project needs.' ),
		'more_about' => array( 'ru' => 'Подробнее: %s', 'uk' => 'Докладніше: %s', 'en' => 'Learn more: %s' ),
		'portal_alt' => array( 'ru' => 'Атмосфера авторского интерьера', 'uk' => 'Атмосфера авторського інтер’єру', 'en' => 'Atmosphere of a bespoke interior' ),
		'process' => array( 'ru' => 'Процесс', 'uk' => 'Процес', 'en' => 'Process' ),
		'process_title' => array( 'ru' => 'Восемь глав.<br>Одна цель.', 'uk' => 'Вісім розділів.<br>Одна мета.', 'en' => 'Eight chapters.<br>One goal.' ),
		'process_intro' => array( 'ru' => 'Предсказуемый путь к пространству, которое ощущается естественно.', 'uk' => 'Передбачуваний шлях до простору, що відчувається природно.', 'en' => 'A clear path to a space that feels completely natural.' ),
		'process_steps_default' => array( 'ru' => "Знакомство и бриф\nОбмеры и анализ пространства\nПланировочное решение\nРазработка концепции\n3D-визуализация\nРабочая документация\nКомплектация\nАвторский надзор", 'uk' => "Знайомство та бриф\nОбміри й аналіз простору\nПланувальне рішення\nРозробка концепції\n3D-візуалізація\nРобоча документація\nКомплектація\nАвторський нагляд", 'en' => "Introduction and brief\nMeasurements and spatial analysis\nLayout solution\nConcept development\n3D visualization\nWorking documentation\nProcurement\nDesign supervision" ),
		'transformation' => array( 'ru' => 'Трансформация', 'uk' => 'Трансформація', 'en' => 'Transformation' ),
		'before_after' => array( 'ru' => 'До / после', 'uk' => 'До / після', 'en' => 'Before / after' ),
		'compare_instruction' => array( 'ru' => 'Перемещайте линию, чтобы увидеть решение.', 'uk' => 'Переміщуйте лінію, щоб побачити рішення.', 'en' => 'Move the line to reveal the transformation.' ),
		'after_alt' => array( 'ru' => 'Интерьер после работы', 'uk' => 'Інтер’єр після роботи', 'en' => 'Interior after the transformation' ),
		'before_alt' => array( 'ru' => 'Исходная концепция интерьера', 'uk' => 'Початкова концепція інтер’єру', 'en' => 'Initial interior concept' ),
		'compare_aria' => array( 'ru' => 'Сравнить до и после', 'uk' => 'Порівняти до та після', 'en' => 'Compare before and after' ),
		'before' => array( 'ru' => 'До', 'uk' => 'До', 'en' => 'Before' ),
		'after' => array( 'ru' => 'После', 'uk' => 'Після', 'en' => 'After' ),
		'portfolio' => array( 'ru' => 'Портфолио', 'uk' => 'Портфоліо', 'en' => 'Portfolio' ),
		'archive_title' => array( 'ru' => 'Проекты, созданные вокруг жизни.', 'uk' => 'Проєкти, створені навколо життя.', 'en' => 'Projects shaped around life.' ),
		'archive_intro' => array( 'ru' => 'Жилые и коммерческие пространства — от первой линии до реализации.', 'uk' => 'Житлові та комерційні простори — від першої лінії до реалізації.', 'en' => 'Residential and commercial spaces — from the first line to completion.' ),
		'open' => array( 'ru' => 'Открыть', 'uk' => 'Відкрити', 'en' => 'Open' ),
		'projects_soon' => array( 'ru' => 'Проекты скоро появятся.', 'uk' => 'Проєкти незабаром з’являться.', 'en' => 'Projects are coming soon.' ),
		'interior_project' => array( 'ru' => 'Проект интерьера', 'uk' => 'Проєкт інтер’єру', 'en' => 'Interior project' ),
		'brief' => array( 'ru' => 'Задача', 'uk' => 'Завдання', 'en' => 'The brief' ),
		'brief_default' => array( 'ru' => 'Создать цельное пространство для комфортной современной жизни.', 'uk' => 'Створити цілісний простір для комфортного сучасного життя.', 'en' => 'Create a coherent space for comfortable contemporary living.' ),
		'key_solutions' => array( 'ru' => 'Ключевые решения', 'uk' => 'Ключові рішення', 'en' => 'Key solutions' ),
		'next_project' => array( 'ru' => 'Следующий проект', 'uk' => 'Наступний проєкт', 'en' => 'Next project' ),
		'service' => array( 'ru' => 'Услуга', 'uk' => 'Послуга', 'en' => 'Service' ),
		'duration' => array( 'ru' => 'Срок', 'uk' => 'Термін', 'en' => 'Timeline' ),
		'included' => array( 'ru' => 'Что входит', 'uk' => 'Що входить', 'en' => 'What is included' ),
		'city_label' => array( 'ru' => 'Город', 'uk' => 'Місто', 'en' => 'City' ),
		'area_label' => array( 'ru' => 'Площадь', 'uk' => 'Площа', 'en' => 'Area' ),
		'year_label' => array( 'ru' => 'Год', 'uk' => 'Рік', 'en' => 'Year' ),
		'start_dialogue' => array( 'ru' => 'Начнём диалог', 'uk' => 'Почнімо діалог', 'en' => 'Start a conversation' ),
		'footer_heading' => array( 'ru' => 'Давайте создадим интерьер, который будет отражать именно вас.', 'uk' => 'Створімо інтер’єр, який відображатиме саме вас.', 'en' => 'Let’s create an interior that feels unmistakably yours.' ),
		'footer_note_default' => array( 'ru' => 'Создаю пространства с характером — от идеи до реализации.', 'uk' => 'Створюю простори з характером — від ідеї до реалізації.', 'en' => 'I create spaces with character — from concept to completion.' ),
		'contact' => array( 'ru' => 'Связаться', 'uk' => 'Зв’язатися', 'en' => 'Contact' ),
		'social' => array( 'ru' => 'Социальные сети', 'uk' => 'Соціальні мережі', 'en' => 'Social' ),
		'privacy' => array( 'ru' => 'Политика конфиденциальности', 'uk' => 'Політика конфіденційності', 'en' => 'Privacy policy' ),
		'top' => array( 'ru' => 'Наверх', 'uk' => 'Нагору', 'en' => 'Back to top' ),
		'close' => array( 'ru' => 'Закрыть', 'uk' => 'Закрити', 'en' => 'Close' ),
		'tell_task' => array( 'ru' => 'Расскажите о задаче', 'uk' => 'Розкажіть про завдання', 'en' => 'Tell me about your project' ),
		'your_name' => array( 'ru' => 'Ваше имя', 'uk' => 'Ваше ім’я', 'en' => 'Your name' ),
		'contact_method' => array( 'ru' => 'Телефон или мессенджер', 'uk' => 'Телефон або месенджер', 'en' => 'Phone or messenger' ),
		'object_type' => array( 'ru' => 'Тип объекта', 'uk' => 'Тип об’єкта', 'en' => 'Property type' ),
		'apartment' => array( 'ru' => 'Квартира', 'uk' => 'Квартира', 'en' => 'Apartment' ),
		'private_house' => array( 'ru' => 'Частный дом', 'uk' => 'Приватний будинок', 'en' => 'Private house' ),
		'commercial_space' => array( 'ru' => 'Коммерческое помещение', 'uk' => 'Комерційне приміщення', 'en' => 'Commercial space' ),
		'other' => array( 'ru' => 'Другое', 'uk' => 'Інше', 'en' => 'Other' ),
		'area' => array( 'ru' => 'Площадь', 'uk' => 'Площа', 'en' => 'Area' ),
		'area_placeholder' => array( 'ru' => 'например, 85 м²', 'uk' => 'наприклад, 85 м²', 'en' => 'for example, 85 m²' ),
		'city' => array( 'ru' => 'Город', 'uk' => 'Місто', 'en' => 'City' ),
		'budget' => array( 'ru' => 'Ориентировочный бюджет', 'uk' => 'Орієнтовний бюджет', 'en' => 'Estimated budget' ),
		'message' => array( 'ru' => 'Сообщение', 'uk' => 'Повідомлення', 'en' => 'Message' ),
		'website' => array( 'ru' => 'Сайт', 'uk' => 'Сайт', 'en' => 'Website' ),
		'consent' => array( 'ru' => 'Я согласен(на) с %s.', 'uk' => 'Я погоджуюся з %s.', 'en' => 'I agree to the %s.' ),
		'submit' => array( 'ru' => 'Отправить заявку', 'uk' => 'Надіслати заявку', 'en' => 'Send enquiry' ),
		'form_check' => array( 'ru' => 'Проверьте обязательные поля.', 'uk' => 'Перевірте обов’язкові поля.', 'en' => 'Please check the required fields.' ),
		'form_sending' => array( 'ru' => 'Отправляем…', 'uk' => 'Надсилаємо…', 'en' => 'Sending…' ),
		'form_rejected' => array( 'ru' => 'Заявка отклонена.', 'uk' => 'Заявку відхилено.', 'en' => 'The enquiry was rejected.' ),
		'form_validation' => array( 'ru' => 'Заполните имя, контакт и подтвердите согласие.', 'uk' => 'Заповніть ім’я, контакт і підтвердьте згоду.', 'en' => 'Enter your name and contact details, then confirm your consent.' ),
		'form_rate' => array( 'ru' => 'Пожалуйста, подождите минуту перед повторной отправкой.', 'uk' => 'Будь ласка, зачекайте хвилину перед повторним надсиланням.', 'en' => 'Please wait a minute before sending again.' ),
		'form_mail_error' => array( 'ru' => 'Не удалось отправить сообщение. Свяжитесь с нами по телефону.', 'uk' => 'Не вдалося надіслати повідомлення. Зв’яжіться з нами телефоном.', 'en' => 'The message could not be sent. Please contact us by phone.' ),
		'form_success' => array( 'ru' => 'Спасибо! Я свяжусь с вами в ближайшее время.', 'uk' => 'Дякую! Я зв’яжуся з вами найближчим часом.', 'en' => 'Thank you! I’ll be in touch shortly.' ),
		'not_found' => array( 'ru' => 'Страница не найдена', 'uk' => 'Сторінку не знайдено', 'en' => 'Page not found' ),
		'not_found_title' => array( 'ru' => 'Кажется, это пространство ещё не спроектировано.', 'uk' => 'Здається, цей простір іще не спроєктовано.', 'en' => 'It seems this space has not been designed yet.' ),
		'back_home' => array( 'ru' => 'Вернуться на главную', 'uk' => 'Повернутися на головну', 'en' => 'Return home' ),
		'search' => array( 'ru' => 'Поиск', 'uk' => 'Пошук', 'en' => 'Search' ),
		'search_results' => array( 'ru' => 'Результаты для: %s', 'uk' => 'Результати для: %s', 'en' => 'Results for: %s' ),
		'nothing_found' => array( 'ru' => 'Ничего не найдено.', 'uk' => 'Нічого не знайдено.', 'en' => 'Nothing found.' ),
	);
}

function olga_t( $key, ...$args ) {
	return olga_t_for( $key, olga_current_language(), ...$args );
}

function olga_t_for( $key, $language, ...$args ) {
	$dictionary = olga_translations();
	$language   = array_key_exists( $language, olga_languages() ) ? $language : 'ru';
	$value      = $dictionary[ $key ][ $language ] ?? $dictionary[ $key ]['ru'] ?? $key;
	return $args ? vsprintf( $value, $args ) : $value;
}

function olga_known_content_translations() {
	return array(
		'Квартиры' => array( 'uk' => 'Квартири', 'en' => 'Apartments' ),
		'Частные дома' => array( 'uk' => 'Приватні будинки', 'en' => 'Private houses' ),
		'Коммерческие пространства' => array( 'uk' => 'Комерційні простори', 'en' => 'Commercial spaces' ),
		'Тихая гавань' => array( 'uk' => 'Тиха гавань', 'en' => 'Quiet Haven' ),
		'Дом у сосен' => array( 'uk' => 'Будинок біля сосен', 'en' => 'House Among the Pines' ),
		'Киев' => array( 'uk' => 'Київ', 'en' => 'Kyiv' ),
		'Варшава' => array( 'uk' => 'Варшава', 'en' => 'Warsaw' ),
		'Буча' => array( 'uk' => 'Буча', 'en' => 'Bucha' ),
		'Львов' => array( 'uk' => 'Львів', 'en' => 'Lviv' ),
		'Прага' => array( 'uk' => 'Прага', 'en' => 'Prague' ),
		'Дизайн-проект квартиры' => array( 'uk' => 'Дизайн-проєкт квартири', 'en' => 'Apartment design project' ),
		'Дизайн частного дома' => array( 'uk' => 'Дизайн приватного будинку', 'en' => 'Private house design' ),
		'Коммерческий интерьер' => array( 'uk' => 'Комерційний інтер’єр', 'en' => 'Commercial interior' ),
		'Планировочное решение' => array( 'uk' => 'Планувальне рішення', 'en' => 'Layout solution' ),
		'Авторский надзор' => array( 'uk' => 'Авторський нагляд', 'en' => 'Design supervision' ),
		'Комплектация интерьера' => array( 'uk' => 'Комплектація інтер’єру', 'en' => 'Interior procurement' ),
		'Консультация дизайнера' => array( 'uk' => 'Консультація дизайнера', 'en' => 'Designer consultation' ),
		'Полный проект жилого интерьера: от планировки до рабочей документации.' => array( 'uk' => 'Повний проєкт житлового інтер’єру: від планування до робочої документації.', 'en' => 'A complete residential interior project, from layout to working documentation.' ),
		'Целостная концепция дома с учётом архитектуры, инженерии и образа жизни семьи.' => array( 'uk' => 'Цілісна концепція будинку з урахуванням архітектури, інженерії та способу життя родини.', 'en' => 'A complete home concept shaped by its architecture, engineering and the family’s lifestyle.' ),
		'Пространство, которое выражает характер бренда и помогает бизнесу.' => array( 'uk' => 'Простір, що виражає характер бренду та допомагає бізнесу.', 'en' => 'A space that expresses the brand and supports the business.' ),
		'Несколько точных вариантов организации пространства с расстановкой мебели.' => array( 'uk' => 'Кілька точних варіантів організації простору з розстановкою меблів.', 'en' => 'Several precise space-planning options with furniture layouts.' ),
		'Контроль соответствия реализации проекту и оперативное решение вопросов стройки.' => array( 'uk' => 'Контроль відповідності реалізації проєкту та оперативне вирішення питань будівництва.', 'en' => 'On-site quality control and prompt resolution of construction questions.' ),
		'Подбор, заказ и координация поставок материалов, мебели, света и декора.' => array( 'uk' => 'Підбір, замовлення та координація постачання матеріалів, меблів, освітлення й декору.', 'en' => 'Selection, ordering and delivery coordination for materials, furniture, lighting and décor.' ),
		'Сфокусированная встреча для решения конкретных вопросов по интерьеру.' => array( 'uk' => 'Сфокусована зустріч для вирішення конкретних питань щодо інтер’єру.', 'en' => 'A focused meeting to resolve specific interior design questions.' ),
		'8–14 недель' => array( 'uk' => '8–14 тижнів', 'en' => '8–14 weeks' ),
		'12–20 недель' => array( 'uk' => '12–20 тижнів', 'en' => '12–20 weeks' ),
		'10–18 недель' => array( 'uk' => '10–18 тижнів', 'en' => '10–18 weeks' ),
		'2–3 недели' => array( 'uk' => '2–3 тижні', 'en' => '2–3 weeks' ),
		'На период реализации' => array( 'uk' => 'На період реалізації', 'en' => 'During implementation' ),
		'По графику проекта' => array( 'uk' => 'За графіком проєкту', 'en' => 'According to the project schedule' ),
		'90 минут' => array( 'uk' => '90 хвилин', 'en' => '90 minutes' ),
		'Светлый семейный интерьер, построенный на естественных фактурах, мягком свете и точных сценариях хранения.' => array( 'uk' => 'Світлий сімейний інтер’єр, побудований на природних фактурах, м’якому світлі та точних сценаріях зберігання.', 'en' => 'A light-filled family interior shaped by natural textures, soft light and precise storage solutions.' ),
		'Выразительная квартира с глубокими оттенками, скульптурной мебелью и современной графикой.' => array( 'uk' => 'Виразна квартира з глибокими відтінками, скульптурними меблями та сучасною графікою.', 'en' => 'An expressive apartment with deep tones, sculptural furniture and contemporary art.' ),
		'Дом для неторопливой жизни, где архитектура и ландшафт продолжают друг друга.' => array( 'uk' => 'Будинок для неквапливого життя, де архітектура та ландшафт продовжують одне одного.', 'en' => 'A home for unhurried living, where architecture and landscape flow into one another.' ),
		'Мягкая геометрия, сложные нейтральные оттенки и тщательно подобранный свет.' => array( 'uk' => 'М’яка геометрія, складні нейтральні відтінки та ретельно підібране світло.', 'en' => 'Soft geometry, nuanced neutral tones and carefully composed lighting.' ),
		'Камерное пространство студии с архитектурной пластикой и выразительным светом.' => array( 'uk' => 'Камерний простір студії з архітектурною пластикою та виразним світлом.', 'en' => 'An intimate studio shaped by architectural forms and expressive light.' ),
		'Монохромный интерьер с богатством фактур и сдержанным характером.' => array( 'uk' => 'Монохромний інтер’єр із багатством фактур і стриманим характером.', 'en' => 'A monochrome interior rich in texture and restrained in character.' ),
		'Создать спокойное пространство для семьи с двумя детьми, сохранив ощущение воздуха.' => array( 'uk' => 'Створити спокійний простір для родини з двома дітьми, зберігши відчуття повітря.', 'en' => 'Create a calm home for a family with two children while preserving a sense of openness.' ),
		'Объединить представительскую эстетику и комфорт ежедневной жизни.' => array( 'uk' => 'Поєднати представницьку естетику та комфорт щоденного життя.', 'en' => 'Combine a refined, representative aesthetic with everyday comfort.' ),
		'Наполнить дом природным светом и связать общие зоны с садом.' => array( 'uk' => 'Наповнити будинок природним світлом і пов’язати спільні зони із садом.', 'en' => 'Fill the home with natural light and connect the shared spaces to the garden.' ),
		'Организовать компактную квартиру без ощущения тесноты.' => array( 'uk' => 'Організувати компактну квартиру без відчуття тісноти.', 'en' => 'Organize a compact apartment without making it feel confined.' ),
		'Создать узнаваемый интерьер, который работает на бренд.' => array( 'uk' => 'Створити впізнаваний інтер’єр, що працює на бренд.', 'en' => 'Create a distinctive interior that strengthens the brand.' ),
		'Сформировать вневременной интерьер для длительного проживания.' => array( 'uk' => 'Сформувати позачасовий інтер’єр для тривалого проживання.', 'en' => 'Shape a timeless interior designed for long-term living.' ),
		"Обмерный план\nПланировочные решения\nКонцепция и 3D-визуализации\nКомплект рабочих чертежей\nВедомость материалов" => array( 'uk' => "Обмірний план\nПланувальні рішення\nКонцепція та 3D-візуалізації\nКомплект робочих креслень\nВідомість матеріалів", 'en' => "Measured survey\nLayout solutions\nConcept and 3D visualizations\nComplete working drawings\nMaterials schedule" ),
		"Функциональное зонирование\nКонцепция всех помещений\n3D-визуализации\nРабочая документация\nСпецификации" => array( 'uk' => "Функціональне зонування\nКонцепція всіх приміщень\n3D-візуалізації\nРобоча документація\nСпецифікації", 'en' => "Functional zoning\nConcept for every room\n3D visualizations\nWorking documentation\nSpecifications" ),
		"Анализ сценариев\nПланировка\nКонцепция бренда в интерьере\nДокументация\nКомплектация" => array( 'uk' => "Аналіз сценаріїв\nПланування\nКонцепція бренду в інтер’єрі\nДокументація\nКомплектація", 'en' => "User-flow analysis\nSpace planning\nBrand concept in the interior\nDocumentation\nProcurement" ),
		"Обмеры и анализ\n2–3 варианта планировки\nФинальная схема\nЭкспликация помещений" => array( 'uk' => "Обміри та аналіз\n2–3 варіанти планування\nФінальна схема\nЕксплікація приміщень", 'en' => "Measurements and analysis\n2–3 layout options\nFinal plan\nRoom schedule" ),
		"Регулярные выезды\nПроверка соответствия проекту\nРабота с подрядчиками\nКорректировки" => array( 'uk' => "Регулярні виїзди\nПеревірка відповідності проєкту\nРобота з підрядниками\nКоригування", 'en' => "Regular site visits\nDesign compliance checks\nContractor coordination\nAdjustments" ),
		"Тендер поставщиков\nСчета и графики\nКонтроль заказов\nОрганизация доставок" => array( 'uk' => "Тендер постачальників\nРахунки та графіки\nКонтроль замовлень\nОрганізація доставок", 'en' => "Supplier tender\nInvoices and schedules\nOrder tracking\nDelivery coordination" ),
		"Предварительный бриф\nОнлайн или офлайн встреча\nПрактические рекомендации\nРезюме встречи" => array( 'uk' => "Попередній бриф\nОнлайн- або офлайн-зустріч\nПрактичні рекомендації\nПідсумок зустрічі", 'en' => "Preliminary brief\nOnline or in-person meeting\nPractical recommendations\nMeeting summary" ),
		'Каждое решение в проекте подчинено общей идее: пространство должно быть красивым, удобным и естественным для своих владельцев.' => array( 'uk' => 'Кожне рішення в проєкті підпорядковане спільній ідеї: простір має бути красивим, зручним і природним для своїх власників.', 'en' => 'Every decision follows one idea: the space should be beautiful, comfortable and natural for the people who live in it.' ),
		'Результат' => array( 'uk' => 'Результат', 'en' => 'Outcome' ),
		'Вы получаете понятный, профессионально подготовленный результат и уверенность в дальнейших решениях.' => array( 'uk' => 'Ви отримуєте зрозумілий, професійно підготовлений результат і впевненість у подальших рішеннях.', 'en' => 'You receive a clear, professionally prepared result and confidence in every next decision.' ),
		'Обо мне' => array( 'uk' => 'Про мене', 'en' => 'About' ),
		'Контакты' => array( 'uk' => 'Контакти', 'en' => 'Contact' ),
		'Политика конфиденциальности' => array( 'uk' => 'Політика конфіденційності', 'en' => 'Privacy policy' ),
		'Интерьеры как продолжение человека' => array( 'uk' => 'Інтер’єри як продовження людини', 'en' => 'Interiors as an extension of the person' ),
		'Мой подход' => array( 'uk' => 'Мій підхід', 'en' => 'My approach' ),
		'Общие положения' => array( 'uk' => 'Загальні положення', 'en' => 'General provisions' ),
		'Какие данные мы получаем' => array( 'uk' => 'Які дані ми отримуємо', 'en' => 'What data we collect' ),
		'Я верю, что хорошее пространство начинается с внимательного диалога. Изучаю привычки, ритм и ценности клиента, чтобы создать интерьер, который не устареет и будет каждый день делать жизнь комфортнее.' => array( 'uk' => 'Я вірю, що гарний простір починається з уважного діалогу. Вивчаю звички, ритм і цінності клієнта, щоб створити інтер’єр, який не застаріє й щодня робитиме життя комфортнішим.', 'en' => 'I believe a good space begins with an attentive conversation. I study each client’s habits, rhythm and values to create an interior that will endure and make everyday life more comfortable.' ),
		'Функция, эстетика и реалистичность решений всегда развиваются вместе. Я сопровождаю проект от первых эскизов до последней детали.' => array( 'uk' => 'Функція, естетика та реалістичність рішень завжди розвиваються разом. Я супроводжую проєкт від перших ескізів до останньої деталі.', 'en' => 'Function, aesthetics and practical feasibility always develop together. I guide the project from the first sketch to the final detail.' ),
		'Буду рада познакомиться' => array( 'uk' => 'Буду рада познайомитися', 'en' => 'I would love to meet you' ),
		'Опишите объект, его площадь, город и желаемые сроки. Я отвечу и предложу удобный формат первой встречи.' => array( 'uk' => 'Опишіть об’єкт, його площу, місто та бажані терміни. Я відповім і запропоную зручний формат першої зустрічі.', 'en' => 'Tell me about the property, its area, location and desired timeline. I will reply and suggest a convenient format for our first meeting.' ),
		'Данные из формы используются только для ответа на обращение и не передаются третьим лицам без законных оснований.' => array( 'uk' => 'Дані з форми використовуються лише для відповіді на звернення та не передаються третім особам без законних підстав.', 'en' => 'Data submitted through the form is used only to respond to your enquiry and is not shared with third parties without a lawful basis.' ),
		'Имя, контактные данные и сведения о проекте, которые пользователь указывает добровольно.' => array( 'uk' => 'Ім’я, контактні дані та відомості про проєкт, які користувач надає добровільно.', 'en' => 'The name, contact details and project information that the user provides voluntarily.' ),
		'Натуральный дуб и тактильный текстиль' => array( 'uk' => 'Натуральний дуб і тактильний текстиль', 'en' => 'Natural oak and tactile textiles' ),
		'Скрытые системы хранения' => array( 'uk' => 'Приховані системи зберігання', 'en' => 'Concealed storage systems' ),
		'Несколько сценариев освещения' => array( 'uk' => 'Кілька сценаріїв освітлення', 'en' => 'Multiple lighting scenarios' ),
		'Единая ось гостиной и кухни' => array( 'uk' => 'Єдина вісь вітальні та кухні', 'en' => 'A unified living and kitchen axis' ),
		'Акцентный природный камень' => array( 'uk' => 'Акцентний природний камінь', 'en' => 'Statement natural stone' ),
		'Авторская мебель' => array( 'uk' => 'Авторські меблі', 'en' => 'Custom-designed furniture' ),
		'Панорамное остекление' => array( 'uk' => 'Панорамне скління', 'en' => 'Panoramic glazing' ),
		'Тёплая нейтральная палитра' => array( 'uk' => 'Тепла нейтральна палітра', 'en' => 'Warm neutral palette' ),
		'Натуральный шпон' => array( 'uk' => 'Натуральний шпон', 'en' => 'Natural veneer' ),
		'Радиусные перегородки' => array( 'uk' => 'Радіусні перегородки', 'en' => 'Curved partitions' ),
		'Встроенная мебель' => array( 'uk' => 'Вбудовані меблі', 'en' => 'Built-in furniture' ),
		'Визуально лёгкие конструкции' => array( 'uk' => 'Візуально легкі конструкції', 'en' => 'Visually light structures' ),
		'Модульная планировка' => array( 'uk' => 'Модульне планування', 'en' => 'Modular layout' ),
		'Графичная навигация' => array( 'uk' => 'Графічна навігація', 'en' => 'Graphic wayfinding' ),
		'Световые акценты' => array( 'uk' => 'Світлові акценти', 'en' => 'Lighting accents' ),
		'Микроцемент и натуральный камень' => array( 'uk' => 'Мікроцемент і натуральний камінь', 'en' => 'Microcement and natural stone' ),
		'Минимум визуального шума' => array( 'uk' => 'Мінімум візуального шуму', 'en' => 'Minimal visual noise' ),
		'Предметное искусство' => array( 'uk' => 'Предметне мистецтво', 'en' => 'Collectible art objects' ),
	);
}

function olga_translate_known( $value, $language = null ) {
	$language = $language ?: olga_current_language();
	if ( 'ru' === $language || '' === trim( (string) $value ) ) {
		return $value;
	}
	$known = olga_known_content_translations();
	if ( isset( $known[ $value ][ $language ] ) ) {
		return $known[ $value ][ $language ];
	}
	$replace = array();
	foreach ( $known as $source => $translations ) {
		if ( isset( $translations[ $language ] ) ) {
			$replace[ $source ] = $translations[ $language ];
		}
	}
	return strtr( $value, $replace );
}

function olga_localized_option( $key, $translation_key = '', $fallback = '' ) {
	$language = olga_current_language();
	if ( 'ru' !== $language ) {
		$localized_key = $key . '_' . $language;
		$fields        = function_exists( 'olga_option_fields' ) ? olga_option_fields() : array();
		$translated    = olga_option( $localized_key, $fields[ $localized_key ][1] ?? '' );
		if ( '' !== $translated ) {
			return $translated;
		}
		if ( $translation_key ) {
			return olga_t( $translation_key );
		}
	}
	return olga_option( $key, $fallback );
}

function olga_post_value( $post_id, $field ) {
	$post_id  = $post_id ?: get_the_ID();
	$language = olga_current_language();
	if ( 'ru' !== $language ) {
		$value = get_post_meta( $post_id, '_olga_' . $field . '_' . $language, true );
		if ( '' !== trim( (string) $value ) ) {
			return $value;
		}
	}
	if ( 'title' === $field ) {
		$value = get_post_field( 'post_title', $post_id );
	} elseif ( 'excerpt' === $field ) {
		$value = get_post_field( 'post_excerpt', $post_id );
	} elseif ( 'content' === $field ) {
		$value = get_post_field( 'post_content', $post_id );
	} else {
		$value = get_post_meta( $post_id, '_olga_' . $field, true );
	}
	if ( 'area' === $field && 'ru' !== $language ) {
		$value = str_replace( array( 'м²', 'м2' ), array( 'm²', 'm2' ), $value );
	}
	return olga_translate_known( $value, $language );
}

function olga_term_name( $term ) {
	if ( ! $term instanceof WP_Term ) {
		return '';
	}
	$language = olga_current_language();
	if ( 'ru' !== $language ) {
		$value = get_term_meta( $term->term_id, '_olga_name_' . $language, true );
		if ( $value ) {
			return $value;
		}
	}
	return olga_translate_known( $term->name, $language );
}

function olga_url( $url, $language = null ) {
	$language = $language ?: olga_current_language();
	$home_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );
	$url_host  = wp_parse_url( $url, PHP_URL_HOST );
	if ( $url_host && $home_host !== $url_host ) {
		return $url;
	}
	$fragment = wp_parse_url( $url, PHP_URL_FRAGMENT );
	$query    = wp_parse_url( remove_query_arg( 'lang', $url ), PHP_URL_QUERY );
	$path     = (string) wp_parse_url( $url, PHP_URL_PATH );
	$home_path = rtrim( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
	if ( $home_path && str_starts_with( $path, $home_path ) ) {
		$path = substr( $path, strlen( $home_path ) );
	}
	$path = preg_replace( '#^/(uk|en)(?=/|$)#', '', '/' . ltrim( $path, '/' ) );
	$path = '/' . ltrim( $path, '/' );
	if ( 'ru' !== $language ) {
		$path = '/' . $language . ( '/' === $path ? '/' : $path );
	}
	$localized = home_url( $path );
	if ( $query ) {
		$localized .= '?' . $query;
	}
	if ( $fragment ) {
		$localized .= '#' . $fragment;
	}
	return $localized;
}

function olga_section_url( $section, $language = null ) {
	$language = $language ?: olga_current_language();
	if ( 'ru' === $language ) {
		return home_url( '/#' . $section );
	}
	return home_url( '/' . $language . '/' . $section . '/' );
}

function olga_archive_url( $language = null ) {
	$language = $language ?: olga_current_language();
	return 'ru' === $language ? home_url( '/projects/' ) : home_url( '/' . $language . '/portfolio/' );
}

function olga_page_url( $post_id, $language = null ) {
	$language = $language ?: olga_current_language();
	$slug     = get_post_field( 'post_name', $post_id );
	return 'ru' === $language ? home_url( '/' . $slug . '/' ) : home_url( '/' . $language . '/pages/' . $slug . '/' );
}

function olga_language_url( $language ) {
	$section = olga_current_section();
	if ( $section ) {
		return olga_section_url( $section, $language );
	}
	if ( is_front_page() ) {
		return olga_url( home_url( '/' ), $language );
	}
	if ( is_post_type_archive( 'project' ) ) {
		return olga_archive_url( $language );
	}
	if ( is_page() ) {
		return olga_page_url( get_queried_object_id(), $language );
	}
	if ( is_singular() ) {
		return olga_url( get_permalink( get_queried_object_id() ), $language );
	}
	global $wp;
	return olga_url( home_url( '/' . ltrim( $wp->request ?? '', '/' ) ), $language );
}

function olga_language_switcher() {
	$current = olga_current_language();
	echo '<nav class="language-switcher" aria-label="' . esc_attr( olga_t( 'language_nav' ) ) . '">';
	foreach ( olga_languages() as $code => $language ) {
		printf( '<a href="%1$s" lang="%2$s" hreflang="%2$s"%3$s>%4$s</a>', esc_url( olga_language_url( $code ) ), esc_attr( $language['html'] ), $current === $code ? ' aria-current="true"' : '', esc_html( $language['label'] ) );
	}
	echo '</nav>';
}

function olga_filter_language_attributes( $output ) {
	$html = olga_languages()[ olga_current_language() ]['html'];
	if ( preg_match( '/lang=("|\')[^"\']+("|\')/', $output ) ) {
		return preg_replace( '/lang=("|\')[^"\']+("|\')/', 'lang="' . esc_attr( $html ) . '"', $output );
	}
	return 'lang="' . esc_attr( $html ) . '" ' . $output;
}
add_filter( 'language_attributes', 'olga_filter_language_attributes' );

function olga_filter_internal_url( $url ) {
	return is_admin() ? $url : olga_url( $url );
}
add_filter( 'post_type_link', 'olga_filter_internal_url' );
add_filter( 'post_link', 'olga_filter_internal_url' );

function olga_filter_page_url( $url, $post_id ) {
	return is_admin() ? $url : olga_page_url( $post_id );
}
add_filter( 'page_link', 'olga_filter_page_url', 10, 2 );

function olga_filter_archive_url( $url, $post_type ) {
	return ! is_admin() && 'project' === $post_type ? olga_archive_url() : $url;
}
add_filter( 'post_type_archive_link', 'olga_filter_archive_url', 10, 2 );

function olga_filter_menu_attributes( $atts ) {
	if ( ! empty( $atts['href'] ) ) {
		$fragment = wp_parse_url( $atts['href'], PHP_URL_FRAGMENT );
		if ( in_array( $fragment, array( 'about', 'projects', 'services', 'process', 'contacts' ), true ) ) {
			$atts['href'] = olga_section_url( $fragment );
			$atts['data-section-link'] = $fragment;
		} else {
			$atts['href'] = olga_url( $atts['href'] );
		}
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'olga_filter_menu_attributes' );

function olga_filter_menu_title( $title ) {
	$labels = array(
		'Главная' => array( 'uk' => 'Головна', 'en' => 'Home' ), 'Обо мне' => array( 'uk' => 'Про мене', 'en' => 'About' ),
		'Проекты' => array( 'uk' => 'Проєкти', 'en' => 'Projects' ), 'Услуги' => array( 'uk' => 'Послуги', 'en' => 'Services' ),
		'Этапы работы' => array( 'uk' => 'Етапи роботи', 'en' => 'Process' ), 'Этапы' => array( 'uk' => 'Етапи', 'en' => 'Process' ),
		'Контакты' => array( 'uk' => 'Контакти', 'en' => 'Contact' ),
	);
	return $labels[ $title ][ olga_current_language() ] ?? $title;
}
add_filter( 'nav_menu_item_title', 'olga_filter_menu_title' );

function olga_filter_document_title_parts( $parts ) {
	if ( is_singular() ) {
		$parts['title'] = olga_post_value( get_queried_object_id(), 'title' );
	} elseif ( is_post_type_archive( 'project' ) ) {
		$parts['title'] = olga_t( 'portfolio' );
	}
	return $parts;
}
add_filter( 'document_title_parts', 'olga_filter_document_title_parts' );

function olga_filter_front_document_title( $title ) {
	return is_front_page() ? olga_t( 'site_title' ) : $title;
}
add_filter( 'pre_get_document_title', 'olga_filter_front_document_title' );

function olga_disable_language_route_canonical( $redirect ) {
	return get_query_var( 'olga_lang' ) ? false : $redirect;
}
add_filter( 'redirect_canonical', 'olga_disable_language_route_canonical' );

function olga_hreflang_links() {
	foreach ( olga_languages() as $code => $language ) {
		echo '<link rel="alternate" hreflang="' . esc_attr( $language['html'] ) . '" href="' . esc_url( olga_language_url( $code ) ) . '">' . "\n";
	}
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( olga_language_url( 'ru' ) ) . '">' . "\n";
}
add_action( 'wp_head', 'olga_hreflang_links', 2 );
