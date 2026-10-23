document.addEventListener('DOMContentLoaded', function () {
  var emailInput = document.getElementById('mce-EMAIL');
  if (emailInput && !emailInput.placeholder) {
    emailInput.placeholder = 'example@email.com';
  }
});
