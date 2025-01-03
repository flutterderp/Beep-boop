window.addEventListener('load', function() {
  // Set translations
  Jooa11y.Lang.addI18n(Jooa11yLangEn.strings);

  // Instantiate
  const checker = new Jooa11y.Jooa11y(Jooa11yLangEn.options);
  checker.doInitialCheck();
  // const sa11y = new Sa11y();
});

window.addEventListener('DOMContentLoaded', function() {
  let mobile_nav  = document.getElementById('mobilenav'),
      mainmenu    = document.querySelector('nav.mainmenu'),
      has_submenu = document.querySelectorAll('.has-submenu'),
      closables   = document.querySelectorAll('.callout[data-closable]');

  // Cycle through anchor links and attach click listeners
  document.addEventListener('click', clickListener);
  document.addEventListener('touch', clickListener);

  // toggle mobile nav open/closed
  mobile_nav.addEventListener('click', function(e) {
    e.preventDefault();
    e.stopPropagation();

    let computed_display = window.getComputedStyle(mainmenu).display,
        inline_display   = mainmenu.style.display;

    if(computed_display === 'none' || inline_display === 'none') {
      mainmenu.style.display = 'block';
    } else {
      mainmenu.style.display = 'none';
    }
  });

  // toggle submenus open/closed
  for(let i = 0, lgth = has_submenu.length; i < lgth; i++) {
    has_submenu[i].addEventListener('click', function(e) {
      let icon   = has_submenu[i].querySelector('a:first-child>span.fa'),
          parent = e.target.parentElement;

      if(parent.classList.contains('has-submenu')) {
        e.preventDefault();
        e.stopPropagation();

        has_submenu[i].classList.toggle('is-open');
        icon.classList.toggle('fa-angle-right');
        icon.classList.toggle('fa-angle-down');
      }
    });
  }

  if(closables) {
    closables.forEach(function(v, k) {
      btn = v.querySelector('button[data-close]');

      btn.addEventListener('click', function(e) {
        e.preventDefault;
        e.stopPropagation;

        v.remove();
      });
    });
  }

  if(typeof GLightbox !== 'undefined') {
    const lightbox = GLightbox({
      autoplayVideos: false,
      loop: true,
      touchNavigation: true,
    });
  }

  function clickListener(event) {
    const href = event.target.getAttribute('href');

    if(href !== null) {
      let hash_split    = href.split('#'),
          target        = document.querySelector('[id="'+hash_split[1]+'"]'),
          target_legacy = document.querySelector('[name="'+hash_split[1]+'"]'),
          coords        = null,
          target_valid  = false;

      switch(true) {
        case (target !== null && typeof target === 'object') :
          target_valid = true;
          break;

        case (target_legacy !== null && typeof target_legacy === 'object') :
          target = target_legacy;
          target_valid = true;
          break;

        default :
          break;
      }

      // Attempt to parse the link as a URL and determine if it is a local link
      try {
        url = new URL(hash_split[0]);

        if (url.host === window.location.host) {
          localLink = true;
        } else {
          localLink = false;
        }
      } catch (err) {
        url = false;

        if (hash_split[0].length === 0) {
          localLink = true;
        }
      }

      if((hash_split.length === 2 && localLink === true) && target_valid === true) {
        try {
          coords = target.getBoundingClientRect();

          event.preventDefault();
          target.scrollIntoView({behavior: 'smooth'});
        } catch(e) {
          // Scroll to top if we can't find a target to scroll to
          coords = document.body.getBoundingClientRect();
          // console.warn('No anchor available to scroll to or something hecked up.\r\n'+e);

          event.preventDefault();
          window.scrollTo({left: coords['x'], top: coords['y'], behavior: 'smooth'});
        }

        // Scroll the window to the anchor
        // event.preventDefault();
        // window.scrollTo({left: coords['x'], top: coords['y'], behavior: 'smooth'});
      }
    }
  }
});
