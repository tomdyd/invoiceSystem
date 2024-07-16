const closeBtn = document.querySelector(".close");
const alert = document.querySelector(".alert_success");
const body = document.querySelector("body")

console.log(alert)
const closeAlert = () =>{
    body.removeChild(alert)
}

closeBtn.addEventListener('click', closeAlert)