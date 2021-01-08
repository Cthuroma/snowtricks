let buttons = document.getElementsByClassName('addform');
for (let button of buttons) {
  button.onclick =  function() {
    addFormToCollection(document.getElementById(button.dataset.formul));
  }
}

function addFormToCollection($collectionHolder) {

  let prototype = $collectionHolder.dataset.prototype;
  let index = $collectionHolder.dataset.index;
  let newForm = document.createElement('li');

  prototype = prototype.replace(/__name__/g, index);

  newForm.innerHTML = prototype;
  $collectionHolder.dataset.index = index+1;

  $collectionHolder.append(newForm);

  $('.custom-file-input:not(.custom-file-input-hack)').on('change', function (event) {
      $(this).addClass('custom-file-input-hack');
      let inputFile = event.currentTarget;
      $(inputFile).parent()
      .find('.custom-file-label')
      .html(inputFile.files[0].name);
  });
}
