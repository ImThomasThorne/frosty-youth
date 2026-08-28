document.addEventListener('DOMContentLoaded', function () {
  var items = document.querySelectorAll('.mobile-menu li.mobile-submenu');

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
});
