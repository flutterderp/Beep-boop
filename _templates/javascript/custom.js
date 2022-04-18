window.addEventListener('DOMContentLoaded', function() {
  // Cycle through anchor links and attach click listeners
  document.addEventListener('click', clickListener);
  document.addEventListener('touch', clickListener);

  function clickListener(event) {
    const href = event.target.getAttribute('href');

    if(href !== null) {
      let hash_split = href.split('#'),
      target     = document.querySelector('[id="'+hash_split[1]+'"]'),
      coords     = null;


      if((hash_split.length === 2 && hash_split[0].length === 0) && (target !== null && typeof target === 'object')) {
        try {
          coords = target.getBoundingClientRect();
        } catch (e) {
          // Scroll to top if we can't find a target to scroll to
          coords = document.body.getBoundingClientRect();
          // console.warn('No anchor available to scroll to or something hecked up.\r\n'+e);
        }

        // Scroll the window to the anchor
        event.preventDefault();
        window.scrollTo({left: coords['x'], top: coords['y'], behavior: 'smooth'});
      }
    }
  }

  const pushybtn = document.getElementById('pushy-menu-btn')

  if(pushybtn && typeof pushybtn !== 'undefined') {
    pushybtn.addEventListener('click', function() {
      let pushy    = document.querySelector('.pushy'),
          computed = window.getComputedStyle(pushy).visibility

      if(typeof pushy == 'object' && computed == 'hidden') {
        pushy.style.visibility = 'visible'
      }
    })
  }

  if(typeof jQuery.fn.foundation !== 'undefined' && jQuery.isFunction(jQuery.fn.foundation)) {
    jQuery(document).foundation();
  }

  if(typeof lightcase !== 'undefined' && jQuery.isFunction(jQuery.fn.lightcase)) {
    // Lightbox
    jQuery('a[data-rel^=lightcase]').lightcase();
  }

  if(typeof flatpickr !== 'undefined') {
    dateboxes = document.querySelectorAll('.datepicker');

    for(i = 0, dlen = dateboxes.length; i < dlen; i++) {
      flatpickr(dateboxes[i], { allowInput: true, altFormat: 'M j, Y \@ h:iK', altInput: true, dateFormat: 'Y-m-d H:i:00', enableTime: true });
    }
  }

  // Height matching script
  if(typeof rightHeight !== 'undefined') {
    rightHeight.init();
  }
})

// Function to re-adjust the heights of copy and sidebar
function adjustCopy() {
  if(typeof rightHeight !== 'undefined') {
    let container = document.querySelector('.copyarea > div.row');

    setTimeout(function() {
      rightHeight.adjustContainerHeight(container);
      console.log(rightHeight);
    }, 1000);
  }
}
