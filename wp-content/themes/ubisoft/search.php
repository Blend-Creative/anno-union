<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package ubisoft
 */

get_header();
?>

	<!-- Breadcrumbs -->
	<?php
		if ( function_exists('yoast_breadcrumb') ) {
			echo '<div class="container">';
				echo '<div class="row">';
					echo '<div class="col-sm-12 breadcrumbs-container">';
						yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
	?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Search Results for: %s', 'ubisoft' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</div>
		</div>
	</div>
	
	<div class="container post-meta-container">
		<div class="row section-post-filter">
			<?php if ( have_posts() ) : ?>
				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					get_template_part( 'template-parts/content', 'search' );

				endwhile;

				the_posts_navigation();

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>
		</div>
	</div>


<?php
get_sidebar();
get_footer();
