window.addEventListener('DOMContentLoaded', function() {
  let mobile_nav  = document.getElementById('mobilenav'),
      mainmenu    = document.querySelector('nav.mainmenu'),
      has_submenu = document.querySelectorAll('.has-submenu')

  // toggle mobile nav open/closed
  mobile_nav.addEventListener('click', function(e) {
    e.preventDefault()
    e.stopPropagation()

    let computed_display = window.getComputedStyle(mainmenu).display,
        inline_display   = mainmenu.style.display

    if(computed_display === 'none' || inline_display === 'none') {
      mainmenu.style.display = 'block'
    } else {
      mainmenu.style.display = 'none'
    }
  })

  // toggle submenus open/closed
  for(let i = 0, lgth = has_submenu.length; i < lgth; i++) {
    has_submenu[i].addEventListener('click', function(e) {
      let icon = has_submenu[i].querySelector('a:first-child>span.fa')

      e.preventDefault()
      e.stopPropagation()

      has_submenu[i].classList.toggle('is-open')
      icon.classList.toggle('fa-angle-right')
      icon.classList.toggle('fa-angle-down')
    })
  }
})
