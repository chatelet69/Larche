// Gestion Articles

async function searchArticleForEdit(articleId) {
    let container = document.querySelector("#SearchArticleForEditPopup ul");
    if (articleId.length == 0) {
        container.innerHTML = "";
        container.style.display = "none";
        return;
    } else {
        try {
            let request = await fetch(`../includes/staff-options.php?requestType=searchArticleToEdit&articleId=${articleId}`, {
                method: "GET",
                mode: "no-cors",
                credentials: "same-origin",
                headers: {
                    "Content-type": "application/x-www-form-urlencoded",
                },
                referrer: "https://staff.larche.ovh/gestion-articles.php",
            });
            let response = await request.text();
            if (response !== false) {
               container.style.display = "block";
               container.innerHTML = response;
            }
        } catch (error) {
            console.log(error);
        }
    }
};

async function searchArticleByMember(type,input) {
    if (input.length === 0) {
        document.getElementById("staffInfosBox").innerHTML = "";
        document.getElementById("staffInfosBox").style.display = "none";
        return;
    } else {
        try {
            if (type === "username_author" || type === "id_article") {
                let request = await fetch(`../includes/staff-options.php?requestType=searchArticle&type=${type}&search=${input}`, {
                    method: "GET",
                    mode: "no-cors",
                    credentials: "same-origin",
                    headers: {
                        "Content-type": "application/x-www-form-urlencoded",
                    },
                    referrer: "https://staff.larche.ovh/gestion-articles.php",
                });
                let response = await request.text();
                if (response !== "ko") {
                    document.getElementById("staffInfosBox").style.display = "block";
                    document.getElementById("staffInfosBox").innerHTML = response;
                }
            }
        } catch (error) {
            console.log(error);
        }
    }
};

async function searchEventById(eventId) {
    if (eventId.length === 0) {
        document.getElementById("InfosContainer").innerHTML = "";
        document.getElementById("InfosContainer").style.display = "none";
        return;
    } else {
        try {
            let request = await fetch(`../includes/staff-options.php?requestType=searchEvent&eventId=${eventId}`, {
                method: "GET",
                mode: "no-cors",
                credentials: "same-origin",
                headers: {
                    "Content-type": "application/x-www-form-urlencoded",
                },
                referrer: "https://staff.larche.ovh/module/gestion-events.php",
            });
            let response = await request.text();
            if (response !== "error") {
                document.getElementById("InfosContainer").style.display = "block";
                document.getElementById("InfosContainer").innerHTML = response;
            }
        } catch (error) {
            console.log(error);
        }
    }
};

function deleteEvent(eventId) {
    let deleteEventBtn = document.querySelector(`#event-${eventId} form input[type='submit']`);
    deleteEventBtn.click();
}

async function saveArticleEdit() {
    let articleContainer = document.getElementById('articleToEditContainer');
    let form = new FormData();
    let newTitle = document.getElementById("newTitleArticleInput");
    let newContent = document.getElementById("newContentInput");
    let newTeaser = document.getElementById("newTeaserArticleInput");

    if (newTitle.placeholder !== newTitle.value && newTitle.value.length > 0) {
        form.append('newTitle', newTitle.value);
    }
    if (newContent.innerHTML !== newContent.value && newContent.value.length > 0) {
        form.append('newContent', newContent.value);
    }
    if (newTeaser.placeholder !== newTeaser.value && newTeaser.value.length > 0) {
        form.append('newTeaser', newTeaser.value);
    }
    try {
        form.append('requestType', 'editArticle');
        form.append('articleId', document.getElementById('idArticleToEditInput').value);
        let request = await fetch(`../includes/staff-options.php`, {
            method: "POST",
            mode: "no-cors",
            credentials: "same-origin",
            referrer: "https://staff.larche.ovh/module/gestion-articles.php",
            body: form
        });
        let response = await request.text();
        if (response !== "error") {
            searchArticleForEdit(document.getElementById('idArticleToEditInput').value);
        }
    } catch (error) {
        console.log(error);
    }
}