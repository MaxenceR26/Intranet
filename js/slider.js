let flag = 0;

setInterval(() => {
    flag = flag + 1;
    slidesShow(flag);
}, 5000);
slidesShow(flag);
function slidesShow(num) {
    var getSlides = document.getElementsByClassName("slides");
    for (let i = 0; i < getSlides.length; i++) {
        const element = getSlides[i];
        element.style.animation = 'slide-out 0.5s forwards';
    }
    if (num >= getSlides.length) {
        flag = 0;
        num = 0;
    } else if (num < 0) {
        flag = getSlides.length - 1;
        num = getSlides.length - 1;
    }
    getSlides[num].style.animation = 'slide-in 0.5s forwards';
    getSlides[num].classList.add("active");
}