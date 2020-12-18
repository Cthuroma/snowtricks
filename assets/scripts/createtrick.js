// Get the ul that holds the collection of tags
var imagesCollectionHolder = document.getElementById('imageul')
// count the current form inputs we have (e.g. 2), use that as the new
// index when inserting a new item (e.g. 2)

var buttons = document.getElementsByClassName('addform');
for (let button of buttons) {
  button.onclick =  function() {
    console.log('a');
    addFormToCollection(document.getElementById(button.dataset.formul));
  }
}

function addFormToCollection($collectionHolder) {
  // Get the data-prototype explained earlier
  var prototype = $collectionHolder.dataset.prototype;

  // get the new index
  var index = $collectionHolder.dataset.index;

  var newForm = document.createElement('li');

  prototype = prototype.replace(/__name__/g, index);

  newForm.innerHTML = prototype;
  // You need this only if you didn't set 'label' => false in your tags field in TaskType
  // Replace '__name__label__' in the prototype's HTML to
  // instead be a number based on how many items we have
  // newForm = newForm.replace(/__name__label__/g, index);

  // Replace '__name__' in the prototype's HTML to
  // instead be a number based on how many items we have

  // increase the index with one for the next item
  $collectionHolder.dataset.index = index+1;

  // Add the new form at the end of the list
  $collectionHolder.append(newForm);
}
