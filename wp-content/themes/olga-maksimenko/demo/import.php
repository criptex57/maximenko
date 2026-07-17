<?php
/**
 * Idempotent demo importer, executed by WP-CLI on first start.
 *
 * @package OlgaMaksimenko
 */

defined( 'ABSPATH' ) || exit;

if ( get_option( 'olga_demo_imported' ) ) {
	echo "Demo content already exists.\n";
	return;
}

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$theme_dir = get_template_directory();
$uploads   = array();
for ( $i = 1; $i <= 15; $i++ ) {
	$source = $theme_dir . '/demo/work-' . str_pad( (string) $i, 2, '0', STR_PAD_LEFT ) . '.jpg';
	if ( ! file_exists( $source ) ) {
		continue;
	}
	$temp = wp_tempnam( basename( $source ) );
	copy( $source, $temp );
	$id = media_handle_sideload( array( 'name' => basename( $source ), 'tmp_name' => $temp ), 0, 'Визуализация интерьера Ольги Максименко' );
	if ( ! is_wp_error( $id ) ) {
		update_post_meta( $id, '_wp_attachment_image_alt', 'Современный интерьер — проект Ольги Максименко' );
		$uploads[] = $id;
	}
}

$categories = array( 'Квартиры', 'Частные дома', 'Коммерческие пространства' );
foreach ( $categories as $category ) {
	if ( ! term_exists( $category, 'project_category' ) ) {
		wp_insert_term( $category, 'project_category' );
	}
}

$projects = array(
	array( 'Тихая гавань', 'Киев', '118 м²', '2026', 'Квартиры', 'Светлый семейный интерьер, построенный на естественных фактурах, мягком свете и точных сценариях хранения.', 'Создать спокойное пространство для семьи с двумя детьми, сохранив ощущение воздуха.', "Натуральный дуб и тактильный текстиль\nСкрытые системы хранения\nНесколько сценариев освещения" ),
	array( 'Plum Residence', 'Варшава', '146 м²', '2025', 'Квартиры', 'Выразительная квартира с глубокими оттенками, скульптурной мебелью и современной графикой.', 'Объединить представительскую эстетику и комфорт ежедневной жизни.', "Единая ось гостиной и кухни\nАкцентный природный камень\nАвторская мебель" ),
	array( 'Дом у сосен', 'Буча', '240 м²', '2025', 'Частные дома', 'Дом для неторопливой жизни, где архитектура и ландшафт продолжают друг друга.', 'Наполнить дом природным светом и связать общие зоны с садом.', "Панорамное остекление\nТёплая нейтральная палитра\nНатуральный шпон" ),
	array( 'Soft Geometry', 'Львов', '92 м²', '2024', 'Квартиры', 'Мягкая геометрия, сложные нейтральные оттенки и тщательно подобранный свет.', 'Организовать компактную квартиру без ощущения тесноты.', "Радиусные перегородки\nВстроенная мебель\nВизуально лёгкие конструкции" ),
	array( 'Atelier 44', 'Киев', '174 м²', '2024', 'Коммерческие пространства', 'Камерное пространство студии с архитектурной пластикой и выразительным светом.', 'Создать узнаваемый интерьер, который работает на бренд.', "Модульная планировка\nГрафичная навигация\nСветовые акценты" ),
	array( 'Monochrome', 'Прага', '132 м²', '2023', 'Квартиры', 'Монохромный интерьер с богатством фактур и сдержанным характером.', 'Сформировать вневременной интерьер для длительного проживания.', "Микроцемент и натуральный камень\nМинимум визуального шума\nПредметное искусство" ),
);

$project_ids = array();
foreach ( $projects as $index => $project ) {
	$post_id = wp_insert_post(
		array(
			'post_type'    => 'project',
			'post_status'  => 'publish',
			'post_title'   => $project[0],
			'post_excerpt' => $project[5],
			'post_content' => '<p>' . esc_html( $project[5] ) . '</p><p>Каждое решение в проекте подчинено общей идее: пространство должно быть красивым, удобным и естественным для своих владельцев.</p>',
			'menu_order'   => $index,
		)
	);
	if ( is_wp_error( $post_id ) ) {
		continue;
	}
	$project_ids[] = $post_id;
	update_post_meta( $post_id, '_olga_city', $project[1] );
	update_post_meta( $post_id, '_olga_area', $project[2] );
	update_post_meta( $post_id, '_olga_year', $project[3] );
	update_post_meta( $post_id, '_olga_task', $project[6] );
	update_post_meta( $post_id, '_olga_solution', $project[7] );
	if ( isset( $uploads[ $index ] ) ) {
		set_post_thumbnail( $post_id, $uploads[ $index ] );
	}
	$gallery = array_slice( $uploads, 6 + $index, 3 );
	update_post_meta( $post_id, '_olga_gallery', implode( ',', $gallery ) );
	wp_set_object_terms( $post_id, $project[4], 'project_category' );
}

$services = array(
	array( 'Дизайн-проект квартиры', 'Полный проект жилого интерьера: от планировки до рабочей документации.', '8–14 недель', "Обмерный план\nПланировочные решения\nКонцепция и 3D-визуализации\nКомплект рабочих чертежей\nВедомость материалов" ),
	array( 'Дизайн частного дома', 'Целостная концепция дома с учётом архитектуры, инженерии и образа жизни семьи.', '12–20 недель', "Функциональное зонирование\nКонцепция всех помещений\n3D-визуализации\nРабочая документация\nСпецификации" ),
	array( 'Коммерческий интерьер', 'Пространство, которое выражает характер бренда и помогает бизнесу.', '10–18 недель', "Анализ сценариев\nПланировка\nКонцепция бренда в интерьере\nДокументация\nКомплектация" ),
	array( 'Планировочное решение', 'Несколько точных вариантов организации пространства с расстановкой мебели.', '2–3 недели', "Обмеры и анализ\n2–3 варианта планировки\nФинальная схема\nЭкспликация помещений" ),
	array( 'Авторский надзор', 'Контроль соответствия реализации проекту и оперативное решение вопросов стройки.', 'На период реализации', "Регулярные выезды\nПроверка соответствия проекту\nРабота с подрядчиками\nКорректировки" ),
	array( 'Комплектация интерьера', 'Подбор, заказ и координация поставок материалов, мебели, света и декора.', 'По графику проекта', "Тендер поставщиков\nСчета и графики\nКонтроль заказов\nОрганизация доставок" ),
	array( 'Консультация дизайнера', 'Сфокусированная встреча для решения конкретных вопросов по интерьеру.', '90 минут', "Предварительный бриф\nОнлайн или офлайн встреча\nПрактические рекомендации\nРезюме встречи" ),
);
foreach ( $services as $index => $service ) {
	$post_id = wp_insert_post( array( 'post_type' => 'service', 'post_status' => 'publish', 'post_title' => $service[0], 'post_excerpt' => $service[1], 'post_content' => '<p>' . esc_html( $service[1] ) . '</p><h2>Результат</h2><p>Вы получаете понятный, профессионально подготовленный результат и уверенность в дальнейших решениях.</p>', 'menu_order' => $index ) );
	update_post_meta( $post_id, '_olga_duration', $service[2] );
	update_post_meta( $post_id, '_olga_scope', $service[3] );
}

$reviews = array(
	array( 'Анна и Михаил', 'Квартира', 'Киев', 'Ольга услышала нас с первой встречи. Интерьер получился спокойным, очень личным и при этом невероятно удобным — именно таким мы его представляли.' ),
	array( 'Елена', 'Частный дом', 'Буча', 'Больше всего ценю системность: на каждом этапе мы понимали, что происходит и почему. Дом выглядит цельно, а жить в нём легко.' ),
	array( 'Мария', 'Квартира', 'Варшава', 'Удалённая работа прошла безупречно. Внимание к деталям и умение собрать сложные пожелания в ясную концепцию впечатляют.' ),
	array( 'Александр', 'Офис', 'Киев', 'Новое пространство изменило восприятие бренда и стало любимым местом команды. Проект выдержал и сроки, и бюджет.' ),
	array( 'Дарья', 'Квартира', 'Львов', 'Получился интерьер вне времени: тёплый, тактильный, с идеальным количеством вещей. Спустя год он нравится нам ещё больше.' ),
);
foreach ( $reviews as $index => $review ) {
	$post_id = wp_insert_post( array( 'post_type' => 'testimonial', 'post_status' => 'publish', 'post_title' => $review[0], 'post_content' => '<p>' . esc_html( $review[3] ) . '</p>', 'menu_order' => $index ) );
	update_post_meta( $post_id, '_olga_object', $review[1] );
	update_post_meta( $post_id, '_olga_city', $review[2] );
}

$pages = array(
	'Обо мне' => array( 'about', '<h2>Интерьеры как продолжение человека</h2><p>Я верю, что хорошее пространство начинается с внимательного диалога. Изучаю привычки, ритм и ценности клиента, чтобы создать интерьер, который не устареет и будет каждый день делать жизнь комфортнее.</p><h2>Мой подход</h2><p>Функция, эстетика и реалистичность решений всегда развиваются вместе. Я сопровождаю проект от первых эскизов до последней детали.</p>' ),
	'Контакты' => array( 'contacts', '<h2>Буду рада познакомиться</h2><p>Опишите объект, его площадь, город и желаемые сроки. Я отвечу и предложу удобный формат первой встречи.</p>' ),
	'Политика конфиденциальности' => array( 'privacy-policy', '<h2>Общие положения</h2><p>Данные из формы используются только для ответа на обращение и не передаются третьим лицам без законных оснований.</p><h2>Какие данные мы получаем</h2><p>Имя, контактные данные и сведения о проекте, которые пользователь указывает добровольно.</p>' ),
);
$page_ids = array();
foreach ( $pages as $title => $page ) {
	$page_ids[ $page[0] ] = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $title, 'post_name' => $page[0], 'post_content' => $page[1] ) );
}
if ( ! empty( $page_ids['privacy-policy'] ) ) {
	update_option( 'wp_page_for_privacy_policy', $page_ids['privacy-policy'] );
}

$menu_id = wp_create_nav_menu( 'Основное меню' );
if ( ! is_wp_error( $menu_id ) ) {
	$links = array(
		'Главная' => home_url( '/' ),
		'Обо мне' => home_url( '/#about' ),
		'Проекты' => home_url( '/#projects' ),
		'Услуги' => home_url( '/#services' ),
		'Этапы работы' => home_url( '/#process' ),
		'Отзывы' => home_url( '/#reviews' ),
		'Контакты' => home_url( '/#contacts' ),
	);
	foreach ( $links as $label => $url ) {
		wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => $label, 'menu-item-url' => $url, 'menu-item-status' => 'publish' ) );
	}
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary'] = $menu_id;
	$locations['footer']  = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

$defaults = array();
foreach ( olga_option_fields() as $key => $field ) {
	$defaults[ $key ] = $field[1];
}
update_option( 'olga_theme_options', $defaults );
update_option( 'olga_demo_imported', gmdate( 'c' ) );
echo "Demo content imported successfully.\n";

