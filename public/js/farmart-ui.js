/**
 * Farmart UI: SweetAlert2 defaults, confirmations, flash toasts, submit loading.
 */
(function () {
  'use strict';

  var PRIMARY = '#166534';
  var DANGER = '#b91c1c';

  function escapeHtml(s) {
    if (s == null || s === '') return '';
    var d = document.createElement('div');
    d.textContent = String(s);
    return d.innerHTML;
  }

  function inferIcon(el, message) {
    var set = (el.getAttribute('data-confirm-icon') || '').toLowerCase();
    if (['question', 'warning', 'error', 'info', 'success'].indexOf(set) !== -1) return set;
    var m = (message || '').toLowerCase();
    if (/cannot be undone|permanently|delete this user|delete this product|delete reported|delete this report/.test(m)) return 'warning';
    if (/delete|remove|cancel this order|disable|suspend|reject|unblock/.test(m)) return 'warning';
    if (/log out|sign out/.test(m)) return 'question';
    return 'question';
  }

  function isDangerAction(el, message) {
    if (el.hasAttribute('data-confirm-danger')) return true;
    var m = (message || '').toLowerCase();
    if (/re-enable|keep item|mark as reviewed|mark as resolved|approve/.test(m)) return false;
    return /delete|remove|cancel|disable|suspend|reject|unblock|discard/.test(m);
  }

  function readConfirmAttrs(el) {
    var title = el.getAttribute('data-confirm-title');
    var detail = el.getAttribute('data-confirm-detail');
    var main = el.getAttribute('data-confirm') || (el.dataset && el.dataset.confirmMessage) || '';
    var confirmBtn =
      el.getAttribute('data-confirm-ok') ||
      (isDangerAction(el, main || title) ? 'Yes, continue' : 'Confirm');
    var cancelBtn = el.getAttribute('data-confirm-cancel') || 'Cancel';
    var icon = inferIcon(el, title || main);
    var danger = isDangerAction(el, main || title);
    return { title: title, detail: detail, main: main, confirmBtn: confirmBtn, cancelBtn: cancelBtn, icon: icon, danger: danger };
  }

  function buildConfirmHtml(o) {
    var primaryText = o.main || 'Continue with this action?';
    var parts = [];
    if (o.title) {
      parts.push('<h2 class="text-xl font-semibold text-gray-900 mb-2 text-center">' + escapeHtml(o.title) + '</h2>');
      parts.push('<p class="farmart-swal-body text-gray-600 text-center text-base leading-relaxed">' + escapeHtml(primaryText) + '</p>');
    } else {
      parts.push('<p class="farmart-swal-body text-gray-800 text-center text-lg font-medium leading-snug">' + escapeHtml(primaryText) + '</p>');
    }
    if (o.detail) {
      parts.push('<p class="text-sm text-gray-500 text-center mt-3 leading-relaxed">' + escapeHtml(o.detail) + '</p>');
    }
    return '<div class="farmart-swal-wrap">' + parts.join('') + '</div>';
  }

  function showConfirmDialog(el) {
    var o = readConfirmAttrs(el);
    var primaryText = o.main || 'Continue with this action?';
    if (!window.Swal) {
      var fallback = (o.title ? o.title + '\n\n' : '') + primaryText + (o.detail ? '\n\n' + o.detail : '');
      return Promise.resolve({ isConfirmed: window.confirm(fallback) });
    }
    return Swal.fire({
      html: buildConfirmHtml(o),
      icon: o.icon,
      showCancelButton: true,
      confirmButtonText: o.confirmBtn,
      cancelButtonText: o.cancelBtn,
      reverseButtons: true,
      focusCancel: o.danger,
      confirmButtonColor: o.danger ? DANGER : PRIMARY,
      cancelButtonColor: '#64748b',
      customClass: {
        popup: 'farmart-swal-popup',
        confirmButton: 'farmart-swal-confirm-brand',
        cancelButton: 'farmart-swal-cancel-brand',
        actions: 'farmart-swal-actions-gap',
      },
      buttonsStyling: true,
      showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
      hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' },
    });
  }

  function setFormSubmitting(form, submitting) {
    if (!form) return;
    form.classList.toggle('farmart-form-submitting', !!submitting);
    var label =
      form.getAttribute('data-loading-label') ||
      (form.querySelector('[type="submit"]') && form.querySelector('[type="submit"]').getAttribute('data-loading-label')) ||
      'Processing…';
    form.querySelectorAll('[type="submit"]').forEach(function (btn) {
      btn.disabled = !!submitting;
      if (submitting) {
        if (!btn.dataset._farmartPrevHtml) btn.dataset._farmartPrevHtml = btn.innerHTML;
        var cls = btn.className || '';
        var onGreen =
          /\bbg-primary\b/.test(cls) ||
          /\bbg-green-[56]00\b/.test(cls) ||
          /\bbg-emerald-[56]00\b/.test(cls) ||
          (/\bbg-gradient-to/.test(cls) && /\btext-white\b/.test(cls)) ||
          (/\bbg-red-[56]00\b/.test(cls) && /\btext-white\b/.test(cls));
        var spinClass = onGreen ? 'farmart-inline-spinner farmart-inline-spinner--light' : 'farmart-inline-spinner';
        btn.innerHTML =
          '<span class="' +
          spinClass +
          '" aria-hidden="true"></span><span>' +
          escapeHtml(label) +
          '</span>';
      } else if (btn.dataset._farmartPrevHtml) {
        btn.innerHTML = btn.dataset._farmartPrevHtml;
        delete btn.dataset._farmartPrevHtml;
      }
    });
  }

  function bindConfirmForms() {
    document.querySelectorAll('form[data-confirm], form.swal-confirm-form').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        showConfirmDialog(form).then(function (result) {
          if (result && result.isConfirmed) {
            setFormSubmitting(form, true);
            try {
              HTMLFormElement.prototype.submit.call(form);
            } catch (err) {
              setFormSubmitting(form, false);
              console.error(err);
            }
          }
        });
      });
    });
  }

  /** Submit buttons that carry confirm attrs while the form does not (e.g. legacy logout). */
  function bindConfirmSubmitButtons() {
    document.addEventListener(
      'click',
      function (e) {
        var btn = e.target && e.target.closest && e.target.closest('button[type="submit"]');
        if (!btn) return;
        if (!btn.hasAttribute('data-confirm') && !btn.classList.contains('swal-confirm-form')) return;
        var form = btn.closest('form');
        if (!form) return;
        if (form.matches('form[data-confirm], form.swal-confirm-form')) return;
        e.preventDefault();
        e.stopPropagation();
        showConfirmDialog(btn).then(function (result) {
          if (result && result.isConfirmed) {
            setFormSubmitting(form, true);
            HTMLFormElement.prototype.submit.call(form);
          }
        });
      },
      true
    );
  }

  function bindConfirmClicks() {
    document.querySelectorAll('[data-confirm], .swal-confirm').forEach(function (el) {
      if (el.tagName === 'FORM') return;
      if (el.matches && el.matches('button[type="submit"].swal-confirm-form, button[type="submit"][data-confirm]')) return;

      el.addEventListener('click', function (e) {
        var msg = el.getAttribute('data-confirm') || (el.dataset && el.dataset.confirmMessage) || 'Are you sure?';
        var href = el.getAttribute('href');
        if (href) {
          e.preventDefault();
          showConfirmDialog(el).then(function (result) {
            if (result && result.isConfirmed) window.location = href;
          });
          return;
        }
        var navHref = el.getAttribute('data-nav-href');
        if (navHref && !href) {
          e.preventDefault();
          showConfirmDialog(el).then(function (result) {
            if (result && result.isConfirmed) window.location = navHref;
          });
          return;
        }
        var target = el.getAttribute('data-target-form');
        if (target) {
          e.preventDefault();
          var form = document.querySelector(target);
          if (form) {
            showConfirmDialog(el).then(function (result) {
              if (result && result.isConfirmed) {
                setFormSubmitting(form, true);
                HTMLFormElement.prototype.submit.call(form);
              }
            });
          }
        }
      });
    });
  }

  function bindSubmitLoading() {
    document.querySelectorAll('form[data-submit-loading]').forEach(function (form) {
      if (form.matches('form[data-confirm], form.swal-confirm-form')) return;
      form.addEventListener('submit', function () {
        setFormSubmitting(form, true);
      });
    });
  }

  function processFlashAlerts() {
    var alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(function (el) {
      var cls = el.className || '';
      var icon = 'info';
      var title = '';
      if (/green|success/.test(cls)) {
        icon = 'success';
        title = 'Success';
      } else if (/red|error|danger/.test(cls)) {
        icon = 'error';
        title = 'Something went wrong';
      } else if (/warning|yellow/.test(cls)) {
        icon = 'warning';
        title = 'Warning';
      }

      var html = '';
      var ul = el.querySelector('ul');
      if (ul) {
        html =
          '<ul style="text-align:left;margin:0;padding-left:1.1rem">' +
          Array.from(ul.querySelectorAll('li'))
            .map(function (li) {
              return '<li>' + li.innerHTML + '</li>';
            })
            .join('') +
          '</ul>';
      } else {
        var p = el.querySelector('p');
        html = p ? p.innerHTML : el.innerHTML;
      }

      var textOnly = (function () {
        var p2 = el.querySelector('p');
        if (p2) return p2.innerText.trim();
        return el.innerText.trim();
      })();

      var isList = !!el.querySelector('ul');
      el.remove();

      if (!window.Swal) return;

      try {
        var useToast =
          !isList &&
          textOnly.length > 0 &&
          textOnly.length < 200 &&
          (icon === 'success' || icon === 'error');

        if (useToast) {
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: icon,
            title: textOnly,
            showConfirmButton: false,
            timer: icon === 'error' ? 4200 : 2800,
            timerProgressBar: true,
            customClass: { popup: 'farmart-swal-toast animate__animated animate__fadeInRight animate__faster' },
            hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' },
            color: '#0f172a',
          });
        } else {
          Swal.fire({
            title: title,
            html: html,
            icon: icon,
            showCloseButton: true,
            showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
            hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' },
            timer: icon === 'success' ? 3200 : icon === 'warning' ? 5000 : undefined,
            confirmButtonColor: PRIMARY,
            cancelButtonColor: '#64748b',
            customClass: {
              popup: 'farmart-swal-popup',
              confirmButton: 'farmart-swal-confirm-brand',
            },
          });
        }
      } catch (err) {
        console.error('FarmartUI flash', err);
      }
    });
  }

  function bindRevealOnScroll() {
    var nodes = document.querySelectorAll('.farmart-reveal');
    if (!nodes.length) return;
    if (!('IntersectionObserver' in window)) {
      nodes.forEach(function (n) {
        n.classList.add('farmart-reveal-visible');
      });
      return;
    }
    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (en) {
          if (en.isIntersecting) {
            en.target.classList.add('farmart-reveal-visible');
            io.unobserve(en.target);
          }
        });
      },
      { rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
    );
    nodes.forEach(function (n) {
      io.observe(n);
    });
  }

  function validatePasswordRules(pw) {
    var s = String(pw || '');
    var errors = [];
    if (s.length < 8 || s.length > 15) errors.push('8 to 15 characters');
    if (!/[A-Z]/.test(s)) errors.push('at least 1 uppercase letter');
    if (!/\d/.test(s)) errors.push('at least 1 number');
    if (!/[^A-Za-z0-9]/.test(s)) errors.push('at least 1 special character');
    return { ok: errors.length === 0, errors: errors };
  }

  function showPasswordWarning(errors) {
    if (!window.Swal) {
      alert('Password requirements not met:\n- ' + errors.join('\n- '));
      return;
    }
    Swal.fire({
      icon: 'warning',
      title: 'Password requirements not met',
      html:
        '<div style="text-align:left">' +
        '<p class="text-sm text-gray-600 mb-2">Your password must have:</p>' +
        '<ul style="margin:0;padding-left:1.1rem">' +
        errors.map(function (e) { return '<li>' + escapeHtml(e) + '</li>'; }).join('') +
        '</ul>' +
        '</div>',
      confirmButtonText: 'OK',
      confirmButtonColor: PRIMARY,
      customClass: { popup: 'farmart-swal-popup', confirmButton: 'farmart-swal-confirm-brand' },
    });
  }

   function bindPasswordRequirementPopups() {
     document.querySelectorAll('form').forEach(function (form) {
       // Only target forms that actually collect a new password.
       var pw = form.querySelector('input[name="password"], input[name="new_password"]');
       var confirm = form.querySelector('input[name="confirm_password"]');
       if (!pw || !confirm) return;

       // Add real-time validation for password requirements
       if (pw) {
         pw.addEventListener('input', function () {
           validatePasswordField(pw, form);
         });
       }

       // Add real-time validation for password confirmation
       if (confirm) {
         confirm.addEventListener('input', function () {
           validateConfirmPasswordField(pw, confirm, form);
         });
       }

       form.addEventListener(
         'submit',
         function (e) {
           // Don't interfere with confirmation forms (admin actions etc.)
           if (form.matches('form[data-confirm], form.swal-confirm-form')) return;

           var v = validatePasswordRules(pw.value);
           if (!v.ok) {
             e.preventDefault();
             e.stopPropagation();
             showPasswordWarning(v.errors);
             try { pw.focus(); } catch (_) {}
             return;
           }
           if (pw.value !== confirm.value) {
             e.preventDefault();
             e.stopPropagation();
             if (window.Swal) {
               Swal.fire({
                 icon: 'warning',
                 title: 'Passwords do not match',
                 text: 'Please make sure the passwords are the same.',
                 confirmButtonColor: PRIMARY,
                 customClass: { popup: 'farmart-swal-popup', confirmButton: 'farmart-swal-confirm-brand' },
               });
             } else {
               alert('Passwords do not match.');
             }
             try { confirm.focus(); } catch (_) {}
           }
         },
         true
       );
     });
   }

   function validatePasswordField(input, form) {
     var value = input.value;
     var v = validatePasswordRules(value);
     
     // Update requirement indicators
     updatePasswordRequirements(value, form);
     
     // Show/hide help text based on validity
     var helpText = form.querySelector('#password-help');
     if (helpText) {
       if (v.ok) {
         helpText.classList.remove('text-red-500');
         helpText.classList.add('text-green-500');
         helpText.textContent = 'Password meets all requirements';
       } else {
         helpText.classList.remove('text-green-500');
         helpText.classList.add('text-red-500');
         helpText.textContent = 'Password must be 8-15 characters with at least 1 uppercase letter, 1 number, and 1 special character';
       }
     }
   }

   function validateConfirmPasswordField(passwordInput, confirmInput, form) {
     var passwordValue = passwordInput.value;
     var confirmValue = confirmInput.value;
     
     var matchStatus = form.querySelector('#matchStatus');
     var confirmHelpText = form.querySelector('#confirmPassword-help');
     
     if (passwordValue === confirmValue && passwordValue !== '') {
       if (matchStatus) {
         matchStatus.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mt-1 mr-2 text-green-500"></i> Passwords match';
         lucide.createIcons(); // Refresh icons
       }
       if (confirmHelpText) {
         confirmHelpText.classList.remove('hidden');
         confirmHelpText.classList.add('text-green-500');
         confirmHelpText.textContent = 'Passwords match';
       }
     } else {
       if (matchStatus) {
         matchStatus.innerHTML = passwordValue !== '' && confirmValue !== '' 
           ? '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2 text-red-500"></i> Passwords do not match'
           : '';
         lucide.createIcons(); // Refresh icons
       }
       if (confirmHelpText) {
         confirmHelpText.classList.remove('hidden');
         confirmHelpText.classList.add('text-red-500');
         confirmHelpText.textContent = 'Passwords must match';
       }
     }
   }

   function updatePasswordRequirements(value, form) {
     var lengthReq = form.querySelector('#lengthReq');
     var uppercaseReq = form.querySelector('#uppercaseReq');
     var numberReq = form.querySelector('#numberReq');
     var specialReq = form.querySelector('#specialReq');
     
     if (lengthReq) {
       if (value.length >= 8 && value.length <= 15) {
         lengthReq.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mt-1 mr-2 text-green-500"></i> 8-15 characters';
         lengthReq.classList.remove('text-red-500');
         lengthReq.classList.add('text-green-500');
       } else {
         lengthReq.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2 text-red-500"></i> 8-15 characters';
         lengthReq.classList.remove('text-green-500');
         lengthReq.classList.add('text-red-500');
       }
     }
     
     if (uppercaseReq) {
       if (/[A-Z]/.test(value)) {
         uppercaseReq.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mt-1 mr-2 text-green-500"></i> 1 uppercase letter';
         uppercaseReq.classList.remove('text-red-500');
         uppercaseReq.classList.add('text-green-500');
       } else {
         uppercaseReq.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2 text-red-500"></i> 1 uppercase letter';
         uppercaseReq.classList.remove('text-green-500');
         uppercaseReq.classList.add('text-red-500');
       }
     }
     
     if (numberReq) {
       if (/\d/.test(value)) {
         numberReq.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mt-1 mr-2 text-green-500"></i> 1 number';
         numberReq.classList.remove('text-red-500');
         numberReq.classList.add('text-green-500');
       } else {
         numberReq.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2 text-red-500"></i> 1 number';
         numberReq.classList.remove('text-green-500');
         numberReq.classList.add('text-red-500');
       }
     }
     
     if (specialReq) {
       if (/[^A-Za-z0-9]/.test(value)) {
         specialReq.innerHTML = '<i data-lucide="check-circle" class="w-3 h-3 mt-1 mr-2 text-green-500"></i> 1 special character';
         specialReq.classList.remove('text-red-500');
         specialReq.classList.add('text-green-500');
       } else {
         specialReq.innerHTML = '<i data-lucide="x-circle" class="w-3 h-3 mt-1 mr-2 text-red-500"></i> 1 special character';
         specialReq.classList.remove('text-green-500');
         specialReq.classList.add('text-red-500');
       }
     }
     
     // Refresh icons after updating
     if (typeof lucide !== 'undefined') {
       lucide.createIcons();
     }
   }

  function applySwalMixin() {
    if (!window.Swal) return;
    Swal.mixin({
      confirmButtonColor: PRIMARY,
      cancelButtonColor: '#64748b',
      reverseButtons: true,
      customClass: {
        popup: 'farmart-swal-popup',
        confirmButton: 'farmart-swal-confirm-brand',
        cancelButton: 'farmart-swal-cancel-brand',
        actions: 'farmart-swal-actions-gap',
      },
      buttonsStyling: true,
    });
  }

  function onDomReady(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  function init() {
    applySwalMixin();
    onDomReady(function () {
      processFlashAlerts();
      bindConfirmForms();
      bindConfirmSubmitButtons();
      bindConfirmClicks();
      bindSubmitLoading();
      bindRevealOnScroll();
      bindPasswordRequirementPopups();
    });
  }

  window.FarmartUI = {
    init: init,
    showConfirm: showConfirmDialog,
    setFormSubmitting: setFormSubmitting,
  };

  init();
})();
