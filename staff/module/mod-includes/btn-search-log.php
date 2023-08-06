<div class="staff-view-box mb-2 col-sm-2 mt-2">
    <button class="btn-popup open-button" onclick="openPopup('viewLogSearchPopup')"><strong>Chercher une log</strong></button>
</div>
<div class="form-popup top-50 start-50 translate-middle" id="viewLogSearchPopup">
    <form id="formSearchLog" class="form-container bg-secondary p-3 popup-bg-dark-blue rounded-3 text-center text-light">
        <h2>Chercher une log</h2>
        <div>
            <label for="username">
                <strong>Par Pseudo</strong>
            </label>
            <input id="searchUserForLog" type="text" name="username" placeholder="pseudo" oninput="searchLog(this)">
        </div>
        <div class="d-flex flex-column align-items-center mb-1 mx-auto">
            <label class="form-label" for="id_log">
                <strong>Par ID</strong>
            </label>
            <input class="w-50 form-control" id="searchIdForLog" type="number" name="id_log" placeholder="ID" oninput="searchLog(this)">
        </div>
        <ul id="logInfoBox" class="w-auto list-group p-1 m-1">
        </ul>
        <button type="button" class="btn btn-danger cancel" onclick="closeViewLogSearch()">Fermer</button>
    </form>
</div>
<script>
    function closeViewLogSearch() {
        document.getElementById("searchUserForLog").value = "";
        document.getElementById("searchIdForLog").value = "";
        document.getElementById("logInfoBox").innerHTML = "";
        document.getElementById("viewLogSearchPopup").style.display = "none";
    }

    async function searchLog(str) {
        if (str.value.length === 0) {
            document.getElementById("logInfoBox").innerHTML = "";
            document.getElementById("logInfoBox").style.display = "none";
            return;
        } else {
            try {
                let logSearch = (str.type === "number") ? Number(str.value) : str.value;
                let strType = (str.type === "number") ? "id_log" : "username";
                let request = await fetch(`../includes/staff-options.php`, {
                    method: "POST",
                    mode: "no-cors",
                    credentials: "same-origin",
                    headers: {
                        "Content-type": "application/x-www-form-urlencoded",
                    },
                    referrer: "https://larche.ovh/webchat.php",
                    body: `requestType=logSearch&logToSearch=${logSearch}&typeToSearch=${strType}`
                });
                let res = await request.text();
                if (res !== "nop") {
                    document.getElementById("logInfoBox").style.display = "block";
                    document.getElementById("logInfoBox").innerHTML = res;
                }
            } catch (error) {
                console.log(error);
            }
        }
    }
</script>