<?php
/** Site footer. */
defined( 'ABSPATH' ) || exit;
?>
</main>
<footer class="site-footer" id="contacts">
	<div class="footer__lead">
		<p class="eyebrow"><?php echo esc_html( olga_t( 'start_dialogue' ) ); ?></p>
		<h2><?php echo esc_html( olga_t( 'footer_heading' ) ); ?></h2>
		<button class="button button--light" type="button" data-open-contact><?php echo esc_html( olga_t( 'discuss_project' ) ); ?> <span class="ui-arrow" aria-hidden="true"></span></button>
	</div>
	<div class="footer__grid">
		<div><strong><?php echo esc_html( olga_localized_option( 'name', 'name_default', 'Ольга Максименко' ) ); ?></strong><p><?php echo esc_html( olga_localized_option( 'footer_note', 'footer_note_default', 'Создаю пространства с характером — от идеи до реализации.' ) ); ?></p></div>
		<div><span class="footer__label"><?php echo esc_html( olga_t( 'contact' ) ); ?></span><a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', olga_option( 'phone' ) ) ); ?>"><?php echo esc_html( olga_option( 'phone' ) ); ?></a><a href="mailto:<?php echo esc_attr( olga_option( 'email' ) ); ?>"><?php echo esc_html( olga_option( 'email' ) ); ?></a></div>
		<div><span class="footer__label"><?php echo esc_html( olga_t( 'social' ) ); ?></span><a href="<?php echo esc_url( olga_option( 'instagram' ) ); ?>" target="_blank" rel="noopener">Instagram <span class="ui-arrow" aria-hidden="true"></span></a><a href="<?php echo esc_url( olga_option( 'telegram' ) ); ?>" target="_blank" rel="noopener">Telegram <span class="ui-arrow" aria-hidden="true"></span></a></div>
	</div>
	<div class="footer__bottom"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( olga_localized_option( 'name', 'name_default', 'Ольга Максименко' ) ); ?></span><a href="<?php echo esc_url( olga_url( get_privacy_policy_url() ) ); ?>"><?php echo esc_html( olga_t( 'privacy' ) ); ?></a><button type="button" data-to-top><span class="footer__to-top-label"><?php echo esc_html( olga_t( 'top' ) ); ?></span> <span class="ui-arrow-up footer__to-top-arrow" aria-hidden="true"></span></button></div>
</footer>

<div class="contact-modal" role="dialog" aria-modal="true" aria-labelledby="contact-title" aria-hidden="true" data-contact-modal>
	<div class="contact-modal__backdrop" data-close-contact></div>
	<div class="contact-modal__panel" data-lenis-prevent>
		<button class="modal-close" type="button" aria-label="<?php echo esc_attr( olga_t( 'close' ) ); ?>" data-close-contact>×</button>
		<p class="eyebrow"><?php echo esc_html( olga_t( 'tell_task' ) ); ?></p>
		<h2 id="contact-title"><?php echo esc_html( olga_t( 'discuss_project' ) ); ?></h2>
		<form class="contact-form" data-contact-form novalidate>
			<div class="form-grid">
				<label><?php echo esc_html( olga_t( 'your_name' ) ); ?> *<input name="name" autocomplete="name" placeholder="<?php echo esc_attr( olga_t( 'name_placeholder' ) ); ?>" required minlength="2"></label>
				<label><?php echo esc_html( olga_t( 'contact_method' ) ); ?> *<input name="contact" autocomplete="tel" placeholder="<?php echo esc_attr( olga_t( 'contact_placeholder' ) ); ?>" required minlength="5"></label>
				<label>Email<input type="email" name="email" autocomplete="email" placeholder="<?php echo esc_attr( olga_t( 'email_placeholder' ) ); ?>"></label>
				<label><?php echo esc_html( olga_t( 'object_type' ) ); ?><select name="object"><option value="" selected><?php echo esc_html( olga_t( 'object_placeholder' ) ); ?></option><option><?php echo esc_html( olga_t( 'apartment' ) ); ?></option><option><?php echo esc_html( olga_t( 'private_house' ) ); ?></option><option><?php echo esc_html( olga_t( 'commercial_space' ) ); ?></option><option><?php echo esc_html( olga_t( 'other' ) ); ?></option></select></label>
				<label><?php echo esc_html( olga_t( 'area' ) ); ?><input name="area" placeholder="<?php echo esc_attr( olga_t( 'area_placeholder' ) ); ?>"></label>
				<label><?php echo esc_html( olga_t( 'city' ) ); ?><input name="city" autocomplete="address-level2" placeholder="<?php echo esc_attr( olga_t( 'city_placeholder' ) ); ?>"></label>
				<label><?php echo esc_html( olga_t( 'budget' ) ); ?><input name="budget" placeholder="<?php echo esc_attr( olga_t( 'budget_placeholder' ) ); ?>"></label>
				<label class="form-wide"><?php echo esc_html( olga_t( 'message' ) ); ?><textarea name="message" rows="3" placeholder="<?php echo esc_attr( olga_t( 'message_placeholder' ) ); ?>"></textarea></label>
			</div>
			<label class="honeypot" aria-hidden="true"><?php echo esc_html( olga_t( 'website' ) ); ?><input name="website" tabindex="-1" autocomplete="off"></label>
			<label class="form-consent"><input type="checkbox" name="agree" required> <span><?php echo wp_kses_post( olga_t( 'consent', '<a href="' . esc_url( olga_url( get_privacy_policy_url() ) ) . '">' . esc_html( olga_t( 'privacy' ) ) . '</a>' ) ); ?></span></label>
			<button class="button form-submit" type="submit">
				<span class="form-submit__label"><?php echo esc_html( olga_t( 'submit' ) ); ?></span>
				<span class="ui-arrow form-submit__arrow" aria-hidden="true"></span>
				<span class="form-submit__loader" aria-hidden="true"><i></i></span>
			</button>
			<p class="form-status" aria-live="polite" data-form-status></p>
		</form>
	</div>
</div>

<div class="form-success-toast" role="status" aria-live="polite" aria-hidden="true" data-success-toast>
	<span class="form-success-toast__mark" aria-hidden="true">✓</span>
	<span class="form-success-toast__copy"><strong><?php echo esc_html( olga_t( 'form_success_title' ) ); ?></strong><span data-success-toast-message></span></span>
	<button type="button" aria-label="<?php echo esc_attr( olga_t( 'close' ) ); ?>" data-success-toast-close>×</button>
</div>
<?php wp_footer(); ?>
</body>
</html>
