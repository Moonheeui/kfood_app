// console.log('kfood.js is connected');

async function fetchFavorites(url) {
    const response = await fetch(url);
    const data = await response.json();
    displayData(data);
}

//call function to fetch data from the database
fetchFavorites('app/select.php');

function displayData(data) { 
    //select the display div from the DOM where the data will be displayed
    const display = document.querySelector('#display');
    display.innerHTML = '';

    let ul = document.createElement('ul');
    let a = document.createElement('a');

    data.forEach((user) => {
        //console.log(user);
        //create a list item for each user, add text and append to the ul
        let li = document.createElement('li');
        let a = document.createElement('a');
        a.href = `#${user.kfoodID}`;
        a.dataset.kfoodID = user.kfoodID;
        a.innerHTML = "Edit";
        li.innerHTML = `The menu ${user.name} chose is ${user.menu}.`;
        li.appendChild(a);
        a.addEventListener('click', (event) => {
            updateForm(event, user.kfoodID, user.name, user.menu);
        });
        ul.appendChild(li);
    })

    //append the ul to the element with the id of display
    display.appendChild(ul);
}

const submitBtn = document.querySelector('#submit');
submitBtn.addEventListener('click', getFormData);

function getFormData(event) {
    event.preventDefault();

    const insertFormData = new FormData(document.querySelector('#insert-form'));
    let url = 'app/insert.php';
    inserter(insertFormData, url);
}

async function inserter(data, url) { 
    const response = await fetch(url, {
        method: "POST",
        body: data
    });
    const confirmation = await response.json();

    console.log(confirmation);
    //call function again to refresh the data
    fetchFavorites('app/select.php');
}

//update form
function updateForm(event, kfoodID, user, menu) {
    console.log(kfoodID, user, menu);
    event.preventDefault();

    let li = event.target.parentNode;

    event.target.parentNode.innerHTML = `<form id="update-form"><input type="hidden" name="kfoodID" value="${kfoodID}"> <input type="text" name="full_name" value="${user}"> <input type="text" name="menu" value="${menu}"> <a href="#update" id="update">UPDATE</a></form>`;

    console.log(li.querySelector('#update'));
    li.querySelector('#update').addEventListener('click', (event) => {
        event.preventDefault();
        console.log(event);
        let updateData = new FormData(document.querySelector('#update-form'));
        let url = 'app/update.php';
        updater(updateData, url);
    })
}

//again with the names!!
async function updater(data, url) {
    const response = await fetch(url, {
        method: "POST",
        body: data
    });
    const confirmation = await response.json();

    console.log(confirmation);
    //call function again to refresh the page
    fetchFavorites('app/select.php');
}


