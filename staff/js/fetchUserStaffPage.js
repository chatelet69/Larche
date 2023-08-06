function searchUserForStaffOption(str, popup) {
    if (str.length == 0) {
        document.querySelector(`#${popup} datalist`).innerHTML = "";
        document.querySelector(`#${popup} datalist`).style.display = "none";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                let userList = document.querySelector(`#${popup} datalist`);
                userList.setAttribute("autocomplete", "test");
                userList.removeAttribute("autocomplete");
                userList.style.display = "block";
                userList.innerHTML = "";
                let users = (this.responseText).split(',');
                users.pop();
                for (let i = 0; i < users.length; i++) {
                    if (!userList.querySelector(`[value="${users[i]}"]`)) {
                        let option = document.createElement("option");
                        option.innerHTML = users[i];
                        option.value = users[i];
                        option.setAttribute("onclick", `selectData(this.value,'${popup}')`);
                        userList.appendChild(option);
                    }
                }
                userList.setAttribute("autocomplete", "test");
                userList.removeAttribute("autocomplete");
            }
        };
        xmlhttp.open("POST", "https://staff.larche.ovh/includes/staff-options.php", true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlhttp.send(`requestType=searchUserForStaffOption&usernameToSearch=${str}`);
    }
};

function selectData(username, popup) {
    const input = document.querySelector(`#${popup} #username`);
    const userList = document.querySelector(`#${popup} datalist`);
    input.value = username;
    userList.style.display = "none";
    userList.innerHTML = "";
}