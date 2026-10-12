document.addEventListener('DOMContentLoaded', function () {
  var overrideCss = '.givewp-donation-form__steps { max-width: none !important; }';

  function inject(iframe) {
    try {
      var doc = iframe.contentDocument;
      if (!doc || !doc.head || doc.getElementById('frost-givewp-form-width-override')) return;

      var style = doc.createElement('style');
      style.id = 'frost-givewp-form-width-override';
      style.textContent = overrideCss;
      doc.head.appendChild(style);
    } catch (e) {
      // Cross-origin iframe - nothing we can do from here.
    }
  }

  document.querySelectorAll('iframe[src*="givewp-route=donation-form-view"]').forEach(function (iframe) {
    inject(iframe);
    iframe.addEventListener('load', function () {
      inject(iframe);
    });
  });
});
