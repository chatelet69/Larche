let reloaded = false;

function reloadOnce() {
    if (!reloaded) {
        reloaded = true;
        location.reload();
    }
}

function setThemeMode(setThemeBtn) {
    let newTheme = setThemeBtn.innerHTML;
    setThemeBtn.innerHTML = (newTheme === "Dark-Mode") ? "Light-Mode" : "Dark-Mode";
    setTheme(newTheme);
}

function setTheme(themeName) {
    let theme = (themeName === "Dark-Mode") ? "darkMode" : "lightMode";
    localStorage.setItem('themeMode', theme);
    //reloadOnce();
    document.querySelector("html").setAttribute("data-theme", theme);
}

let htmlPage = document.querySelector("html");

if (localStorage.getItem("themeMode") === "darkMode") {
    htmlPage.setAttribute("data-theme", "darkMode");
} else if (localStorage.getItem("themeMode") === "lightMode") {
    htmlPage.setAttribute("data-theme", "lightMode");
} else {
    htmlPage.setAttribute("data-theme", "lightMode");
}

let dashboard = document.getElementById("sidebarMenu");
let isDash = getComputedStyle(dashboard).display;
var link = (isDash == "block") ? document.getElementById("setThemeBtn") : document.getElementById("setThemeBtnDropdown");
link.innerHTML = (localStorage.getItem('themeMode') === "darkMode") ? "Light-Mode" : "Dark-Mode";