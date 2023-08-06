async function checkResetPassCode(code, emailUser) {
    console.log('OUI');
    const res = await fetch("reset-password.php", {
        method: "POST",
        headers: {
            "Content-type": "application/x-www-form-urlencoded",
            "Origin": "https://larche.ovh",
            "mode": "no-cors",
            "Connection": "keep-alive",
            "Referer": "https://larche.ovh/changer-mdp.php"
        },
        body: `codeVerifEmail=${code}&requestType=changeNewPass&emailUser=${emailUser}`
    })
        let dataCode = await res.text();
        console.log(dataCode);
        if (dataCode === "validCode") {
                document.getElementById("formPutCodeToReset").style.display = "none";
                document.getElementById("titlePutCode").style.display = "none";
                document.getElementById("formPutNewPassword").style.display = "flex";
            } else if (dataCode === "notValidCode") {
                document.getElementById("resetCodeNotValid").style.display = "block";
            }
}