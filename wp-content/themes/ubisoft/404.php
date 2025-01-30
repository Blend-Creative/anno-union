<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package ubisoft
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<section class="imprint-hero" style="margin-top: 144px; background: white;">
			<div class="container">
				<div class="row">
					<div class="col-md-6">
						<h2 class="h1"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'ubisoft' ); ?></h2>
					</div>
				</div>
			</div>
		</section>
		
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
