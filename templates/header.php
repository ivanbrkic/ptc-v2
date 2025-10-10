<?php
if ( in_array( 'ld-in-focus-mode', get_body_class(), true ) ) {
	return;
}

?>
<header class="header zi-5">
	<div class="wrapper">
		<div class="header__inner">
			<?php get_template_part( 'templates/branding' ); ?>
			<nav class="main-navigation <?php echo is_checkout() ? 'd-none' : ''; ?>" role="navigation">
				<?php
				$courses = learndash_user_get_enrolled_courses( get_current_user_id() );
				if ( $courses && is_array( $courses ) ) {
					if ( count( $courses ) > 2 ) {
						$courses = array_slice( $courses, 0, 2 );
					}
					foreach ( $courses as $course_id ) {
						?>
						<li class="main-navigation__item">
							<a href="<?php echo esc_url( learndash_get_course_url( $course_id ) ); ?>" class="main-navigation__link flex-line-05">
								<?php	echo get_the_title( $course_id ); ?>
							</a>
						</li>
						<?php
					}
				}
				?>
				<li class="main-navigation__item">
					<a href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>" class="main-navigation__link flex-line-05">
						<img src="/wp-content/themes/ptc-v2/assets/dist/theme/img/account-svgrepo-com.svg" alt="" style="width: 20px; height: 20px;">
						<?php
						if ( is_user_logged_in() ) {
							esc_html_e( 'My account', 'ptc' );
						} else {
							esc_html_e( 'Login', 'ptc' );
						}
						?>
					</a>
				</li>
			</nav>
			<div class="menu-toggle__container <?php echo is_checkout() ? 'd-none' : ''; ?>">
				<div class="menu-toggle js-menu-toggle">
					<div class="menu-toggle__stripe"></div>
					<div class="menu-toggle__stripe"></div>
					<div class="menu-toggle__stripe"></div>
				</div>
			</div>
		</div>
	</div>
</header>
