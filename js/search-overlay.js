document.addEventListener('DOMContentLoaded', function() {
  // Find the original search form in the header
  const headerSearch = document.querySelector('.wp-block-navigation .wp-block-search');

  if (!headerSearch) return;

  // Create overlay elements
  const overlay = document.createElement('div');
  overlay.className = 'search-overlay';
  overlay.setAttribute('aria-hidden', 'true');

  const overlayContent = document.createElement('div');
  overlayContent.className = 'search-overlay-content';

  const closeButton = document.createElement('button');
  closeButton.className = 'search-overlay-close';
  closeButton.innerHTML = '&times;';
  closeButton.setAttribute('aria-label', 'Close search');

  // Clone the search form for the overlay
  const searchClone = headerSearch.cloneNode(true);

  // Make sure the cloned input is visible in the overlay
  const clonedInput = searchClone.querySelector('.wp-block-search__input');
  if (clonedInput) {
    clonedInput.style.display = 'block';
  }

  // Assemble overlay
  overlayContent.appendChild(closeButton);
  overlayContent.appendChild(searchClone);
  overlay.appendChild(overlayContent);
  document.body.appendChild(overlay);

  // Get header button
  const headerButton = headerSearch.querySelector('.wp-block-search__button');

  // Open overlay when header search button is clicked
  if (headerButton) {
    headerButton.addEventListener('click', function(e) {
      e.preventDefault();
      overlay.classList.add('active');
      overlay.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';

      // Focus on the search input in the overlay
      const overlayInput = overlay.querySelector('.wp-block-search__input');
      if (overlayInput) {
        setTimeout(() => overlayInput.focus(), 100);
      }
    });
  }

  // Close overlay function
  function closeOverlay() {
    overlay.classList.remove('active');
    overlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  // Close on close button click
  closeButton.addEventListener('click', closeOverlay);

  // Close on overlay background click
  overlay.addEventListener('click', function(e) {
    if (e.target === overlay) {
      closeOverlay();
    }
  });

  // Close on escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && overlay.classList.contains('active')) {
      closeOverlay();
    }
  });
});
