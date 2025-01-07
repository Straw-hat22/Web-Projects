var formEmp = document.getElementById('form');
var inputName = document.getElementById('name');
var inputEmail = document.getElementById('email');
var inputMobile = document.getElementById('mobile');

const table = document.querySelector('#table tbody');
var contEdit = document.getElementById('content-edit');// to set the button value and the action
var submit = document.getElementById('submit-button');


class Employee {
    constructor(id, name, email, mobile){
        this.id = id;
        this.name = name;
        this.email = email;
        this.mobile = mobile;
    }
    showData(){
        Employee.showHtml(this.id, this.name, this.email, this.mobile);
        return this;// to help using the chain .showData().storeData();
    }

    storeData(){
        const allData = JSON.parse(localStorage.getItem('employee')) || [];// if the local storage is empty asigne it to empty
        allData.push({id:this.id, name:this.name, email:this.email, mobile:this.mobile}); //push the elements to the array
        localStorage.setItem("employee", JSON.stringify(allData));//transfer allData from jscode to a string and save it in the local storage
    }
    // static to use it individually 
    static showAllEmps(){
        if(localStorage.getItem('employee')){
            JSON.parse(localStorage.getItem('employee')).forEach(item => {
                Employee.showHtml(item.id, item.name, item.email, item.mobile); 
            });// if the employee has any data show it in the table 
        }
    }

    updateEmp(id){
        const newItem = {id:id, name:this.name, email:this.email, mobile:this.mobile};
        const UpdatedData = JSON.parse(localStorage.getItem('employee')).map((item)=>{
            if(item.id == id){// if the id matches change it with new item
                return newItem;
            }
            return item;// if now return the original unchanged
        })
        localStorage.setItem('employee', JSON.stringify(UpdatedData));
    }

    static showHtml(id, name, email, mobile){
        const tr = document.createElement('tr');
        tr.innerHTML = `
        <tr>
            <td>${name}</td>
            <td>${email}</td>
            <td>${mobile}</td>
            <td>
                <button class='edit' data-id="${id}">Edit</button>
                <button class='delete' data-id="${id}">Delete</button>
            </td>
        </tr>`;
        table.appendChild(tr);
    }

}

Employee.showAllEmps();

formEmp.addEventListener('submit', (e)=>{
    e.preventDefault();
    if(!contEdit.value){//contEdit is a var that have an id if it don't have one then it's a new employee
        let id = Math.floor(Math.random() * 100000);
        let emp =new Employee(id, inputName.value, inputEmail.value, inputMobile.value);
        emp.showData().storeData();
        inputName.value = '';
        inputEmail.value = '';
        inputMobile.value = '';
    }else{
        const id =+ contEdit.value;
        const newEmp = new Employee(id, inputName.value, inputEmail.value, inputMobile.value);
        newEmp.updateEmp(id);
        submit.value = 'Store this Data';
        table.innerHTML = '';
        Employee.showAllEmps();
        inputName.value = '';
        inputEmail.value = '';
        inputMobile.value = '';
        contEdit.value = '';
    }
    
});

table.addEventListener('click',(e)=>{
    if(e.target.classList.contains('delete')){

        const id =+ e.target.getAttribute("data-id");
        const emps = JSON.parse(localStorage.getItem("employee"));
        const newData = emps.filter(item=>item.id!==id);
        localStorage.setItem("employee",JSON.stringify(newData));

        e.target.parentElement.parentElement.remove();
    }

    if(e.target.classList.contains('edit')){

        const id =+ e.target.getAttribute("data-id");
        const item = JSON.parse(localStorage.getItem("employee")).find(item => item.id === +id); // Corrected to convert id to number
            inputName.value = item.name;
            inputEmail.value = item.email;
            inputMobile.value = item.mobile;
            contEdit.value = id;
            submit.value = 'Update information';
    }

})