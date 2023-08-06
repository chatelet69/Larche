<div class="staff-view-box mb-2 col-sm-2 mt-2">
    <button class="btn-popup open-button" onclick="openPopup('viewGraphLogs')"><strong>Voir graphique</strong></button>
</div>
<div onplay="getLogs()" class="form-popup top-50 start-50 translate-middle" id="viewGraphLogs">
    <form class="form-container bg-secondary p-3 popup-bg-dark-blue rounded-3 text-center text-light">
        <h2>Graphique des logs</h2>

        <div id="graphLogs" class="w-auto p-1 m-1">
            <div id="graphContainer" class="d-flex flex-row-reverse align-items-end">

            </div>
            <!--<canvas id="graphicLogs" width="200" height="125">
            </canvas>-->
            <div id="graphDatesContainer" class="row">
            </div>
        </div>
        <!--<img class="m-1" id="loadingIcon" width="14vw" height="14vh" src="https://media.tenor.com/On7kvXhzml4AAAAj/loading-gif.gif" alt="gif">-->
        <input type="hidden" name="requestType" value="viewGraphLogs" hidden>
        <button type="button" class="btn btn-danger cancel" onclick="closeViewGraphLogs()">Fermer</button>
    </form>
</div>
<script src="../js/popups.js"></script>
<script>
    function closeViewGraphLogs() {
        document.getElementById("graphDatesContainer").innerHTML = "";
        document.getElementById("graphContainer").innerHTML = "";
        document.getElementById("viewGraphLogs").style.display = "none";
    }

    function getLogs() {
        fetch("../includes/staff-options.php", {
            method: "POST",
            headers: {
                "Content-type": "application/x-www-form-urlencoded",
                //"Content-Type": "application/json",
                "Origin": "https://staff.larche.ovh",
                "mode": "no-cors",
                "Connection": "keep-alive",
                "Referer": "https://staff.larche.ovh/panel-logs.php"
            },
            body: `requestType=getGraphLogs`
        }).then(function (res) {
            res.json().then((data) => {
                let graphContainer = document.getElementById("graphContainer");
                let graphLogs = document.getElementById("graphDatesContainer");
                /*let canvasGraph = document.getElementById("graphicLogs");
                let ctx = canvasGraph.getContext('2d');
                
                const lineColor = '#007bff';
                const lineWidth = 2;
                const pointColor = '#ffffff';
                const pointRadius = 5;

                const graphWidth = 200;
                const graphHeight = 125;

                ctx.beginPath();
		        ctx.moveTo(50, 50);

		        ctx.lineTo(450, 50);
		        ctx.stroke();*/
                for (let i = 0; i < data.length; i++) {
                    let newData = document.createElement("div");
                    newData.className = "graphLog col";
                    newData.style.height = `${data[i]['amount']*2}px`;
                    graphContainer.appendChild(newData);
                }
                for (let i = 0; i < data.length; i++) {
                    let newDate = document.createElement("span");
                    newDate.className = "text-primary col-1 col-sm-1 col-md-1";
                    newDate.innerHTML = data[i]['date'];
                    //newDate.style.height = `${data[i]['amount']*2}px`;
                    graphLogs.appendChild(newDate);
                } 
                //document.getElementById("graphLogs").innerHTML = data;
            })
            .catch(function (error) { console.log(error) });
        });
    }
</script>