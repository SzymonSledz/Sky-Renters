function swapImage(id) {
    let mainBakcup = document.getElementById("mainImageId").src
    document.getElementById("mainImageId").src = document.getElementById(id).src
    document.getElementById(id).src = mainBakcup
}