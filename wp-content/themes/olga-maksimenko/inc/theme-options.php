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

/** Register the ordered selection used by the horizontal projects section. */
function olga_register_home_projects_setting() {
	register_setting( 'olga_home_projects_group', 'olga_home_project_ids', 'olga_sanitize_home_project_ids' );
}
add_action( 'admin_init', 'olga_register_home_projects_setting' );

function olga_sanitize_home_project_ids( $value ) {
	$ids   = is_array( $value ) ? $value : explode( ',', (string) $value );
	$valid = array();

	foreach ( array_unique( array_map( 'absint', $ids ) ) as $project_id ) {
		if ( $project_id && 'project' === get_post_type( $project_id ) && 'publish' === get_post_status( $project_id ) ) {
			$valid[] = $project_id;
		}
		if ( 6 === count( $valid ) ) {
			break;
		}
	}

	return $valid;
}

function olga_add_home_projects_page() {
	add_submenu_page(
		'edit.php?post_type=project',
		'Проекты на главной',
		'Проекты на главной',
		'manage_options',
		'olga-home-projects',
		'olga_home_projects_page'
	);
}
add_action( 'admin_menu', 'olga_add_home_projects_page' );

/** IDs selected in admin; an empty array lets the front page use its legacy fallback. */
function olga_home_project_ids() {
	$value = get_option( 'olga_home_project_ids', array() );
	return olga_sanitize_home_project_ids( $value );
}

function olga_admin_project_choice( $project, $selected = false ) {
	$thumbnail = get_the_post_thumbnail_url( $project, 'thumbnail' );
	?>
	<li class="olga-project-choice<?php echo $selected ? ' is-selected' : ''; ?>" data-id="<?php echo esc_attr( $project->ID ); ?>">
		<span class="olga-project-choice__handle dashicons dashicons-move" aria-hidden="true"></span>
		<?php if ( $thumbnail ) : ?><img src="<?php echo esc_url( $thumbnail ); ?>" alt=""><?php else : ?><span class="olga-project-choice__placeholder dashicons dashicons-format-image"></span><?php endif; ?>
		<span class="olga-project-choice__title"><?php echo esc_html( get_the_title( $project ) ); ?></span>
		<button type="button" class="button olga-project-choice__action" data-project-toggle><?php echo $selected ? 'Убрать' : 'Добавить'; ?></button>
	</li>
	<?php
}

function olga_home_projects_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$projects     = get_posts( array( 'post_type' => 'project', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
	$projects_by_id = array();
	foreach ( $projects as $project ) {
		$projects_by_id[ $project->ID ] = $project;
	}
	$selected_ids = olga_home_project_ids();
	?>
	<div class="wrap olga-home-projects" data-home-projects data-max-projects="6">
		<h1>Проекты на главной</h1>
		<p>Выберите до шести проектов для горизонтального блока. Перетаскивайте выбранные проекты, чтобы изменить порядок показа.</p>
		<form action="options.php" method="post">
			<?php settings_fields( 'olga_home_projects_group' ); ?>
			<input type="hidden" name="olga_home_project_ids" value="<?php echo esc_attr( implode( ',', $selected_ids ) ); ?>" data-project-selection>
			<div class="olga-project-selector">
				<section>
					<h2>На главной <span data-selected-count><?php echo esc_html( count( $selected_ids ) ); ?></span>/6</h2>
					<ul class="olga-project-choice-list is-selected-list" data-selected-projects>
						<?php foreach ( $selected_ids as $project_id ) : if ( isset( $projects_by_id[ $project_id ] ) ) { olga_admin_project_choice( $projects_by_id[ $project_id ], true ); } endforeach; ?>
					</ul>
					<p class="description" data-selected-empty<?php echo $selected_ids ? ' hidden' : ''; ?>>Пока ничего не выбрано — сайт использует первые шесть проектов по текущему порядку.</p>
				</section>
				<section>
					<h2>Все проекты</h2>
					<ul class="olga-project-choice-list" data-available-projects>
						<?php foreach ( $projects as $project ) : if ( ! in_array( $project->ID, $selected_ids, true ) ) { olga_admin_project_choice( $project ); } endforeach; ?>
					</ul>
				</section>
			</div>
			<?php submit_button( 'Сохранить выбор' ); ?>
		</form>
	</div>
	<?php
}

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
