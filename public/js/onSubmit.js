const loader = document.querySelector('.loader');

// Evènement sur le bouton js-verified
document.querySelectorAll('button.btn').forEach(function (link){
    link.addEventListener('click', (event) => {
        //event.preventDefault()
        loader.classList.remove('hidden')
        console.log(loader.classList)
    } );
})

