let container = document.getElementById("drop-down-container");

document.body.addEventListener('click', function (e) {
    if (e.target.parentElement.id !== 'drop-down-title')
    if (container.classList.contains("active")) {
        container.classList.remove("active");
    }
})

container.addEventListener('click', function (){
    if (this.classList.contains("active")) {
        this.classList.remove("active");
    } else {
        this.classList.add("active");
    }
})

