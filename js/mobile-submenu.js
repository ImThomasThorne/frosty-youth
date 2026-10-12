document.addEventListener('DOMContentLoaded', function () {
  var nav = document.querySelector('.mobile-menu');
  if (!nav) return;

  var items = nav.querySelectorAll('li.mobile-submenu');

  items.forEach(function (item) {
    var submenu = item.querySelector(':scope > ul.mobile-submenu');
    if (!submenu) return;

    // WordPress already renders a real toggle button for "open on click / hover-click"
    // submenus (with its own aria-expanded handling) - our CSS hooks into that directly.
    if (item.querySelector(':scope > button.wp-block-navigation-submenu__toggle')) return;

    // Otherwise (submenu set to "open always") WordPress renders no toggle at all, so add one.
    var link = item.querySelector(':scope > a');
    if (!link) return;

    var toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'mobile-submenu-toggle';
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-label', 'Show submenu');
    toggle.innerHTML = '<span class="mobile-submenu-arrow" aria-hidden="true"></span>';

    link.insertAdjacentElement('afterend', toggle);

    toggle.addEventListener('click', function () {
      var isOpen = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
      submenu.style.maxHeight = isOpen ? '' : submenu.scrollHeight + 'px';
    });
  });

  // Close an open toggle (native or custom) by simulating a real click, so WordPress's
  // own toggle buttons go through their normal handler instead of just having their
  // aria-expanded attribute overwritten (which could desync from its internal state).
  function closeIfOpen(button) {
    if (button.getAttribute('aria-expanded') === 'true') {
      button.click();
    }
  }

  function closeAllExcept(exception) {
    nav.querySelectorAll('li.mobile-submenu > button[aria-expanded="true"]').forEach(function (button) {
      if (button !== exception) closeIfOpen(button);
    });
  }

  var observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      var target = mutation.target;

      // Only one submenu open at a time.
      if (mutation.attributeName === 'aria-expanded' && target.getAttribute('aria-expanded') === 'true') {
        closeAllExcept(target);
      }

      // Reset every submenu when the overall mobile menu closes.
      if (
        mutation.attributeName === 'class' &&
        target.classList.contains('wp-block-navigation__responsive-container') &&
        !target.classList.contains('is-menu-open')
      ) {
        closeAllExcept(null);
      }
    });
  });

  observer.observe(nav, {
    attributes: true,
    attributeFilter: ['aria-expanded', 'class'],
    subtree: true,
  });
});
