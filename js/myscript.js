$('.delete-post').on('click', function () {
  if (confirm('Are you sure?')) {
    return true;
  } else {
    return false;
  }
})

function cutLongString() {
  var articles;
  if (articles = document.querySelectorAll('.article')) {

    Array.from(articles).forEach(a => {

      if (window.screen.availWidth >= 1536) {

        if (a.textContent.length > 100) {
          a.textContent = a.textContent.slice(0, 99) + '...';
        }
      } else if (window.screen.availWidth < 1536 && window.screen.availWidth > 1400) {

        if (a.textContent.length > 70) {
          a.textContent = a.textContent.slice(0, 69) + '...';
        }
      } else {
        a.textContent = a.textContent.slice(0, 49) + '...';
      }

    })
  }
}
cutLongString();
console.log(window.screen.availWidth);
console.log(window.screen.availHeight);


toastr.options = {
  "closeButton": true,
  "positionClass": "toast-bottom-center",
}