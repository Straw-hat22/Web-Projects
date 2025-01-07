let form = document.getElementById("item-form");
let itemList = document.getElementById("item-list");
var filter = document.getElementById("search-box-text");

// form submit event
form.addEventListener('submit', addItem);

// delete event
itemList.addEventListener('click', removeItem);

// filter event 
filter.addEventListener('keyup', filterItems);

// add item
function addItem (e) {
    e.preventDefault();
    
    // get input value
    let newItem = document.getElementById("item").value;

    // creat new li element 
    let li = document.createElement("li");
    li.className = "list-group-item";
    
    // Add text node with input value
    li.appendChild(document.createTextNode(newItem));
    
    //create delete button element
    let button = document.createElement('button');
    button.className = "delete";
    //append text node 
    button.appendChild(document.createTextNode('X'));

    // append button to li
    li.appendChild(button);

    // append li to list
    itemList.appendChild(li);
}


function removeItem (e) {
    if(e.target.classList.contains('delete')){
        if(confirm("Are u Sure?")){
            let li = e.target.parentElement;
            itemList.removeChild(li);
        }
    }

}


//Filter items
function filterItems (e){
    var text = e.target.value.toLowerCase();
    var items = itemList.getElementsByTagName('li');
    // convert to an array
    Array.from(items).forEach(function(item){
        var itemName = item.firstChild.textContent;
        if(itemName.toLowerCase().indexOf(text) != -1){
            item.style.display = 'block';
        }else{
            item.style.display = 'none';
        }
    })
}