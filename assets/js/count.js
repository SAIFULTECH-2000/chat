function updateCounter() {
    fetch('https://api.countapi.xyz/get/aitalhomenursing.imrgroup.com.my/02d86d89-a09d-428d-a3fe-f38c1eca0c4d')
        .then(res => res.json())
        .then(data => counterElement.innerHTML = data.value)
}

updateCounter()



counterElement = document.getElementsByClassName('count')[0];