let id = 1;
document.addEventListener('DOMContentLoaded', function(){
    
    // the iputs
    let title = document.getElementById('title');
    let price = document.getElementById('price');
    let taxes = document.getElementById('taxes');
    let ads = document.getElementById('ads');
    let discount = document.getElementById('discount');
    let count = document.getElementById('count');
    let category = document.getElementById('category');
    let create = document.getElementById('submit');
    
    
    
    
    class Items {
        constructor(id, title, price, taxes, ads, discount, total, count, category){
            this.id = id;
            this.title = title;
            this.price = price;
            this.taxes = taxes;
            this.ads = ads;
            this.discount = discount;
            this.total = total;
            this.count = count;
            this.category = category;
        }
    
        addItemToStorage(id, title, price, taxes, ads, discount, total, count, category){
            let items = JSON.parse(localStorage.getItem('items')) || [];
            items.push({id:id, title:title, price:price, taxes:taxes, ads:ads, discount:discount, total:total, count:count, category:category});
            localStorage.setItem('items', JSON.stringify(items));
        }
    
        addItemToTable(id, title, price, taxes, ads, discount, total, count, category){
            let tableBody = document.querySelector('table');
            let trow = document.createElement('tr');
            trow.innerHTML = `
            <td>${id}</td>
            <td>${title}</td>
            <td>${price}</td>
            <td>${taxes}</td>
            <td>${ads}</td>
            <td>${discount}</td>
            <td>${total}</td>
            <td>${category}</td>
            <td><button id="update">Update</button></td>
            <td><button id="delete">Delete</button></td>
            `;
            tableBody.appendChild(trow);
        }

        static loadItemsFromStorage() {
            let items = JSON.parse(localStorage.getItem('items')) || [];
            items.forEach(item => {
                let tableBody = document.querySelector('table');
                let trow = document.createElement('tr');
                trow.innerHTML = `
                    <td>${item.id}</td>
                    <td>${item.title}</td>
                    <td>${item.price}</td>
                    <td>${item.taxes}</td>
                    <td>${item.ads}</td>
                    <td>${item.discount}</td>
                    <td>${item.total}</td>
                    <td>${item.category}</td>
                    <td><button onclick="updateItem(${item.id})">Update</button></td>
                    <td><button onclick="deleteItem(${item.id})">Delete</button></td>
                `;
                tableBody.appendChild(trow);
            });
        }
    
    }
    
    // create new item and add it to the table
    create.onclick = function(e){
        e.preventDefault();
        let titleV = title.value.trim();
        let priceV = price.value.trim();
        let taxesV = taxes.value.trim();
        let adsV = ads.value.trim();
        let discountV = discount.value.trim();
        let countV = count.value.trim();
        let categoryV = category.value.trim();
        let totalV;
        if(titleV === '' || priceV === '' || taxesV === '' || countV === '' || categoryV === '' || adsV === ''){
            Swal.fire({
                title:'Hey',
                text:'Please fill these fields (title, price, taxes, count, category).',
                icon:'info',
                confirmButtonText: 'cool'
            });
        } else{
            if(discountV === ''){
                discountV = '0';
            }
            totalV = parseFloat(priceV) + parseFloat(taxesV) + parseFloat(adsV) - parseFloat(discountV);
            const item = new Items(id, titleV, priceV, taxesV, adsV, discountV,totalV, countV, categoryV);
            for(let i = 0; i < countV; i++){
                item.addItemToStorage(id, titleV, priceV, taxesV, adsV, discountV,totalV, countV, categoryV);
                item.addItemToTable(id, titleV, priceV, taxesV, adsV, discountV,totalV, countV, categoryV);
                id++;
            }
            clearInputs();
        }
    }
    
    
    // clear input fields functions
    function clearInputs(){
        title.value = '';
        price.value = '';
        taxes.value = '';
        ads.value = '';
        discount.value = '';
        count.value = '';
        category.value = '';
    }

    // the function to update the to total price continuously 
    function updateTotal(){
        let priceV = parseFloat(price.value) || 0;
        let taxesV = parseFloat(taxes.value) || 0;
        let adsV = parseFloat(ads.value) || 0;
        let discountV = parseFloat(discount.value) || 0;

        let calculatedTotal = priceV + taxesV + adsV - discountV;

        // Update the total display
        if (calculatedTotal >= 0) {
            total.textContent = `${calculatedTotal.toFixed(2)}`;
        } else {
            total.textContent = 'Invalid';
        }
    }

    // Attach keyup event listeners to the inputs
    price.addEventListener('keyup', updateTotal);
    taxes.addEventListener('keyup', updateTotal);
    ads.addEventListener('keyup', updateTotal);
    discount.addEventListener('keyup', updateTotal);

    Items.loadItemsFromStorage();

});


