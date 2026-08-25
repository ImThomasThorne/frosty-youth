document.addEventListener('DOMContentLoaded', function() {
  // Find all search forms in the header (both desktop and mobile)
  const searchForms = document.querySelectorAll('.wp-block-navigation .wp-block-search');

  // Find any custom search trigger buttons (add class 'search-trigger' to any element in WordPress)
  const customTriggers = document.querySelectorAll('.search-trigger');

  if (searchForms.length === 0 && customTriggers.length === 0) return;

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

  // Use search form from nav if available, otherwise create a basic search form
  let searchClone;
  if (searchForms.length > 0) {
    searchClone = searchForms[0].cloneNode(true);
    const clonedInput = searchClone.querySelector('.wp-block-search__input');
    if (clonedInput) {
      clonedInput.style.display = 'block';
    }
  } else {
    searchClone = document.createElement('form');
    searchClone.setAttribute('role', 'search');
    searchClone.setAttribute('method', 'get');
    searchClone.setAttribute('action', '/');
    searchClone.className = 'wp-block-search';
    searchClone.innerHTML = `
      <div class="wp-block-search__inside-wrapper">
        <input type="search" class="wp-block-search__input" name="s" placeholder="Search..." />
        <button type="submit" class="wp-block-search__button">Search</button>
      </div>
    `;
  }

  // Assemble overlay
  overlayContent.appendChild(closeButton);
  overlayContent.appendChild(searchClone);
  overlay.appendChild(overlayContent);
  document.body.appendChild(overlay);

  // Function to open overlay
  function openOverlay(e) {
    e.preventDefault();
    overlay.classList.add('active');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';

    // Focus on the search input in the overlay
    const overlayInput = overlay.querySelector('.wp-block-search__input');
    if (overlayInput) {
      setTimeout(() => overlayInput.focus(), 100);
    }
  }

  // Attach click handlers to nav search buttons
  searchForms.forEach(function(searchForm) {
    const searchButton = searchForm.querySelector('.wp-block-search__button');
    if (searchButton) {
      searchButton.addEventListener('click', openOverlay);
    }
  });

  // Attach click handlers to any custom .search-trigger elements
  customTriggers.forEach(function(trigger) {
    trigger.addEventListener('click', openOverlay);
  });

  // Close overlay function
  function closeOverlay() {
    overlay.classList.remove('active');
    overlay.setAttribute('aria-hidden', 'true');
    // Only restore scroll if the WP nav overlay is not also open
    if (!document.querySelector('.wp-block-navigation__responsive-container.is-menu-open')) {
      document.body.style.overflow = '';
    }
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
