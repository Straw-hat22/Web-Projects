// Get Slider Items Count
let sliderImgs = Array.from(document.querySelectorAll('.slider-container img'));
let slidesCount = sliderImgs.length;

// set current slide
let currentSlide = 1;

// slide number element 
let slideNumberElement = document.getElementById('slide-number');

// previous and next buttons
let prevButton = document.getElementById('prev');
let nextButton = document.getElementById('next');

// handle click on Previous and next buttons
nextButton.onclick = nextSlide;
prevButton.onclick = prevSlide;


// Creat The Main UL Element
let paginationElement = document.createElement('ul');
paginationElement.setAttribute('id', 'pagination-ul');

// creating the li elements based on slides count
for(let i = 1; i <= slidesCount; i++){
    
    // create the LI
    let paginationItem = document.createElement('li');
    paginationItem.setAttribute('data-index', i);

    //set item content
    paginationItem.appendChild(document.createTextNode(i));

    // append items to the main UL list
    paginationElement.appendChild(paginationItem);
}

// Append the UL element which has all the LIs to the page
document.getElementById('indicators').appendChild(paginationElement);

// get the new created ul
let paginationCreatedUl = document.getElementById('pagination-ul');

// get pagination items
let paginationBullets = Array.from(document.querySelectorAll('#pagination-ul li'));

// loop through all bullets items
for(let i = 0;i < paginationBullets.length; i++){
    paginationBullets[i].onclick = function(){
        currentSlide = parseInt(this.getAttribute('data-index'));
        theChecker();
    }
}

// trigger the checker function
theChecker();

// next slide function
function nextSlide(){
    if(nextButton.classList.contains('disabled')){
        return false;
    }else{
        currentSlide++;
        theChecker();
    }
}

// prev slide function
function prevSlide(){
    if(prevButton.classList.contains('disabled')){
        return false;
    }else{
        currentSlide--;
        theChecker();
    }
}


// create the checker function
function theChecker(){
    // set the slide number
    slideNumberElement.textContent = 'Slide #' + (currentSlide) + ' of ' + (slidesCount);
    
    removeAllActive();
    

    // set Active Class no Current slide
    sliderImgs[currentSlide - 1].classList.add('active');

    // set active class on current pagination item
    paginationCreatedUl.children[currentSlide - 1].classList.add('active');
    
    // disable and enable the next and prev buttons
    if(currentSlide == 1) {
        // add the disabled class on previous button
        prevButton.classList.add('disabled');
    } else if (currentSlide == slidesCount){
        nextButton.classList.add('disabled');
    } else {
        prevButton.classList.remove('disabled');
        nextButton.classList.remove('disabled');
    }
}


// Remove all active class from imgs and pagination bullets
function removeAllActive(){
    
    // loop through imgs 
    sliderImgs.forEach(function (img){
        img.classList.remove('active');
    });

    // loop through pagination bullets
    paginationBullets.forEach(function (bullet) {
        bullet.classList.remove('active');
    });
}