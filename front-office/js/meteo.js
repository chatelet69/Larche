window.setInterval(function(){
    getLocation();
}, 3600000);

window.onload = function getLocation(){
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(getCity)
    }
}
async function getCity(position){
    let lat = position.coords.latitude;
    let long = position.coords.longitude;

    const res = await fetch("https://api.openweathermap.org/geo/1.0/reverse?lat=" + lat + "&lon=" + long + "&limit=1&lang=fr&units=metric&appid=18666b319c5801ca454717eba8786877") 
    // units = metric pour avoir nos unités de mesures *C
    let resJson = await res.json()
    const city = resJson[0].name
    getMeteo(city)
}

async function getMeteo(city){
const res = await fetch('https://api.openweathermap.org/data/2.5/weather?q='+city+'&lang=fr&units=metric&appid=18666b319c5801ca454717eba8786877');
let resJson = await res.json();
let temp = resJson.main.temp;
temp = Math.round(temp);
const desc = resJson.weather[0].description;
const icon = resJson.weather[0].icon;
displayMeteo(city, temp, desc, icon)
}
async function displayMeteo(city, temp, desc, icon){
    const res = await fetch('../includes/meteo.php?city='+city+'&temp='+temp+'&desc='+desc+'&icon='+icon);
    const s = await res.text();
    let box = document.getElementById('meteo-box');
    box.innerHTML = s;
    box.style.display='flex';
}