document.addEventListener('DOMContentLoaded', function () {
    let loggedInUser = sessionStorage.getItem('loggedInUser');
    if(loggedInUser){

        let theInput = document.querySelector('.add-task input');
        let addButton = document.querySelector('.plus');
        let tasksContainer = document.querySelector('.tasks-content');
        let tasksCount = document.querySelector('.tasks-count span');
        let tasksCompleted = document.querySelector('.tasks-completed span');
        let edit = document.querySelector('.tasks-edit');
        let noTasksMsg = document.querySelector('.no-tasks-message');
        let userData = JSON.parse(localStorage.getItem(loggedInUser));

        window.onload = function () {
            theInput.focus();
        };

        loadData();

        function loadData(){
            if(userData.notes.length > 0){
                userData.notes.forEach(note => {
                    if(document.body.contains(noTasksMsg)){
                        noTasksMsg.remove();
                    }
                    createTask(note.id, note.text, note.isCompleted);
                });
                editTasks();
                calcTasks();
            }else{
                let noTasksMsg = document.querySelector('.no-tasks-message');
                if(!document.body.contains(noTasksMsg)){
                    createNoTasks();
                }
            }
        }
        
        addButton.onclick = function() {
            if (theInput.value == '') {
                Swal.fire({
                    title: 'Hey',
                    text: 'The Note Is Empty!',
                    icon: 'info',
                    confirmButtonText: 'Cool'
                });
            } else {
                let noTasksMsg = document.querySelector('.no-tasks-message');
                if (document.body.contains(noTasksMsg)) {
                    noTasksMsg.remove();
                }
                const noteId = Date.now();
                createTask(noteId, theInput.value.trim(), false); // New task is not completed by default
        
                // Save to local storage
                userData.notes.push({id: noteId, text: theInput.value.trim(), isCompleted: false });
                localStorage.setItem(loggedInUser, JSON.stringify(userData));
        
                theInput.value = '';
                editTasks();
                calcTasks();
            }
        };

        document.addEventListener('click', function(e){
            // delete a single task
            deletTask(e);

            // finish a single task
            finishTask(e);

            // delete all tasks
            deleteAll(e);

            // finish all tasks
            finishAll(e);
        });

        function finishAll(e){
            if(e.target.className === 'finish-all'){
                document.querySelectorAll('.tasks-content .task-box').forEach(taskElement => {
                    let noteId = taskElement.getAttribute('data-id');
                    let note = userData.notes.find(note => note.id === parseInt(noteId, 10));
                    if(note && !note.isCompleted){
                        note.isCompleted = true;
                        taskElement.classList.add('finished');
                    }
                });
                localStorage.setItem(loggedInUser, JSON.stringify(userData));
                calcTasks();
            }
        }

        function deleteAll(e){
            if(e.target.className === 'delete-all'){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            'Deleted!',
                            'All tasks have been deleted.',
                            'success'
                        );
                        tasksContainer.innerHTML = '';
                        userData.notes = [];
                        localStorage.setItem(loggedInUser, JSON.stringify(userData));
                        createNoTasks();
                        clearEditButtons();
                        calcTasks();
                    }
                });
                
            }
        }

        function finishTask(e){
            if(e.target.classList.contains('task-box')){
                // toggle class 'finished'
                let noteId = e.target.getAttribute('data-id');
                let note = userData.notes.find(note => note.id === parseInt(noteId, 10));
                if(note){
                    e.target.classList.toggle('finished');
                    note.isCompleted = e.target.classList.contains('finished');
                    localStorage.setItem(loggedInUser, JSON.stringify(userData));
                }
                calcTasks();
            }
        }

        function deletTask (e){
            //delete task
            if(e.target.className === 'delete'){
                let taskElement = e.target.parentNode;
                let noteId = taskElement.getAttribute('data-id');

                // remove the note from local storage
                userData.notes = userData.notes.filter(note=> note.id !== parseInt(noteId, 10));
                localStorage.setItem(loggedInUser, JSON.stringify(userData));
                taskElement.remove();
                calcTasks();
                if (document.querySelectorAll('.tasks-content .task-box').length === 0) {
                    createNoTasks();
                    clearEditButtons();
                }
            }
        }

        function createTask(id, text, isCompleted) {
            let mainSpan = document.createElement('span');
            let deleteButton = document.createElement('span');
            let textNode = document.createTextNode(text);
            let deleteText = document.createTextNode('Delete');
        
            mainSpan.appendChild(textNode);
            mainSpan.className = 'task-box';
            mainSpan.setAttribute('data-id', id);
            if (isCompleted) {
                mainSpan.classList.add('finished'); // Apply style for completed tasks
            }
        
            deleteButton.appendChild(deleteText);
            deleteButton.className = 'delete';
        
            mainSpan.appendChild(deleteButton);
            tasksContainer.appendChild(mainSpan);
        }

        function createNoTasks() {
            // Create Message Span Element
            let msgSpan = document.createElement("span");

            // Create The Text Message
            let msgText = document.createTextNode("No Tasks To Show");

            // Add Text To Message Span Element
            msgSpan.appendChild(msgText);

            // Add Class To Message Span
            msgSpan.className = 'no-tasks-message';

            // Append The Message Span Element To The Task Container
            tasksContainer.appendChild(msgSpan);
        }

        function editTasks(){
            if(document.querySelector('.delete-all') || document.querySelector('.finish-all')){
                return;
            }

            // create the delete all button
            let deleteAll = document.createElement('span');
            let deleteText = document.createTextNode('Delete All Tasks');

            deleteAll.appendChild(deleteText);
            deleteAll.className = 'delete-all';
            edit.appendChild(deleteAll);


            // create the finish all button
            let finishAll = document.createElement('span');
            let finishText = document.createTextNode('Finish All Tasks');

            finishAll.appendChild(finishText);
            finishAll.className = 'finish-all';

            edit.appendChild(finishAll);
        }

        function calcTasks(){
            tasksCount.innerHTML = document.querySelectorAll('.tasks-content .task-box').length;
            tasksCompleted.innerHTML = document.querySelectorAll('.tasks-content .finished').length;
        }

        function clearEditButtons (){
            document.querySelectorAll('.delete-all, .finish-all').forEach(e=>{
                e.remove();
            });
        }
        const logoutBtn = document.querySelector('.logout-btn');
        logoutBtn.addEventListener('click', function() {
            console.log('hello');
            sessionStorage.removeItem('loggedInUser');  // Clear session data
            window.location.href = '../sign-Up.html';    // Redirect to sign-up or login page
        });
        
    }else{
        window.location.href='../sign-Up.html';
    }
    


});