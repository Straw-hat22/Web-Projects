//set variables
let spans = document.querySelectorAll('.buttons span');
let results = document.querySelector('.results > span');
let input = document.getElementById('the-input');

spans.forEach(span=>{
    span.addEventListener('click', (e)=>{
        if(e.target.classList.contains('check-item')){
            checkItem();
        }
        if(e.target.classList.contains('add-item')){
            addItem();
        }
        if(e.target.classList.contains('delete-item')){
            deleteItem();
        }
        if(e.target.classList.contains('show-items')){
            showItems();
        }
    })
});

function showMsg(){
    results.innerHTML = `Input can't be empty`;
}


function checkItem(){
    if(input.value !== ''){
        if(localStorage.getItem(input.value)){
            results.innerHTML = `Found local Storage Item called <span>${input.value}</span>`;
        }else{
            results.innerHTML = `No Local Storage Item with the Name <span>${input.value}</span>`;
        }
    }else{
        showMsg();
    }
}

function addItem(){
    if(input.value !== ''){
        localStorage.setItem(input.value, 'test');

        results.innerHTML = `Local Storage Item <span>${input.value}</span> Added`;
        input.value = '';
    }else{
        showMsg();
    }
}

function deleteItem(){
    if(input.value !== ''){
        if(localStorage.getItem(input.value)){
            localStorage.removeItem(input.value);
            results.innerHTML = `Local Storage Item called <span>${input.value}</span> Deleted`;
            input.value = '';
        }else{
            results.innerHTML = `No Local Storage Item with the Name <span>${input.value}</span>`;
        }
    }else{
        showMsg();
    }
}

function showItems(){
    if(localStorage.length){
        results.innerHTML = '';
        // console.log(`found elements${localStorage.length}`);
        for(let [key, value] of Object.entries(localStorage)){
            results.innerHTML += `<span class="keys">${key} </span>`;
        }
    }else{
        results.innerHTML = `Local Storage is Empty.`;
    }
}
