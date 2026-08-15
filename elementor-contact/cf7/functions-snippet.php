<?php
/**
 * کاوران — افزودنی‌های اختیاری Contact Form 7
 * -----------------------------------------------------------------------
 * این کد را در functions.php قالبِ فرزند یا در افزونه‌ی Code Snippets بگذارید.
 * هیچ‌کدام برای کارکردن فرم‌ها الزامی نیست؛ فقط کیفیت داده را بالا می‌برد.
 * -----------------------------------------------------------------------
 */

/**
 * ۰) غیرفعال‌کردن wpautop روی فرم‌های کاوران.
 *
 *    CF7 روی محتوای فرم wpautop اجرا می‌کند و خودکار <p> و <br> اضافه می‌کند؛
 *    مارکاپ دو-ستونه‌ی ما به آن نیاز ندارد و به‌هم می‌ریزد.
 *
 *    ⚠ این کد اختیاری است — بلوک 00-base.html خودش با CSS این <p> و <br>ها را
 *      خنثی می‌کند و ظاهر فرم بدون هیچ کد PHP درست است. این اسنیپت فقط برای
 *      کسانی است که می‌خواهند خروجی HTML هم تمیز بماند.
 *
 *    فقط روی فرم‌هایی اثر می‌گذارد که عنوانشان با «کاوران» شروع می‌شود، تا
 *      سایر فرم‌های سایت دست‌نخورده بمانند.
 */
add_filter( 'wpcf7_autop_or_not', 'kaverun_disable_cf7_autop' );
function kaverun_disable_cf7_autop( $autop ) {
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return $autop;
	}

	$form = WPCF7_ContactForm::get_current();

	if ( $form && 0 === strpos( $form->title(), 'کاوران' ) ) {
		return false;
	}

	return $autop;
}

/**
 * ۱) اعتبارسنجی شماره‌ی موبایل/ثابت ایران روی فیلدهای kv-phone.
 *    اعداد فارسی و عربی هم پذیرفته و به انگلیسی تبدیل می‌شوند.
 */
add_filter( 'wpcf7_validate_tel*', 'kaverun_validate_ir_phone', 20, 2 );
add_filter( 'wpcf7_validate_tel',  'kaverun_validate_ir_phone', 20, 2 );
function kaverun_validate_ir_phone( $result, $tag ) {
	if ( 'kv-phone' !== $tag->name ) {
		return $result;
	}

	$value = isset( $_POST['kv-phone'] ) ? trim( (string) $_POST['kv-phone'] ) : '';
	if ( '' === $value ) {
		return $result; // فیلد خالی را خود CF7 مدیریت می‌کند
	}

	$value = kaverun_fa_to_en_digits( $value );
	$value = preg_replace( '/[^\d+]/', '', $value );
	$value = preg_replace( '/^(\+98|0098|98)/', '0', $value );

	// موبایل: 09xxxxxxxxx — ثابت: 0xxxxxxxxxx (۱۱ رقم)
	if ( ! preg_match( '/^0\d{10}$/', $value ) ) {
		$result->invalidate( $tag, 'شماره تماس معتبر نیست (مثلاً ۰۹۱۲۱۲۳۴۵۶۷).' );
	}

	return $result;
}

/**
 * ۲) تبدیل ارقام فارسی/عربی به انگلیسی.
 */
function kaverun_fa_to_en_digits( $str ) {
	$fa = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	$ar = array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' );
	$en = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
	return str_replace( $ar, $en, str_replace( $fa, $en, $str ) );
}

/**
 * ۳) نرمال‌سازی شماره پیش از ارسال ایمیل، تا در ایمیل همیشه 09xxxxxxxxx ذخیره شود.
 */
add_filter( 'wpcf7_posted_data', 'kaverun_normalize_posted_phone' );
function kaverun_normalize_posted_phone( $data ) {
	if ( ! empty( $data['kv-phone'] ) ) {
		$p = kaverun_fa_to_en_digits( $data['kv-phone'] );
		$p = preg_replace( '/[^\d+]/', '', $p );
		$data['kv-phone'] = preg_replace( '/^(\+98|0098|98)/', '0', $p );
	}
	return $data;
}

/**
 * ۴) اسکریپت‌های CF7 فقط در صفحاتی که واقعاً فرم دارند بارگذاری شوند.
 *    (اختیاری — به سرعت بقیه‌ی صفحات کمک می‌کند.)
 *    اگر فرم را داخل پاپ‌آپ یا فوتر می‌گذارید، این بخش را فعال نکنید.
 */
// add_filter( 'wpcf7_load_js', '__return_false' );
// add_action( 'wp_enqueue_scripts', function () {
// 	if ( is_page( 'contact' ) && function_exists( 'wpcf7_enqueue_scripts' ) ) {
// 		wpcf7_enqueue_scripts();
// 	}
// } );
