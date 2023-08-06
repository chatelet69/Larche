function openPopup(popup) {
    let popups = document.querySelectorAll(".popup");
    popups.forEach(popup => {
        popup.style.display = "none";
    })
    document.getElementById(popup).style.display = "block";
    if (popup === "viewGraphLogs") getLogs();
}

function closePopup(popup) {
    let inputsId = document.querySelectorAll(`#${popup} input`)
    if (document.querySelector(`#${popup} ul`) !== null) document.querySelector(`#${popup} ul`).innerHTML = "";
    let inputs = document.querySelectorAll(".popup input[type='text']");
    inputs.forEach(input => { input.value = ""; });
    inputsId.forEach(inputId => { if (inputId.type !== "submit") inputId.value = ""; });
    document.getElementById(popup).style.display = "none";
}

function changeInput(newInput) {
    if (newInput === "usernameInputForArticle") {
        document.getElementById('idInputForArticle').style.display = "none";
        document.getElementById('idInputForArticle').value = "";
    } else if (newInput === "idInputForArticle") {
        document.getElementById('usernameInputForArticle').style.display = "none";
        document.getElementById('usernameInputForArticle').value = "";
    }
    document.getElementById(newInput).style.display = "block";
    document.getElementById("staffInfosBox").innerHTML = "";
}