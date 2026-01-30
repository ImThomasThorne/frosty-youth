<?php
/**
 * Homepage Slider Block Template
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'homepage-slider-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'homepage-slider';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

// Load values and assign defaults.
$slider_images = get_field( 'slider_images' );
$slider_title = get_field( 'slider_title' ) ?: 'Your Title Here';
$slider_text = get_field( 'slider_text' ) ?: 'Your description text goes here';
$button_text = get_field( 'button_text' ) ?: 'Learn More';
$button_link = get_field( 'button_link' ) ?: '#';
$autoplay = get_field( 'autoplay' ) ?: true;
$autoplay_speed = get_field( 'autoplay_speed' ) ?: 5000;

?>
<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class_name ); ?>">
	<div class="homepage-slider__wrapper">
		<?php if ( $slider_images ) : ?>
			<div class="homepage-slider__slides">
				<?php foreach ( $slider_images as $index => $image ) : ?>
					<div class="homepage-slider__slide <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>">
						<img
							src="<?php echo esc_url( $image['url'] ); ?>"
							alt="<?php echo esc_attr( $image['alt'] ); ?>"
							loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
						/>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="homepage-slider__overlay">
			<div class="homepage-slider__content">
				<?php if ( $slider_title ) : ?>
					<h1 class="homepage-slider__title"><?php echo esc_html( $slider_title ); ?></h1>
				<?php endif; ?>

				<?php if ( $slider_text ) : ?>
					<p class="homepage-slider__text"><?php echo esc_html( $slider_text ); ?></p>
				<?php endif; ?>

				<?php if ( $button_text && $button_link ) : ?>
					<a href="<?php echo esc_url( $button_link ); ?>" class="homepage-slider__button wp-element-button">
						<?php echo esc_html( $button_text ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( $slider_images && count( $slider_images ) > 1 ) : ?>
			<!-- Navigation Arrows -->
			<button class="homepage-slider__arrow homepage-slider__arrow--prev" aria-label="Previous slide">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>
			<button class="homepage-slider__arrow homepage-slider__arrow--next" aria-label="Next slide">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</button>

			<!-- Dots Navigation -->
			<div class="homepage-slider__dots">
				<?php foreach ( $slider_images as $index => $image ) : ?>
					<button
						class="homepage-slider__dot <?php echo $index === 0 ? 'active' : ''; ?>"
						data-slide="<?php echo $index; ?>"
						aria-label="Go to slide <?php echo $index + 1; ?>"
					></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>

<script>
(function() {
	const slider = document.getElementById('<?php echo esc_js( $id ); ?>');
	if (!slider) return;

	const slides = slider.querySelectorAll('.homepage-slider__slide');
	const dots = slider.querySelectorAll('.homepage-slider__dot');
	const prevBtn = slider.querySelector('.homepage-slider__arrow--prev');
	const nextBtn = slider.querySelector('.homepage-slider__arrow--next');

	if (slides.length <= 1) return;

	let currentSlide = 0;
	const autoplay = <?php echo json_encode( $autoplay ); ?>;
	const autoplaySpeed = <?php echo intval( $autoplay_speed ); ?>;
	let autoplayTimer = null;

	function showSlide(index) {
		slides.forEach(slide => slide.classList.remove('active'));
		dots.forEach(dot => dot.classList.remove('active'));

		slides[index].classList.add('active');
		dots[index].classList.add('active');
		currentSlide = index;
	}

	function nextSlide() {
		const next = (currentSlide + 1) % slides.length;
		showSlide(next);
	}

	function prevSlide() {
		const prev = (currentSlide - 1 + slides.length) % slides.length;
		showSlide(prev);
	}

	function startAutoplay() {
		if (autoplay && autoplaySpeed > 0) {
			stopAutoplay();
			autoplayTimer = setInterval(nextSlide, autoplaySpeed);
		}
	}

	function stopAutoplay() {
		if (autoplayTimer) {
			clearInterval(autoplayTimer);
			autoplayTimer = null;
		}
	}

	// Navigation buttons
	if (prevBtn) {
		prevBtn.addEventListener('click', () => {
			prevSlide();
			stopAutoplay();
		});
	}

	if (nextBtn) {
		nextBtn.addEventListener('click', () => {
			nextSlide();
			stopAutoplay();
		});
	}

	// Dots navigation
	dots.forEach((dot, index) => {
		dot.addEventListener('click', () => {
			showSlide(index);
			stopAutoplay();
		});
	});

	// Keyboard navigation
	slider.addEventListener('keydown', (e) => {
		if (e.key === 'ArrowLeft') {
			prevSlide();
			stopAutoplay();
		} else if (e.key === 'ArrowRight') {
			nextSlide();
			stopAutoplay();
		}
	});

	// Pause on hover
	slider.addEventListener('mouseenter', stopAutoplay);
	slider.addEventListener('mouseleave', startAutoplay);

	// Start autoplay
	startAutoplay();
})();
</script>
