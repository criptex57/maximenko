<?php
/** Global theme settings. */
defined( 'ABSPATH' ) || exit;

function olga_option_fields() {
	return array(
		'name'            => array( 'Имя специалиста', 'Ольга Максименко', 'text' ),
		'name_uk'         => array( 'Имя специалиста — українська', 'Ольга Максименко', 'text' ),
		'name_en'         => array( 'Имя специалиста — English', 'Olga Maksimenko', 'text' ),
		'role'            => array( 'Специализация', 'Дизайнер интерьеров', 'text' ),
		'role_uk'         => array( 'Специализация — українська', 'Дизайнерка інтер’єрів', 'text' ),
		'role_en'         => array( 'Специализация — English', 'Interior designer', 'text' ),
		'hero_title'      => array( 'Заголовок первого экрана', 'Дизайн интерьеров, в которых хочется жить', 'text' ),
		'hero_text'       => array( 'Описание первого экрана', 'Создаю продуманные пространства, которые отражают характер владельца, остаются актуальными годами и делают повседневную жизнь комфортнее.', 'textarea' ),
		'about_text'      => array( 'Текст «Обо мне»', 'Я создаю интерьеры, в которых эстетика работает вместе с функциональностью. Для меня дизайн — это создание целостного пространства, соответствующего образу жизни человека.', 'textarea' ),
		'about_text_uk'   => array( 'Текст «Обо мне» — українська', 'Я створюю інтер’єри, у яких естетика працює разом із функціональністю. Для мене дизайн — це цілісний простір, що відповідає способу життя людини.', 'textarea' ),
		'about_text_en'   => array( 'Текст «Обо мне» — English', 'I create interiors where aesthetics and function work together. For me, design means shaping a complete space around the way a person lives.', 'textarea' ),
		'experience'      => array( 'Лет опыта', '8+', 'text' ),
		'projects_count'  => array( 'Реализовано проектов', '60+', 'text' ),
		'phone'           => array( 'Телефон', '+380 67 000 00 00', 'text' ),
		'email'           => array( 'Email', 'hello@maksimenko.design', 'email' ),
		'city'            => array( 'География работы', 'Киев · Европа · онлайн', 'text' ),
		'instagram'       => array( 'Instagram', 'https://www.instagram.com/mcseamnko_3dviz/', 'url' ),
		'telegram'        => array( 'Telegram', '#', 'url' ),
		'form_email'      => array( 'Email для заявок', get_option( 'admin_email' ), 'email' ),
		'process_steps'   => array( 'Этапы работы — по одному на строку', "Знакомство и бриф\nОбмеры и анализ пространства\nПланировочное решение\nРазработка концепции\n3D-визуализация\nРабочая документация\nКомплектация\nАвторский надзор", 'textarea' ),
		'process_steps_uk' => array( 'Этапы работы — українська', "Знайомство та бриф\nОбміри й аналіз простору\nПланувальне рішення\nРозробка концепції\n3D-візуалізація\nРобоча документація\nКомплектація\nАвторський нагляд", 'textarea' ),
		'process_steps_en' => array( 'Этапы работы — English', "Introduction and brief\nMeasurements and spatial analysis\nLayout solution\nConcept development\n3D visualization\nWorking documentation\nProcurement\nDesign supervision", 'textarea' ),
		'footer_note'     => array( 'Подпись в подвале', 'Создаю пространства с характером — от идеи до реализации.', 'textarea' ),
		'footer_note_uk'  => array( 'Подпись в подвале — українська', 'Створюю простори з характером — від ідеї до реалізації.', 'textarea' ),
		'footer_note_en'  => array( 'Подпись в подвале — English', 'I create spaces with character — from concept to completion.', 'textarea' ),
		'seo_description' => array( 'SEO description', 'Ольга Максименко — дизайнер жилых и коммерческих интерьеров. Дизайн-проекты, визуализация, комплектация и авторский надзор.', 'textarea' ),
		'seo_description_uk' => array( 'SEO description — українська', 'Ольга Максименко — дизайнерка житлових і комерційних інтер’єрів. Дизайн-проєкти, візуалізація, комплектація та авторський нагляд.', 'textarea' ),
		'seo_description_en' => array( 'SEO description — English', 'Olga Maksimenko is a residential and commercial interior designer offering design projects, visualization, procurement and design supervision.', 'textarea' ),
	);
}

function olga_register_settings() {
	register_setting( 'olga_options_group', 'olga_theme_options', 'olga_sanitize_options' );
}
add_action( 'admin_init', 'olga_register_settings' );

function olga_add_options_page() {
	add_options_page( 'Настройки сайта', 'Настройки портфолио', 'manage_options', 'olga-options', 'olga_options_page' );
}
add_action( 'admin_menu', 'olga_add_options_page' );

function olga_sanitize_options( $input ) {
	$output = array();
	foreach ( olga_option_fields() as $key => $field ) {
		$value          = isset( $input[ $key ] ) ? wp_unslash( $input[ $key ] ) : '';
		$output[ $key ] = 'email' === $field[2] ? sanitize_email( $value ) : ( 'url' === $field[2] ? esc_url_raw( $value ) : ( 'textarea' === $field[2] ? sanitize_textarea_field( $value ) : sanitize_text_field( $value ) ) );
	}
	return $output;
}

function olga_options_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$options = get_option( 'olga_theme_options', array() );
	?>
	<div class="wrap">
		<h1>Настройки портфолио</h1>
		<p>Основные тексты, контакты, статистика и этапы работы.</p>
		<form action="options.php" method="post">
			<?php settings_fields( 'olga_options_group' ); ?>
			<table class="form-table" role="presentation">
				<?php foreach ( olga_option_fields() as $key => $field ) : $value = $options[ $key ] ?? $field[1]; ?>
				<tr>
					<th scope="row"><label for="olga-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[0] ); ?></label></th>
					<td>
						<?php if ( 'textarea' === $field[2] ) : ?>
							<textarea class="large-text" rows="5" id="olga-<?php echo esc_attr( $key ); ?>" name="olga_theme_options[<?php echo esc_attr( $key ); ?>]"><?php echo esc_textarea( $value ); ?></textarea>
						<?php else : ?>
							<input class="regular-text" type="<?php echo esc_attr( $field[2] ); ?>" id="olga-<?php echo esc_attr( $key ); ?>" name="olga_theme_options[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $value ); ?>">
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
