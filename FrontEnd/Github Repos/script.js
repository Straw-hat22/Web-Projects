//main variables
var input = document.querySelector('.get-repos input');
var getButton = document.querySelector('.get-repos span');
let reposData = document.querySelector('.show-data');

getButton.onclick = function(){
    getRepos();
}

//get Repos function
function getRepos(){
    if(input.value == ''){//if value is empty
        reposData.innerHTML = '<span>Please write Github Username.</span>';
    }else{
        fetch(`https://api.github.com/users/${input.value}/repos`)
        .then((res)=>res.json())
        .then((repos)=>{
            reposData.innerHTML = '';

            //loop on repos
            repos.forEach(repo => {
                
                //craete main div element
                let mainDiv = document.createElement('div');

                //create repo name text
                let repoName = document.createTextNode(repo.name);

                //append the text ot the main div
                mainDiv.appendChild(repoName);

                //create repo url 
                let theUrl = document.createElement('a');

                //create url text
                let UrlText = document.createTextNode('visit');

                //append the Url text
                theUrl.appendChild(UrlText);

                // add the hypertext reference 'href'
                theUrl.href = `https://github.com/${input.value}/${repo.name}`;

                //set attribute blank
                theUrl.setAttribute('target', '_blank');

                //append url to main div
                mainDiv.appendChild(theUrl);

                //create stars count span
                let starsSpan = document.createElement('span');

                //create the star count text
                let starsText = document.createTextNode(`Stars: ${repo.stargazers_count}`);

                //add stars count text to stars span
                starsSpan.appendChild(starsText);

                //add stars count span to main div
                mainDiv.appendChild(starsSpan);
                //add class name on main div
                mainDiv.className = 'repo-box';
                
                //append the main div to container
                reposData.appendChild(mainDiv);
            });
        });
    }
}