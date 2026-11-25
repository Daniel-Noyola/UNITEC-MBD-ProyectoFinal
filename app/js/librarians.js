(function () {
  'use strict';

  function openModal(modalId) {
    var modal = document.getElementById(modalId);
    if (!modal) {
      return;
    }
    modal.hidden = false;
    var firstInput = modal.querySelector('input:not([type="hidden"])');
    if (firstInput) {
      firstInput.focus();
    }
  }

  function closeModal(modalId) {
    var modal = document.getElementById(modalId);
    if (modal) {
      modal.hidden = true;
    }
  }

  var openCreateBtn = document.getElementById('open-create-modal');
  if (openCreateBtn) {
    openCreateBtn.addEventListener('click', function () {
      openModal('create-librarian-modal');
    });
  }

  document.querySelectorAll('.js-edit-librarian').forEach(function (button) {
    button.addEventListener('click', function () {
      var idInput = document.getElementById('edit-id');
      var empInput = document.getElementById('edit-emp');
      var nameInput = document.getElementById('edit-name');
      var emailInput = document.getElementById('edit-email');
      var pwdInput = document.getElementById('edit-password');
      var pwdConfirmInput = document.getElementById('edit-password-confirm');

      if (idInput) idInput.value = button.getAttribute('data-id') || '';
      if (empInput) empInput.value = button.getAttribute('data-emp') || '';
      if (nameInput) nameInput.value = button.getAttribute('data-name') || '';
      if (emailInput) emailInput.value = button.getAttribute('data-email') || '';
      if (pwdInput) pwdInput.value = '';
      if (pwdConfirmInput) pwdConfirmInput.value = '';

      openModal('edit-librarian-modal');
    });
  });

  document.querySelectorAll('[data-close-modal]').forEach(function (button) {
    button.addEventListener('click', function () {
      closeModal(button.getAttribute('data-close-modal'));
    });
  });

  document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
    backdrop.addEventListener('click', function (event) {
      if (event.target === backdrop) {
        backdrop.hidden = true;
      }
    });
  });

  document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape') {
      return;
    }
    document.querySelectorAll('.modal-backdrop:not([hidden])').forEach(function (backdrop) {
      backdrop.hidden = true;
    });
  });

  document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
    button.addEventListener('click', function () {
      var targetId = button.getAttribute('data-toggle-password');
      var input = targetId ? document.getElementById(targetId) : null;
      if (!input) {
        return;
      }
      var isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      var icon = button.querySelector('.material-symbols-outlined');
      if (icon) {
        icon.textContent = isHidden ? 'visibility_off' : 'visibility';
      }
    });
  });
})();
